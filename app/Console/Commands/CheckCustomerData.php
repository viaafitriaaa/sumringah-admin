<?php

namespace App\Console\Commands;

use App\Models\Meja;
use App\Models\Menu;
use App\Models\Promo;
use App\Models\Setting;
use App\Services\Customer\Catalog;
use Illuminate\Console\Command;

class CheckCustomerData extends Command
{
    protected $signature = 'customer:check-data';

    protected $description = 'Periksa kelengkapan data katalog pelanggan tanpa mengubah database';

    public function handle(Catalog $catalog): int
    {
        $issues = [];
        $menus = Menu::query()->orderBy('id')->get(['id', 'nama_menu', 'kategori', 'harga', 'stok', 'status', 'foto']);
        $loaded = 0;

        foreach ($menus as $menu) {
            $item = "#{$menu->id} {$menu->nama_menu}";
            if ($catalog->media($menu->foto, 'menus')) {
                $loaded++;
            } else {
                $issues[] = [$item, filled($menu->foto) ? 'Referensi foto ada, file tidak dapat diakses dari disk public.' : 'Foto belum diisi.'];
            }
            if (blank($menu->nama_menu) || blank($menu->kategori) || $menu->harga < 0 || $menu->stok < 0) {
                $issues[] = [$item, 'Nama, kategori, harga, atau stok tidak valid.'];
            }
        }
        if ($menus->isEmpty()) {
            $issues[] = ['Katalog', 'Belum ada menu.'];
        }
        if (!Meja::query()->whereNotNull('kode_meja')->where('kode_meja', '<>', '')->exists()) {
            $issues[] = ['Meja', 'Belum ada kode meja yang dapat dipilih.'];
        }

        $settings = Setting::query()->oldest('id')->first();
        if (!$settings || blank($settings->nama_usaha) || blank($settings->alamat)) {
            $issues[] = ['Outlet', 'Lengkapi profil dan alamat outlet.'];
        }
        $missingDays = array_filter($catalog->outletHours($settings?->jam_operasional), fn (string $value) => str_contains(strtolower($value), 'belum diatur'));
        if ($missingDays) {
            $issues[] = ['Jam outlet', 'Periksa '.implode(', ', array_keys($missingDays)).'.'];
        }
        if (filled($settings?->logo) && !$catalog->media($settings->logo, 'logos')) {
            $issues[] = ['Logo outlet', 'Referensi logo ada, file belum tersedia.'];
        }
        foreach (Promo::query()->activeOn(now(config('customer.timezone'))->toDateString())->get(['nama_promo', 'gambar']) as $promo) {
            if (filled($promo->gambar) && !$catalog->media($promo->gambar, 'promos')) {
                $issues[] = [$promo->nama_promo, 'File gambar promo aktif belum tersedia.'];
            }
        }
        if (config('filesystems.disks.public.driver') === 'local' && !is_dir(public_path('storage'))) {
            $issues[] = ['Penyimpanan', 'public/storage belum tersedia. Jalankan php artisan storage:link.'];
        }

        $this->line("Foto menu tersedia: {$loaded}/{$menus->count()}");
        if ($issues) {
            $this->table(['Data', 'Yang perlu dilengkapi'], $issues);

            return self::FAILURE;
        }

        $this->info('Pemeriksaan data katalog pelanggan lulus.');

        return self::SUCCESS;
    }
}
