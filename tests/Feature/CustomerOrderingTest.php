<?php

namespace Tests\Feature;

use App\Models\Meja;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Promo;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CustomerOrderingTest extends TestCase
{
    private Menu $menu;
    private int $option;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();

        if (DB::connection()->getDriverName() !== 'sqlite' || DB::connection()->getDatabaseName() !== ':memory:') {
            throw new \RuntimeException('Customer ordering tests require SQLite :memory:.');
        }
        foreach ([
            '2026_11_15_020854_create_menus_table',
            '2026_11_15_144056_create_mejas_table',
            '2026_11_19_060307_create_settings_table',
            '2026_11_22_115112_create_promos_table',
            '2026_11_24_170000_create_customer_order_tables',
        ] as $migration) {
            (require database_path("migrations/$migration.php"))->up();
        }

        config(['customer.tax_bps' => 1000, 'customer.simulate_payment' => true]);
        $this->menu = Menu::create(['nama_menu' => 'Kwetiau Fixture', 'kategori' => 'Makanan', 'harga' => '17000.50', 'stok' => 4, 'status' => 'tersedia']);
        $this->option = $this->menu->options()->create(['name' => 'Bakso Fixture', 'price' => '2000.25', 'max_quantity' => 2, 'active' => true])->id;
        Meja::create(['kode_meja' => 'TEST-01', 'area' => 'SAL1', 'lantai' => 1, 'jenis_area' => 'Indoor', 'kapasitas' => 4, 'status' => 'Kosong']);
        Setting::create(['nama_usaha' => 'Outlet Fixture', 'jam_operasional' => ['rabu' => ['buka' => true, 'jam_buka' => '08:00', 'jam_tutup' => '22:00']]]);
    }

    public function test_catalog_uses_database_and_promo_period_in_jakarta(): void
    {
        $this->travelTo(now('Asia/Jakarta')->setDate(2026, 11, 24)->setTime(12, 0));
        foreach ([
            ['Promo Hari Ini', '2026-11-24', '2026-11-24', 'aktif'],
            ['Promo Lama', '2026-11-01', '2026-11-23', 'aktif'],
            ['Promo Besok', '2026-11-25', '2026-11-30', 'aktif'],
            ['Promo Nonaktif', '2026-11-24', '2026-11-24', 'nonaktif'],
        ] as [$name, $start, $end, $status]) {
            Promo::query()->insert(['nama_promo' => $name, 'tanggal_mulai' => $start, 'tanggal_selesai' => $end, 'status' => $status, 'jenis_promo' => 'diskon']);
        }

        $this->get('/pelanggan?meja=TEST-01')->assertOk()->assertSee('value="TEST-01"', false)
            ->assertSeeText('Outlet Fixture')->assertSeeText('Promo Hari Ini')
            ->assertDontSeeText('Promo Lama')->assertDontSeeText('Promo Besok')->assertDontSeeText('Promo Nonaktif');
        $this->get('/pelanggan/menu/makanan')->assertOk()->assertSeeText('Kwetiau Fixture')->assertSeeText('17.000,50')
            ->assertSee('customer/cat-nasi.png')->assertDontSeeText('Ilustrasi kategori');
        $this->get('/pelanggan/informasi')->assertOk()->assertSeeText('Buka 08:00–22:00 WIB');
        $this->get('/login')->assertOk();
    }

    public function test_uploaded_menu_photo_uses_public_disk_and_replaces_category_illustration(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('menus/menu-fixture.png', file_get_contents(public_path('customer/logo.png')));
        $this->menu->update(['foto' => '/storage/menus/menu-fixture.png']);

        $this->get('/pelanggan/menu/makanan')->assertOk()->assertSee('/storage/menus/menu-fixture.png')
            ->assertDontSee('customer/cat-nasi.png');
        $this->get('/pelanggan/item/'.$this->menu->id)->assertOk()->assertSee('/storage/menus/menu-fixture.png');

        $this->menu->update(['foto' => 'menus/../logos/private.png']);
        $this->get('/pelanggan/menu/makanan')->assertOk()->assertSee('customer/cat-nasi.png')
            ->assertDontSee('logos/private.png');
    }

    public function test_data_check_reports_missing_media_without_altering_records(): void
    {
        Storage::fake('public');
        $this->menu->update(['foto' => 'menus/missing.png']);

        $this->artisan('customer:check-data')->expectsOutputToContain('Foto menu tersedia: 0/1')
            ->expectsOutputToContain('Referensi foto ada')->assertExitCode(1);

        $this->assertDatabaseHas('menus', ['id' => $this->menu->id, 'foto' => 'menus/missing.png']);
    }

    public function test_invalid_table_out_of_stock_and_invalid_options_are_rejected(): void
    {
        $this->postJson('/pelanggan/keranjang', $this->item())->assertUnprocessable()->assertJsonValidationErrors('cart');
        $this->postJson('/pelanggan/meja', ['code' => 'BUKAN-MEJA'])->assertUnprocessable();
        $this->selectTable();
        $this->postJson('/pelanggan/keranjang', $this->item(5))->assertUnprocessable();
        $this->postJson('/pelanggan/keranjang', $this->item(1, [$this->option => 3]))->assertUnprocessable();
        $this->postJson('/pelanggan/keranjang', $this->item(1, [999 => 1]))->assertUnprocessable();

        $line = $this->postJson('/pelanggan/keranjang', $this->item())->assertOk()->json('lines.0.id');
        $this->getJson('/pelanggan/keranjang')->assertJsonPath('lines.0.id', $line)->assertJsonPath('table', 'TEST-01');
        $this->postJson('/pelanggan/keranjang', ['action' => 'quantity', 'line' => $line, 'quantity' => 3])->assertOk()->assertJsonPath('totals.count', 3);
        $this->postJson('/pelanggan/keranjang', ['action' => 'remove', 'line' => $line])->assertOk()->assertJsonCount(0, 'lines');
        $this->menu->update(['status' => 'habis']);
        $this->postJson('/pelanggan/keranjang', $this->item())->assertUnprocessable();
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_order_prices_snapshot_idempotency_and_payment_are_server_controlled(): void
    {
        $this->selectTable();
        $state = $this->postJson('/pelanggan/keranjang', $this->item(2, [$this->option => 1]) + ['price' => 1])->assertOk()
            ->assertJsonPath('totals.subtotal', 3800150)->assertJsonPath('totals.tax', 380015)->assertJsonPath('totals.total', 4180165)->json();
        $this->postJson('/pelanggan/pembayaran', $this->checkout($state) + ['total' => 1, 'paid' => true])->assertOk();
        $order = Order::query()->sole();
        $this->assertSame('awaiting_payment', $order->status);
        $this->assertSame('pending', $order->payment->status);
        $this->assertEquals(4180165, $order->payment->amount_cents);
        $this->assertEquals(1900075, $order->items->sole()->unit_price_cents);
        $this->assertEquals(2, $this->menu->fresh()->stok);

        $this->postJson('/pelanggan/pembayaran', $this->checkout($state))->assertOk()->assertJsonPath('url', route('customer.payment', $order->number));
        $this->assertDatabaseCount('orders', 1);
        $this->assertEquals(2, $this->menu->fresh()->stok);
        $this->get(route('customer.success', $order->number))->assertRedirect(route('customer.order', $order->number));
        $this->post(route('customer.payment.simulate', $order->number))->assertRedirect(route('customer.success', $order->number));
        $this->assertSame('paid', $order->payment()->first()->status);
        $this->get(route('customer.order', $order->number))->assertOk()->assertSeeText('Rp41.801,65')->assertSeeText('Kwetiau Fixture');

        $this->app['session']->flush();
        $this->get(route('customer.order', $order->number))->assertNotFound();
        $this->post(route('customer.payment.simulate', $order->number))->assertNotFound();
    }

    public function test_stale_quotes_fail_and_cancelling_pending_payment_restores_stock_once(): void
    {
        $this->selectTable();
        $state = $this->postJson('/pelanggan/keranjang', $this->item(4))->assertOk()->json();
        $this->menu->update(['harga' => 18000]);
        $this->postJson('/pelanggan/pembayaran', $this->checkout($state))->assertUnprocessable();
        $this->assertDatabaseCount('orders', 0);
        $this->assertEquals(4, $this->menu->fresh()->stok);

        $updated = $this->getJson('/pelanggan/keranjang')->assertOk()->json();
        $this->postJson('/pelanggan/pembayaran', $this->checkout($updated))->assertOk();
        $order = Order::query()->sole();
        $this->assertEquals(0, $this->menu->fresh()->stok);

        $this->app->instance('env', 'production');
        $this->post(route('customer.payment.simulate', $order->number), ['_token' => csrf_token()])->assertNotFound();
        $this->postJson('/pelanggan/pembayaran', $this->checkout($updated), ['X-CSRF-TOKEN' => csrf_token()])->assertStatus(503);
        $this->assertSame('pending', $order->payment()->first()->status);
        $this->app->instance('env', 'testing');

        $this->post(route('customer.payment.cancel', $order->number))->assertRedirect(route('customer.order', $order->number));
        $this->post(route('customer.payment.cancel', $order->number))->assertRedirect(route('customer.order', $order->number));
        $this->assertEquals(4, $this->menu->fresh()->stok);
        $this->assertSame('cancelled', $order->fresh()->status);
    }

    private function selectTable(): void
    {
        $this->post('/pelanggan/meja', ['code' => 'TEST-01'])->assertRedirect('/pelanggan');
    }

    private function item(int $quantity = 1, array $options = []): array
    {
        return ['action' => 'item', 'menu' => $this->menu->id, 'quantity' => $quantity, 'options' => $options, 'note' => 'Tanpa saus'];
    }

    private function checkout(array $state): array
    {
        return ['checkout_key' => $state['key'], 'quote' => $state['signature'], 'name' => 'Pelanggan Fixture', 'payment' => 'qris', 'consent' => true];
    }
}
