<x-customer.layout title="Pembayaran" page="payment" :customer="$customer" :state="$state">
    <x-customer.header title="Menunggu Pembayaran" :back="route('customer.order', $order->number)" />
    <main id="konten" class="customer-container py-5">
        <p class="mb-3 text-sm">Pesanan sudah disimpan. Pembayaran masih menunggu konfirmasi.</p>
        <x-customer.order-info :order="$order" :timezone="$customer['timeZone']" summary />

        @if ($sandbox)
            <section class="customer-panel mt-4 grid gap-3 bg-tint p-4" aria-labelledby="midtrans-title"
                data-midtrans-payment data-status-url="{{ route('customer.payment.status', $order->number) }}"
                data-qr-url="{{ $order->payment->qr_url }}" data-expires-at="{{ $order->payment->expires_at?->toIso8601String() }}">
                <div class="flex items-center justify-between gap-2">
                    <h2 id="midtrans-title" class="font-heading text-base text-warkop">Pembayaran QRIS</h2>
                    <span class="rounded bg-paper px-2 py-1 text-[10px] font-bold text-warkop">Midtrans Sandbox</span>
                </div>
                <p class="text-xs">Gunakan tautan QR hanya di simulator resmi Midtrans untuk pembayaran uji.</p>
                @if (session('payment_error'))
                    <p role="alert" class="text-xs font-bold text-warkop">{{ session('payment_error') }}</p>
                @endif
                <div data-payment-code @if (!$order->payment->qr_url || $order->payment->expires_at?->isPast()) hidden @endif
                    class="grid justify-items-center gap-2 rounded-md bg-paper p-3">
                    <img data-payment-image @if ($order->payment->qr_url) src="{{ $order->payment->qr_url }}" @endif
                        alt="Kode QRIS Midtrans Sandbox" referrerpolicy="no-referrer" class="aspect-square w-full max-w-52 bg-white object-contain p-2">
                    <a data-payment-qr-link href="{{ $order->payment->qr_url }}" target="_blank" rel="noreferrer" class="text-xs font-bold text-warkop underline">Buka gambar QR</a>
                    <button data-payment-copy type="button" class="rounded px-3 py-2 text-xs font-bold text-warkop hover:bg-tint">Salin tautan QR</button>
                    <a href="https://simulator.sandbox.midtrans.com/v2/qris/index" target="_blank" rel="noreferrer" class="text-xs font-bold text-warkop underline">Buka simulator Midtrans</a>
                </div>
                <p data-payment-countdown hidden class="text-center text-xs font-bold tabular-nums text-warkop" aria-live="off"></p>
                <p data-payment-message role="status" class="text-xs text-subtle">{{ $order->payment->qr_url ? 'Menunggu pembayaran…' : 'Kode QR belum tersedia. Tekan Coba Lagi.' }}</p>
                <button type="button" data-payment-check class="customer-cta w-full">Cek Status Pembayaran</button>
                <button type="button" data-payment-retry @if ($order->payment->qr_url) hidden @endif class="customer-cta customer-cta--outline w-full">Coba Lagi</button>
                <form method="post" action="{{ route('customer.payment.cancel', $order->number) }}">
                    @csrf
                    <button type="submit" class="customer-cta customer-cta--outline w-full">Batalkan Pesanan</button>
                </form>
            </section>
        @elseif ($simulation)
            <section class="customer-panel mt-4 grid gap-2 bg-tint p-4" aria-labelledby="simulation-title">
                <h2 id="simulation-title" class="font-heading text-base text-warkop">Simulasi QRIS</h2>
                <p class="text-xs">Khusus development. Tidak ada uang yang ditagihkan. Stok sudah dialokasikan untuk pesanan ini dan akan dikembalikan jika pesanan dibatalkan.</p>
                <form method="post" action="{{ route('customer.payment.simulate', $order->number) }}">
                    @csrf
                    <button type="submit" class="customer-cta mt-1 w-full">Konfirmasi Simulasi</button>
                </form>
                <form method="post" action="{{ route('customer.payment.cancel', $order->number) }}">
                    @csrf
                    <button type="submit" class="customer-cta customer-cta--outline w-full">Batalkan Pesanan</button>
                </form>
            </section>
        @endif
    </main>
</x-customer.layout>
