<x-customer.layout title="Daftar Kategori" page="catalog" :customer="$customer" :state="$state">
    <x-customer.table-tag :number="$state['table']" class="mx-1.5 mt-3 sm:mx-6" />
    <x-customer.header title="Daftar Kategori" :back="route('customer.home')" :elevated="false" compact>
        <button class="customer-icon-button" type="button" data-search-toggle aria-label="Cari menu" aria-controls="menu-search" aria-expanded="false">
            <x-customer.icon name="search" class="size-6" />
        </button>
    </x-customer.header>

    <div id="menu-search" class="customer-container pb-2" hidden>
        <label for="catalog-search" class="sr-only">Cari menu di kategori ini</label>
        <input id="catalog-search" class="customer-input" type="search" placeholder="Cari di kategori ini…" data-search-input>
    </div>

    <nav class="flex max-w-full gap-1 overflow-x-auto overscroll-x-contain px-0.5 whitespace-nowrap [scrollbar-width:none] sm:px-5" aria-label="Kategori menu">
        @foreach ($customer['categories'] as $slug => $entry)
            <a href="{{ route('customer.catalog', $slug) }}"
                @if ($slug === $category) aria-current="page" @endif
                @class(['flex min-h-6 shrink-0 items-center border-b px-2.5 font-heading text-xs font-bold hover:text-warkop sm:min-h-10 sm:text-sm', 'border-warkop' => $slug === $category, 'border-transparent' => $slug !== $category])>
                {{ $entry['name'] }}
            </a>
        @endforeach
    </nav>

    <main id="konten" class="px-2.5 sm:px-6">
        <ul class="divide-y divide-rule" data-menu-list>
            @foreach ($customer['items'] as $product)
                @continue($product['category'] !== $category)
                <li class="grid min-h-[108px] grid-cols-[85px_minmax(0,1fr)_auto] items-center gap-3 py-3.5 sm:grid-cols-[100px_minmax(0,1fr)_auto] sm:gap-4" data-menu-row data-menu-name="{{ $product['nama_menu'] }}">
                    <a href="{{ route('customer.item', ['item' => $product['id'], 'kategori' => $category]) }}" class="block h-[75px] overflow-hidden sm:h-22" tabindex="-1" aria-hidden="true">
                        <x-customer.photo :item="$product" />
                    </a>
                    <div class="self-start pt-2.5">
                        <h2 class="text-[11px] leading-snug font-extrabold wrap-break-word sm:text-sm">{{ $product['nama_menu'] }}</h2>
                        <p class="customer-price mt-1.5 text-[11px] sm:text-sm">{{ \App\Services\Customer\Money::format($product['price']) }}</p>
                    </div>
                    @if ($product['stok'] > 0)
                        <a href="{{ route('customer.item', ['item' => $product['id'], 'kategori' => $category]) }}" class="mr-1.5 inline-flex min-h-9 items-center rounded sm:mr-0" aria-label="Tambah {{ $product['nama_menu'] }}">
                            <span class="customer-cta min-h-5.5 min-w-[70px] text-[10px] sm:min-h-9 sm:text-xs">Tambah</span>
                        </a>
                    @else
                        <span class="text-center text-[10px] font-bold text-warkop">Stok habis</span>
                    @endif
                </li>
            @endforeach
        </ul>
        <p data-no-results @if ($category !== null) hidden @endif class="py-8 text-center text-sm text-subtle" role="status">Menu tidak ditemukan di kategori ini.</p>
    </main>
    <x-customer.cart-link />
</x-customer.layout>
