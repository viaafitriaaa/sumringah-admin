<x-customer.layout :title="$summary ? 'Pembayaran Berhasil' : 'Detail Pesanan'" :page="$summary ? 'success' : 'order'" :demo="$demo">
    <x-customer.header :title="$summary ? 'Pembayaran Berhasil' : 'Detail Pesanan'" :back="$summary ? route('customer.home') : route('customer.success')" :close="$summary" />
    <main id="konten" class="flex flex-1 flex-col">
        <x-customer.empty title="Belum ada pesanan demo" description="Pesanan akan tampil setelah simulasi pembayaran selesai." :href="route('customer.catalog')" data-order-empty />

        @if ($summary)
            <div class="flex flex-1 flex-col px-5.5 pt-9 pb-6 sm:px-6" data-order-content hidden>
                <div class="text-center">
                    <x-customer.icon name="success-mark" class="mx-auto size-[95px]" />
                    <h2 class="mt-6 text-lg font-extrabold">Pembayaran Berhasil!</h2>
                    <p class="mt-1 text-xs text-subtle">Terima kasih atas pesanan Anda.</p>
                    <p class="mt-1 text-[10px] font-bold text-warkop">Simulasi pembayaran</p>
                </div>
                <x-customer.order-info summary class="mt-4.5" />
                <a href="{{ route('customer.order') }}" class="customer-cta mt-3.5 min-h-10.5">Lihat Detail Pesanan</a>
                <a href="{{ route('customer.home') }}" class="customer-cta customer-cta--outline mt-3 min-h-10.5">Kembali ke Beranda</a>
            </div>
        @else
            <div class="flex flex-1 flex-col px-5.5 pt-3.5 pb-5 sm:px-6" data-order-content hidden>
                <div class="flex items-center gap-2 rounded-[5px] bg-[#c3e7c7] px-3 py-2.5 shadow-warkop">
                    <x-customer.icon name="success-mark" class="size-6" />
                    <div>
                        <strong class="block text-sm">Pembayaran Berhasil!</strong>
                        <p class="text-[11px]">Simulasi · pesanan sedang diproses oleh dapur.</p>
                    </div>
                </div>
                <h2 class="mt-3 mb-2 text-xs font-extrabold sm:text-sm">Informasi Pesanan</h2>
                <x-customer.order-info />
                <h2 class="mt-4 mb-2 text-xs font-extrabold sm:text-sm">Daftar Menu</h2>
                <ul class="space-y-2.5" data-order-lines></ul>
                <p class="mt-2 text-[11px] wrap-break-word text-subtle" data-receipt-note hidden></p>
                <section class="customer-panel mt-4 space-y-2 p-3 text-xs shadow-warkop" aria-labelledby="receipt-summary">
                    <h2 id="receipt-summary" class="font-heading text-sm">Rincian Pembayaran</h2>
                    <dl class="space-y-1">
                        <div class="flex justify-between gap-2"><dt>Subtotal</dt><dd data-order-field="subtotal"></dd></div>
                        <div class="flex justify-between gap-2"><dt>PBJT demo (<span data-order-field="rate"></span>%)</dt><dd data-order-field="tax"></dd></div>
                        <div class="flex justify-between gap-2"><dt>Diskon</dt><dd>Rp0</dd></div>
                        <div class="mt-3 flex items-center justify-between gap-2 rounded bg-table px-2.5 py-3 text-sm font-extrabold"><dt>Total Pembayaran</dt><dd class="text-warkop" data-order-field="total"></dd></div>
                    </dl>
                </section>
                <div class="mt-auto pt-8">
                    <a href="{{ route('customer.home') }}" class="customer-cta customer-cta--outline min-h-10.5 w-full">Kembali ke Beranda</a>
                </div>
            </div>
            <template data-order-line-template>
                <li class="grid grid-cols-[85px_minmax(0,1fr)_auto] items-start gap-2.5 sm:gap-4">
                    <img data-order-line-photo alt="" class="customer-product-image h-[75px]">
                    <div class="min-w-0 pt-1.5">
                        <strong data-order-line-name class="block text-sm wrap-break-word"></strong>
                        <span data-order-line-price class="block text-xs text-subtle"></span>
                        <span data-order-line-extra class="block text-[10px] wrap-break-word text-subtle"></span>
                    </div>
                    <span data-order-line-quantity class="pt-2 text-xs font-extrabold"></span>
                </li>
            </template>
        @endif
    </main>
</x-customer.layout>
