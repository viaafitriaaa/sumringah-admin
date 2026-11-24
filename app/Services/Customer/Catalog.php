<?php

namespace App\Services\Customer;

use App\Models\Menu;
use App\Models\Promo;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class Catalog
{
    public function data(): array
    {
        $menus = Menu::query()->with('options')->orderByDesc('created_at')->orderByDesc('id')->get();
        $settings = Setting::query()->oldest('id')->first();
        $categories = [];
        $items = [];

        foreach ($menus as $menu) {
            $label = trim($menu->kategori) ?: 'Menu';
            $slug = Str::slug($label) ?: 'kategori';
            if (isset($categories[$slug]) && $categories[$slug]['name'] !== $label) {
                $slug .= '-'.substr(sha1($label), 0, 8);
            }
            $categories[$slug] ??= ['name' => $label, 'image' => match (strtolower($label)) {
                'makanan' => 'cat-nasi.png',
                'minuman' => 'cat-kopi.png',
                'snack', 'jajanan' => 'cat-jajan.png',
                default => 'cat-paket.png',
            }];

            $items[$menu->id] = [
                'id' => (string) $menu->id,
                'category' => $slug,
                'nama_menu' => $menu->nama_menu,
                'deskripsi' => $menu->deskripsi,
                'price' => Money::cents($menu->harga),
                ...$this->menuPhoto($menu->foto, $categories[$slug]['image']),
                'stok' => $menu->status === 'tersedia' && Money::cents($menu->harga) >= 0 ? max(0, min(99, $menu->stok)) : 0,
                'options' => $menu->options->where('active', true)->filter(fn ($option) => Money::cents($option->price) >= 0)
                    ->mapWithKeys(fn ($option) => [$option->id => [
                        'name' => $option->name,
                        'price' => Money::cents($option->price),
                        'max' => min(99, $option->max_quantity),
                    ]])->all(),
            ];
        }

        $timezone = config('customer.timezone');
        $date = now($timezone)->toDateString();
        $promos = Promo::query()->activeOn($date)->orderBy('tanggal_selesai')->orderBy('id')->get()->map(fn ($promo) => [
            'name' => $promo->nama_promo,
            'description' => $promo->deskripsi,
            'terms' => $promo->ketentuan,
            'until' => $promo->tanggal_selesai,
            'image' => $this->media($promo->gambar, 'promos'),
        ]);

        return [
            'categories' => $categories,
            'items' => $items,
            'latest' => array_slice(array_filter($items, fn ($item) => $item['stok'] > 0), 0, 3, true),
            'promos' => $promos,
            'timeZone' => $timezone,
            'taxBps' => Money::taxBps(),
            'outlet' => [
                'name' => $settings?->nama_usaha ?? 'Warkop Sumringah',
                'address' => $settings?->alamat,
                'email' => filter_var($settings?->email, FILTER_VALIDATE_EMAIL) ?: null,
                'whatsapp' => $this->whatsapp($settings?->whatsapp),
                'logo' => $this->media($settings?->logo, 'logos') ?? asset('customer/logo.png'),
                'hours' => $this->outletHours($settings?->jam_operasional),
            ],
        ];
    }

    public function menuPhoto(?string $path, string $fallback = 'logo.png'): array
    {
        $url = $this->media($path, 'menus');

        return [
            'image' => $url ?? asset('customer/'.$fallback),
            'fallback' => $url === null,
            'categoryIllustration' => $url === null && $fallback !== 'logo.png',
        ];
    }

    public function media(?string $path, string $directory): ?string
    {
        if ($path === null) {
            return null;
        }

        $path = str_replace('\\', '/', trim($path));
        foreach (['storage/app/public/', 'public/storage/', '/storage/', 'storage/'] as $prefix) {
            if (str_starts_with($path, $prefix)) {
                $path = substr($path, strlen($prefix));
                break;
            }
        }
        if (!str_starts_with($path, $directory.'/')
            || !preg_match('~\A[\p{L}\p{N}_ /.-]+\.(?:png|jpe?g|webp|gif|avif)\z~iu', $path)) {
            return null;
        }
        foreach (explode('/', $path) as $segment) {
            if ($segment === '' || str_starts_with($segment, '.')) {
                return null;
            }
        }

        $disk = Storage::disk('public');

        return $disk->fileExists($path) ? $disk->url($path) : null;
    }

    public function outletHours(?array $stored): array
    {
        $hours = [];
        foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $day) {
            $entry = $stored[strtolower($day)] ?? null;
            if (!is_array($entry)) {
                $hours[$day] = 'Belum diatur';
            } elseif (empty($entry['buka'])) {
                $hours[$day] = 'Tutup';
            } else {
                $start = $entry['jam_buka'] ?? '';
                $end = $entry['jam_tutup'] ?? '';
                $hours[$day] = is_string($start) && is_string($end)
                    && preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/D', $start)
                    && preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/D', $end)
                    ? "Buka {$start}–{$end} WIB"
                    : 'Jam belum diatur';
            }
        }

        return $hours;
    }

    private function whatsapp(?string $value): ?string
    {
        $phone = preg_replace('/\D/', '', $value ?? '');
        if (str_starts_with($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        }

        return strlen($phone) >= 8 && strlen($phone) <= 15 ? $phone : null;
    }
}
