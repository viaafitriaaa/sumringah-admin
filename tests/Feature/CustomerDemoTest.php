<?php

namespace Tests\Feature;

use Tests\TestCase;

class CustomerDemoTest extends TestCase
{
    public function test_customer_pages_render_without_a_database(): void
    {
        $this->withoutVite();
        config(['database.default' => 'customer_audit_disabled']);

        foreach ([
            '/pelanggan' => 'Halaman Utama',
            '/pelanggan/menu/paket-hemat' => 'Paket Hemat 1',
            '/pelanggan/menu/pernasian-duniah' => 'Kwetiau Goreng Spesial',
            '/pelanggan/item/kwetiau-spesial' => 'Recommended Add On',
            '/pelanggan/pesanan' => 'Keranjang masih kosong',
            '/pelanggan/pembayaran' => 'Simulasi QRIS',
            '/pelanggan/pembayaran-berhasil' => 'Pembayaran Berhasil',
            '/pelanggan/detail-pesanan' => 'Detail Pesanan',
            '/pelanggan/informasi' => 'Jam Operasional',
        ] as $path => $content) {
            $this->get($path)->assertOk()->assertSeeText($content);
        }

        $this->get('/pelanggan/menu/bukan-kategori')->assertNotFound();
        $this->get('/pelanggan/item/bukan-item')->assertNotFound();
    }

    public function test_demo_data_and_checkout_are_unavailable_in_production(): void
    {
        $this->app->instance('env', 'production');
        config(['app.env' => 'production', 'app.debug' => false]);

        $this->get('/pelanggan')->assertNotFound()->assertDontSee('customer-data');
        $this->get('/pelanggan/pembayaran')->assertNotFound();
        $this->get('/pelanggan/pembayaran-berhasil')->assertNotFound();
    }
}
