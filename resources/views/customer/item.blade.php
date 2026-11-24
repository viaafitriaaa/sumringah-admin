<x-customer.layout :title="$item['nama_menu']" page="item" :demo="$demo">
    <main id="konten" data-item="{{ $item['id'] }}">
        <div class="relative h-[217px] overflow-hidden sm:h-73">
            <x-customer.photo :item="$item" :lazy="false" />
            <a href="{{ $back }}" aria-label="Kembali" class="customer-icon-button absolute top-3 left-3 bg-paper shadow-warkop">
                <x-customer.icon name="back" class="size-6" />
            </a>
        </div>

        <div class="space-y-1.5 border-b border-rule px-4.5 py-3 sm:px-6">
            <h1 class="text-sm leading-tight font-bold sm:text-lg">{{ $item['nama_menu'] }}</h1>
            <strong class="customer-price text-sm">{{ number_format($item['harga'], 0, ',', '.') }}</strong>
            <p class="text-[11px] text-subtle sm:text-sm">{{ $item['deskripsi'] }}</p>
        </div>

        @if ($item['hasOptions'])
            <section class="space-y-2 border-b border-rule px-4.5 py-3 sm:px-6" aria-labelledby="takeaway-title">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <h2 id="takeaway-title" class="text-xs font-extrabold sm:text-sm">Takeaway</h2>
                        <p class="text-[10px] text-subtle">Opsional · maks. 1</p>
                    </div>
                    <input id="lunch-box" data-lunch-box class="size-5 shrink-0 accent-warkop" type="checkbox">
                </div>
                <label for="lunch-box" class="block cursor-pointer text-xs">Lunch Box <strong>(+ 1.000)</strong></label>
            </section>

            <section class="border-b border-rule px-4.5 py-3 sm:px-6" aria-labelledby="options-title">
                <h2 id="options-title" class="text-xs font-extrabold sm:text-sm">Recommended Add On</h2>
                <p class="text-[10px] text-subtle">Opsional</p>
                <div class="mt-1.5 grid gap-0.5">
                    @foreach ($demo['options'] as $key => $option)
                        @continue($key === 'lunch-box')
                        <div class="flex min-h-6.5 items-center justify-between gap-1.5 text-[11px] sm:text-xs">
                            <span>{{ $option['name'] }} <strong>(+ {{ number_format($option['price'], 0, ',', '.') }})</strong></span>
                            @if ($option['max'])
                                <x-customer.quantity :label="$option['name']" :max="$option['max']" data-option="{{ $key }}" class="[&_button]:size-7" />
                            @else
                                <strong class="shrink-0 text-warkop">Stok habis</strong>
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <div class="px-4.5 pt-3 pb-6 sm:px-6">
            <label for="item-note" class="block text-xs font-extrabold sm:text-sm">Catatan untuk menu</label>
            <p class="text-[10px] text-subtle">Opsional</p>
            <textarea id="item-note" data-item-note rows="2" maxlength="160" class="customer-input mt-2 min-h-15 rounded-lg p-2.5" placeholder="Contoh: banyakin cabe"></textarea>
        </div>
    </main>

    <footer class="customer-bar block">
        <div class="mb-1.5 flex items-center justify-between gap-2 px-1 text-xs">
            <span>Jumlah Pesanan</span>
            <x-customer.quantity label="jumlah pesanan" :max="$item['stok']" :value="1" data-main-quantity data-min="1" />
        </div>
        <button type="button" class="customer-cta w-full" data-add-item @disabled(!$item['stok'])>
            <span data-item-action>{{ $item['stok'] ? 'Tambah Pesanan' : 'Stok Habis' }}</span>
            @if ($item['stok'])
                <span aria-hidden="true">·</span><span class="customer-price" data-item-total>{{ number_format($item['harga'], 0, ',', '.') }}</span>
            @endif
        </button>
    </footer>
</x-customer.layout>
