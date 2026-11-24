<?php

namespace App\Services\Customer;

use App\Models\Meja;
use App\Models\Menu;
use App\Models\MenuOption;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

final class Cart
{
    public function data(Session $session): array
    {
        $data = $session->get('customer.cart', [
            'lines' => [],
            'type' => 'dine-in',
            'note' => '',
            'key' => (string) Str::uuid(),
        ]);
        $session->put('customer.cart', $data);

        return $data;
    }

    public function table(Session $session, bool $lock = false): ?Meja
    {
        $id = $session->get('customer.table');

        return $id ? Meja::query()->when($lock, fn ($query) => $query->lockForUpdate())->find($id) : null;
    }

    public function selectTable(Session $session, string $code): void
    {
        $table = Meja::query()->where('kode_meja', $code)->first();
        if (!$table) {
            $this->fail('Kode meja tidak ditemukan. Periksa kode pada meja.');
        }
        $previous = $this->table($session);
        if ($previous && $this->data($session)['lines'] && $previous->id !== $table->id) {
            $this->fail('Kosongkan keranjang sebelum mengganti meja.');
        }
        $session->put('customer.table', $table->id);
    }

    public function accessHash(Session $session): string
    {
        $key = $session->get('customer.access');
        if (!$key) {
            $key = Str::random(64);
            $session->put('customer.access', $key);
        }

        return hash('sha256', $key);
    }

    public function change(Session $session, array $input): array
    {
        $data = $this->data($session);
        $lines = $data['lines'];
        $lineId = $input['line'] ?? null;

        switch ($input['action']) {
            case 'details':
                $data['type'] = $input['type'] ?? $data['type'];
                if (array_key_exists('note', $input)) {
                    $data['note'] = $input['note'] ?? '';
                }
                break;
            case 'remove':
                unset($lines[$lineId]);
                break;
            default:
                if (!$this->table($session)) {
                    $this->fail('Pilih meja sebelum menambahkan menu.');
                }
                if ($lineId && !isset($lines[$lineId])) {
                    $this->fail('Item sudah tidak ada di keranjang.');
                }
                $old = $lines[$lineId] ?? null;
                $id = $input['menu'] ?? $old['menu'] ?? null;
                $quantity = (int) ($input['quantity'] ?? 0);
                $options = $input['options'] ?? $old['options'] ?? [];
                $note = $input['action'] === 'quantity' ? $old['note'] : ($input['note'] ?? '');

                if (!$lineId && count($lines) >= 30) {
                    $this->fail('Keranjang sudah mencapai batas 30 baris.');
                }
                if (!$id || ($old && (int) $id !== $old['menu'])) {
                    $this->fail('Menu tidak valid.');
                }
                $lineId ??= (string) Str::uuid();
                $lines[$lineId] = ['menu' => (int) $id, 'quantity' => $quantity, 'options' => $options, 'note' => $note];
                $quote = $this->quote($lines);
                $updated = collect($quote['lines'])->firstWhere('id', $lineId);
                if ($updated['issue']) {
                    $this->fail($updated['issue']);
                }
                break;
        }

        $data['lines'] = $lines;
        $data['key'] = (string) Str::uuid();
        $session->put('customer.cart', $data);

        return $this->state($session);
    }

    public function state(Session $session): array
    {
        $data = $this->data($session);
        $quote = $this->quote($data['lines']);

        return $quote + [
            'key' => $data['key'],
            'type' => $data['type'],
            'note' => $data['note'],
            'table' => $this->table($session)?->kode_meja,
            'lastOrder' => $session->get('customer.last_order'),
        ];
    }

    public function quote(array $lines, bool $lock = false): array
    {
        $ids = array_unique(array_column($lines, 'menu'));
        $menus = Menu::query()->whereIn('id', $ids)->orderBy('id')
            ->when($lock, fn ($query) => $query->lockForUpdate())->get()->keyBy('id');
        $options = MenuOption::query()->whereIn('menu_id', $ids)->orderBy('id')
            ->when($lock, fn ($query) => $query->lockForUpdate())->get()->groupBy('menu_id');
        $quantities = [];
        foreach ($lines as $line) {
            $quantities[$line['menu']] = ($quantities[$line['menu']] ?? 0) + $line['quantity'];
        }

        $rows = [];
        $subtotal = 0;
        $count = 0;
        foreach ($lines as $id => $line) {
            $menu = $menus->get($line['menu']);
            $quantity = $line['quantity'];
            $base = $menu ? Money::cents($menu->harga) : 0;
            $available = $menu && $menu->status === 'tersedia' && $base >= 0 ? max(0, min(99, $menu->stok)) : 0;
            $maximum = max(0, $available - $quantities[$line['menu']] + $quantity);
            $issue = match (true) {
                !$menu => 'Menu sudah tidak tersedia.',
                $quantity < 1 => 'Jumlah pesanan minimal 1.',
                $quantity > $maximum => 'Jumlah melebihi stok menu yang tersedia.',
                default => null,
            };

            $definitions = $options->get($line['menu'], collect())->keyBy('id');
            $extras = [];
            $unit = max(0, $base);
            foreach ($line['options'] as $key => $amount) {
                $amount = (int) $amount;
                if (!$amount) {
                    continue;
                }
                $option = $definitions->get($key);
                if (!$option || !$option->active || $amount > $option->max_quantity || Money::cents($option->price) < 0) {
                    $issue ??= 'Pilihan tambahan sudah berubah. Ubah item ini.';
                    continue;
                }
                $price = Money::cents($option->price);
                $extras[] = ['id' => (string) $option->id, 'name' => $option->name, 'quantity' => $amount, 'price' => $price];
                $unit += $price * $amount;
            }
            $subtotal += $unit * $quantity;
            $count += $quantity;
            $rows[] = [
                'id' => $id, 'item' => (string) $line['menu'], 'name' => $menu?->nama_menu ?? 'Menu tidak tersedia',
                'quantity' => $quantity, 'options' => $extras, 'note' => $line['note'], 'base' => $base,
                'unit' => $unit, 'amount' => $unit * $quantity, 'max' => $maximum, 'issue' => $issue,
                'photo' => $menu?->foto,
            ];
        }
        $taxBps = Money::taxBps();
        $tax = intdiv($subtotal, 10000) * $taxBps + intdiv(($subtotal % 10000) * $taxBps + 5000, 10000);
        $totals = ['subtotal' => $subtotal, 'discount' => 0, 'tax' => $tax, 'taxBps' => $taxBps, 'total' => $subtotal + $tax, 'count' => $count];

        return [
            'lines' => $rows,
            'totals' => $totals,
            'signature' => hash('sha256', json_encode([$rows, $totals], JSON_THROW_ON_ERROR)),
            'valid' => $rows && !collect($rows)->contains(fn ($line) => $line['issue'] !== null),
        ];
    }

    public function clear(Session $session): void
    {
        $session->forget('customer.cart');
    }

    public function fail(string $message): never
    {
        throw ValidationException::withMessages(['cart' => $message]);
    }
}
