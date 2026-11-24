<x-customer.layout title="Pesanan" page="cart" :customer="$customer" :state="$state">
    <x-customer.header title="Pesanan" :back="route('customer.catalog')" />
    <main id="konten" class="pb-5">
        <x-customer.empty title="Keranjang masih kosong" description="Tambahkan menu yang ingin kamu pesan." :href="route('customer.catalog')" data-cart-empty />

        <div data-cart-content hidden>
            <div class="customer-container mt-4">
                <label class="customer-panel flex min-h-9 items-center justify-between gap-2 border-warkop bg-tint px-2.5 text-xs">
                    <span>Tipe Pemesanan</span>
                    <select data-order-type class="max-w-[60%] cursor-pointer rounded-sm bg-transparent py-1 text-right font-bold">
                        <option value="dine-in">Makan di tempat</option>
                        <option value="takeaway">Bawa pulang</option>
                    </select>
                </label>
                <div class="mt-3 flex min-h-9 items-center justify-between gap-2 border-b border-rule pb-1.5">
                    <h2 class="text-sm font-extrabold">Item yang dipesan (<span data-cart-count>0</span>)</h2>
                    <a href="{{ route('customer.catalog') }}" class="customer-cta customer-cta--outline min-h-7 px-2 text-[10px]">+ Tambah Item</a>
                </div>
                <ul data-cart-lines class="divide-y divide-rule"></ul>
            </div>

            <div class="mt-3 border-t border-rule px-4.5 py-3 sm:px-6">
                <label for="cart-note" class="block text-xs font-bold">Catatan pesanan</label>
                <textarea id="cart-note" data-cart-note rows="2" maxlength="160" class="customer-input mt-1.5 p-2" placeholder="Tambahan catatan lainnya"></textarea>
            </div>

            <section aria-labelledby="cart-totals" class="customer-panel customer-container mx-3.5 space-y-2 py-3 sm:mx-6">
                <h2 id="cart-totals" class="text-center font-heading text-sm">Rincian Pembayaran</h2>
                <dl class="space-y-2 text-xs">
                    <div class="flex justify-between gap-2"><dt>Subtotal</dt><dd data-subtotal></dd></div>
                    <div class="flex justify-between gap-2 text-subtle"><dt>Diskon</dt><dd data-discount></dd></div>
                    <div class="flex justify-between gap-2 text-subtle"><dt>PBJT ({{ $customer['taxBps'] / 100 }}%)</dt><dd data-tax></dd></div>
                    <div class="flex justify-between gap-2 text-sm font-extrabold"><dt>Total</dt><dd class="text-warkop" data-total></dd></div>
                </dl>
            </section>
            <p data-cart-error role="alert" hidden class="customer-container mt-3 text-xs font-bold text-warkop"></p>
        </div>
    </main>

    <x-customer.total-bar data-cart-footer hidden>
        <a href="{{ route('customer.checkout') }}" data-checkout-link class="customer-cta min-w-39 sm:min-w-52">Lanjut Pembayaran</a>
    </x-customer.total-bar>

    <template data-cart-line-template>
        <li class="grid gap-1 px-1 py-3 text-xs">
            <div class="flex items-start justify-between gap-2">
                <strong data-line-name class="min-w-0 wrap-break-word"></strong>
                <a data-line-edit class="customer-cta customer-cta--outline min-h-7 shrink-0 px-2 text-[10px]">Ubah</a>
            </div>
            <p data-line-options class="text-[11px] text-subtle" hidden></p>
            <p data-line-note class="text-[11px] wrap-break-word text-subtle" hidden></p>
            <p data-line-issue class="text-[11px] font-bold text-warkop" hidden></p>
            <div class="flex flex-wrap items-center justify-between gap-x-2 gap-y-1">
                <span data-line-price class="customer-price"></span>
                <div class="flex items-center gap-2">
                    <x-customer.quantity label="jumlah item" :max="1" :value="1" data-min="1" />
                    <button type="button" data-line-remove class="min-h-8 rounded px-1 text-[11px] font-bold text-warkop hover:underline">Hapus</button>
                </div>
            </div>
        </li>
    </template>
</x-customer.layout>
