<x-customer.layout :title="$summary ? 'Pembayaran Berhasil' : 'Detail Pesanan'" :page="$summary ? 'success' : 'order'" :customer="$customer" :state="$state">
    <x-customer.header :title="$summary ? 'Pembayaran Berhasil' : 'Detail Pesanan'" :back="route('customer.home')" :close="$summary" />
    <main id="konten" class="flex flex-1 flex-col">
        @if (!$order)
            <x-customer.empty title="Belum ada pesanan" description="Pesanan akan tampil setelah dikirim dari checkout." :href="route('customer.catalog')" />
        @elseif ($summary)
            <div class="flex flex-1 flex-col px-5.5 pt-9 pb-6 sm:px-6" data-order-content>
                <div class="text-center">
                    <x-customer.icon name="success-mark" class="mx-auto size-[95px]" />
                    <h2 class="mt-6 text-lg font-extrabold">Pembayaran Berhasil!</h2>
                    <p class="mt-1 text-xs text-subtle">Terima kasih atas pesanan Anda.</p>
                    @if ($order->payment?->provider === \App\Models\Payment::SIMULATION)
                        <p class="mt-1 text-[10px] font-bold text-warkop">Simulasi pembayaran</p>
                    @elseif ($order->payment?->provider === \App\Models\Payment::MIDTRANS)
                        <p class="mt-1 text-[10px] font-bold text-warkop">Midtrans Sandbox</p>
                    @endif
                </div>
                <x-customer.order-info :order="$order" :timezone="$customer['timeZone']" summary class="mt-4.5" />
                <a href="{{ route('customer.order', $order->number) }}" class="customer-cta mt-3.5 min-h-10.5">Lihat Detail Pesanan</a>
                <a href="{{ route('customer.home') }}" class="customer-cta customer-cta--outline mt-3 min-h-10.5">Kembali ke Beranda</a>
            </div>
        @else
            <div class="flex flex-1 flex-col px-5.5 pt-3.5 pb-5 sm:px-6" data-order-content>
                <div @class(['flex items-center gap-2 rounded-[5px] px-3 py-2.5 shadow-warkop', 'bg-[#c3e7c7]' => $order->status === 'confirmed', 'bg-tint' => $order->status !== 'confirmed'])>
                    @if ($order->status === 'confirmed') <x-customer.icon name="success-mark" class="size-6" /> @endif
                    <div>
                        <strong class="block text-sm">{{ match ($order->status) {
                            'confirmed' => 'Pembayaran Berhasil!',
                            'awaiting_payment' => 'Menunggu pembayaran',
                            default => match ($order->payment?->provider_status) {
                                'expire' => 'Pembayaran kedaluwarsa',
                                'deny', 'failure' => 'Pembayaran gagal',
                                default => 'Pesanan dibatalkan',
                            },
                        } }}</strong>
                        @if ($order->payment?->provider === \App\Models\Payment::SIMULATION)
                            <p class="text-[11px]">Transaksi lokal dengan simulasi pembayaran.</p>
                        @endif
                    </div>
                </div>
                <h2 class="mt-3 mb-2 text-xs font-extrabold sm:text-sm">Informasi Pesanan</h2>
                <x-customer.order-info :order="$order" :timezone="$customer['timeZone']" />
                <h2 class="mt-4 mb-2 text-xs font-extrabold sm:text-sm">Daftar Menu</h2>
                <ul class="space-y-2.5">
                    @foreach ($order->items as $line)
                        @php($photo = app(\App\Services\Customer\Catalog::class)->menuPhoto($line->photo))
                        <li class="grid grid-cols-[85px_minmax(0,1fr)_auto] items-start gap-2.5 sm:gap-4">
                            <x-customer.photo :item="$photo" class="h-[75px]" />
                            <div class="min-w-0 pt-1.5">
                                <strong class="block text-sm wrap-break-word">{{ $line->name }}</strong>
                                <span class="block text-xs text-subtle">Rp{{ \App\Services\Customer\Money::format($line->unit_price_cents) }}</span>
                                @foreach ($line->options as $option)
                                    <span class="block text-[10px] text-subtle">{{ $option['quantity'] }}× {{ $option['name'] }}</span>
                                @endforeach
                                @if ($line->note) <span class="block text-[10px] wrap-break-word text-subtle">{{ $line->note }}</span> @endif
                            </div>
                            <span class="pt-2 text-xs font-extrabold">×{{ $line->quantity }}</span>
                        </li>
                    @endforeach
                </ul>
                @if ($order->note)<p class="mt-2 text-[11px] wrap-break-word text-subtle">Catatan pesanan: {{ $order->note }}</p>@endif
                <section class="customer-panel mt-4 space-y-2 p-3 text-xs shadow-warkop" aria-labelledby="receipt-summary">
                    <h2 id="receipt-summary" class="font-heading text-sm">Rincian Pembayaran</h2>
                    <dl class="space-y-1">
                        <div class="flex justify-between gap-2"><dt>Subtotal</dt><dd>Rp{{ \App\Services\Customer\Money::format($order->subtotal_cents) }}</dd></div>
                        <div class="flex justify-between gap-2"><dt>PBJT ({{ $order->tax_bps / 100 }}%)</dt><dd>Rp{{ \App\Services\Customer\Money::format($order->tax_cents) }}</dd></div>
                        <div class="flex justify-between gap-2"><dt>Diskon</dt><dd>Rp{{ \App\Services\Customer\Money::format($order->discount_cents) }}</dd></div>
                        @if ($order->rounding_cents)
                            <div class="flex justify-between gap-2"><dt>Pembulatan QRIS</dt><dd>Rp{{ \App\Services\Customer\Money::format($order->rounding_cents) }}</dd></div>
                        @endif
                        <div class="mt-3 flex items-center justify-between gap-2 rounded bg-table px-2.5 py-3 text-sm font-extrabold"><dt>Total Pembayaran</dt><dd class="text-warkop" data-receipt-total>Rp{{ \App\Services\Customer\Money::format($order->total_cents) }}</dd></div>
                    </dl>
                </section>
                <div class="mt-auto grid gap-2 pt-8">
                    @if ($order->status === 'awaiting_payment')
                        <a href="{{ route('customer.payment', $order->number) }}" class="customer-cta">Lanjutkan Pembayaran</a>
                    @endif
                    <a href="{{ route('customer.home') }}" class="customer-cta customer-cta--outline min-h-10.5 w-full">Kembali ke Beranda</a>
                </div>
            </div>
        @endif
    </main>
</x-customer.layout>
