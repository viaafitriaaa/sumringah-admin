<?php

namespace App\Services\Customer;

use App\Models\Menu;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class Ordering
{
    public function __construct(private Cart $cart) {}

    public function simulationEnabled(): bool
    {
        return app()->environment(['local', 'testing']) && (bool) config('customer.simulate_payment');
    }

    public function place(Session $session, array $input, string $provider): Order
    {
        $accessHash = $this->cart->accessHash($session);
        $draft = $this->cart->data($session);

        $order = DB::transaction(function () use ($session, $input, $accessHash, $draft, $provider) {
            $existing = Order::query()->where('checkout_key', $input['checkout_key'])->first();
            if ($existing) {
                abort_unless(hash_equals($existing->access_hash, $accessHash), 404);

                return $existing;
            }
            if (!hash_equals($draft['key'], $input['checkout_key'])) {
                $this->cart->fail('Keranjang berubah. Muat ulang dan periksa pesanan.');
            }
            $table = $this->cart->table($session, true);
            if (!$table) {
                $this->cart->fail('Pilih ulang nomor meja sebelum melanjutkan.');
            }
            $quote = $this->cart->quote($draft['lines'], true);
            if (!$quote['valid']) {
                $this->cart->fail('Keranjang kosong atau stok atau opsi sudah berubah. Periksa keranjang.');
            }
            if (!hash_equals($quote['signature'], $input['quote'])) {
                $this->cart->fail('Harga atau ketersediaan berubah. Muat ulang dan periksa total terbaru.');
            }
            $totals = $quote['totals'];
            if ($provider === Payment::MIDTRANS && ($totals['total'] < 100 || $totals['total'] > 1_000_000_000)) {
                $this->cart->fail('Total pembayaran QRIS harus antara Rp1 dan Rp10.000.000.');
            }
            $order = Order::create([
                'number' => 'WS-'.Str::ulid(),
                'checkout_key' => $input['checkout_key'],
                'access_hash' => $accessHash,
                'meja_id' => $table->id,
                'table_code' => $table->kode_meja,
                'customer_name' => $input['name'],
                'customer_phone' => $input['phone'] ?? null,
                'customer_email' => $input['email'] ?? null,
                'type' => $draft['type'],
                'note' => $draft['note'],
                'status' => Order::AWAITING,
                'subtotal_cents' => $totals['subtotal'],
                'discount_cents' => $totals['discount'],
                'tax_bps' => $totals['taxBps'],
                'tax_cents' => $totals['tax'],
                'rounding_cents' => $totals['rounding'],
                'total_cents' => $totals['total'],
            ]);
            foreach ($quote['lines'] as $line) {
                $decremented = Menu::query()->whereKey($line['item'])->where('status', 'tersedia')
                    ->where('stok', '>=', $line['quantity'])->decrement('stok', $line['quantity']);
                if ($decremented !== 1) {
                    $this->cart->fail('Stok berubah. Periksa keranjang.');
                }
                $order->items()->create([
                    'menu_id' => $line['item'],
                    'name' => $line['name'],
                    'photo' => $line['photo'],
                    'base_price_cents' => $line['base'],
                    'unit_price_cents' => $line['unit'],
                    'quantity' => $line['quantity'],
                    'options' => $line['options'],
                    'note' => $line['note'],
                ]);
            }
            $order->payment()->create([
                'method' => 'qris',
                'provider' => $provider,
                'status' => 'pending',
                'amount_cents' => $totals['total'],
            ]);

            return $order;
        }, 3);

        if (hash_equals($draft['key'], $input['checkout_key'])) {
            $this->cart->clear($session);
        }
        $session->put('customer.last_order', $order->number);

        return $order;
    }

    public function owned(Session $session, string $number): Order
    {
        return Order::query()->where('number', $number)->where('access_hash', $this->cart->accessHash($session))->firstOrFail();
    }

    public function resolveSimulation(Order $order, bool $paid): void
    {
        abort_unless($this->simulationEnabled(), 404);

        $this->applyPayment($order, Payment::SIMULATION, [], $paid ? 'paid' : 'cancelled');
    }

    public function applyPayment(Order $order, string $provider, array $details, ?string $outcome): void
    {
        DB::transaction(function () use ($order, $provider, $details, $outcome) {
            $order = Order::query()->lockForUpdate()->findOrFail($order->id);
            $payment = $order->payment()->lockForUpdate()->firstOrFail();
            abort_unless($payment->provider === $provider, 404);
            if ($order->status !== Order::AWAITING || $payment->status !== 'pending') {
                return;
            }

            if ($outcome === 'cancelled') {
                foreach ($order->items()->whereNotNull('menu_id')->orderBy('menu_id')->get() as $line) {
                    Menu::query()->whereKey($line->menu_id)->increment('stok', $line->quantity);
                }
            }

            if ($outcome !== null) {
                $payment->update($details + ['status' => $outcome, 'paid_at' => $outcome === 'paid' ? now() : null]);
                $order->update(['status' => $outcome === 'paid' ? Order::CONFIRMED : Order::CANCELLED]);
            } else {
                $payment->update($details);
            }
        }, 3);
    }
}
