<x-customer.layout title="Pembayaran" page="payment" :customer="$customer" :state="$state">
    <x-customer.header title="Menunggu Pembayaran" :back="route('customer.order', $order->number)" />
    <main id="konten" class="customer-container py-5">
        <p class="mb-3 text-sm">Pesanan sudah disimpan. Pembayaran masih menunggu konfirmasi.</p>
        <x-customer.order-info :order="$order" :timezone="$customer['timeZone']" summary />

        @if ($simulation)
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
