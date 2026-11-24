<x-customer.layout title="Pembayaran" page="checkout" :demo="$demo">
    <x-customer.header title="Pembayaran" :back="route('customer.cart')" />
    <main id="konten">
        <x-customer.empty title="Belum ada pesanan" description="Isi keranjang sebelum melanjutkan ke pembayaran." :href="route('customer.catalog')" data-checkout-empty />
        <form id="customer-checkout" method="post" data-checkout-form hidden>
            <div class="px-5.5 pt-3.5 pb-4 sm:px-6">
                <div class="customer-panel flex min-h-7.5 items-center justify-between gap-2 border-warkop bg-tint px-2.5 text-[11px]">
                    <span>Tipe Pemesanan</span><strong data-checkout-type></strong>
                </div>
                <h2 class="mt-3 mb-2 text-sm font-extrabold">Informasi Pemesan</h2>
                <div class="grid gap-2">
                    @foreach ([
                        ['name', 'Nama Lengkap*', 'Nama Lengkap', 'text', 'name', 'person', 80],
                        ['phone', 'Nomor Ponsel', 'Nomor Ponsel', 'tel', 'tel', 'phone', 24],
                        ['email', 'Kirim struk ke email', 'Email', 'email', 'email', 'mail', 100],
                    ] as [$key, $label, $placeholder, $type, $autocomplete, $icon, $limit])
                        <label class="block text-[11px]">
                            {{ $label }}
                            <span class="relative mt-1 block">
                                <x-customer.icon :name="$icon" :extension="$icon === 'person' ? 'png' : 'svg'" class="pointer-events-none absolute top-1/2 left-2.5 size-4 -translate-y-1/2 opacity-65" />
                                <input name="{{ $key }}" type="{{ $type }}" autocomplete="{{ $autocomplete }}" maxlength="{{ $limit }}" placeholder="{{ $placeholder }}"
                                    @required($key === 'name') class="customer-input min-h-8 pl-8">
                            </span>
                        </label>
                    @endforeach

                    <label class="block text-[11px]">
                        Nomor Meja*
                        <span class="relative mt-1 block">
                            <x-customer.icon name="table" class="pointer-events-none absolute top-1/2 left-2.5 size-4 -translate-y-1/2 opacity-65" />
                            <input name="table" value="{{ $demo['table'] }}" readonly class="customer-input min-h-8 pl-8 text-subtle">
                        </span>
                    </label>
                </div>
            </div>

            <fieldset class="border-t border-rule px-5.5 pt-3.5 pb-4 sm:px-6">
                <legend class="sr-only">Metode Pembayaran</legend>
                <h2 class="mb-2.5 text-sm font-extrabold" aria-hidden="true">Metode Pembayaran</h2>
                <label class="customer-panel flex min-h-9 cursor-pointer items-center gap-2 px-2.5 text-xs hover:bg-tint">
                    <strong>QRIS</strong>
                    <span class="text-[10px] font-bold text-warkop">Demo</span>
                    <input type="radio" class="ml-auto size-4 accent-warkop" name="payment" value="qris-demo" required>
                </label>
                <p class="mt-2 text-[11px] text-subtle">Pembayaran simulasi. Tidak ada uang yang ditagihkan.</p>
                <div data-checkout-consent hidden class="mt-3">
                    <label class="flex items-start gap-2 text-xs">
                        <input type="checkbox" name="consent" disabled required class="mt-0.5 size-4 shrink-0 accent-warkop">
                        Saya setuju melanjutkan simulasi checkout.
                    </label>
                    <p class="mt-1 pl-6 text-[10px] text-subtle">Gunakan data contoh. Kontak tidak dikirim atau disimpan.</p>
                </div>
            </fieldset>
            <p data-checkout-error role="alert" hidden class="px-5.5 pb-4 text-xs font-semibold text-warkop"></p>
        </form>
    </main>
    <x-customer.total-bar data-checkout-footer hidden>
        <button form="customer-checkout" type="submit" data-checkout-submit class="customer-cta min-w-39 sm:min-w-52">Bayar</button>
    </x-customer.total-bar>

    <dialog data-payment-dialog class="customer-panel fixed inset-0 m-auto w-[min(22rem,calc(100%-2rem))] bg-paper p-5 text-ink backdrop:bg-black/40">
        <h2 class="font-heading text-base">Simulasi QRIS</h2>
        <p class="mt-2 text-sm text-subtle">Konfirmasi pembayaran demo sebesar <strong data-payment-total></strong>. Tidak ada pembayaran nyata.</p>
        <div class="mt-4 flex justify-end gap-2">
            <button type="button" class="customer-cta customer-cta--outline" data-cancel-payment>Batal</button>
            <button type="button" class="customer-cta" data-confirm-payment>Konfirmasi Demo</button>
        </div>
    </dialog>
</x-customer.layout>
