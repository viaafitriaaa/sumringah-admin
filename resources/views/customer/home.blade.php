<x-customer.layout title="Halaman Utama" page="home" :demo="$demo">
    <header class="flex min-h-[97px] items-center justify-center bg-warkop px-3 text-white sm:min-h-29">
        <h1 class="flex items-center justify-center gap-2.5 font-wordmark text-[clamp(22px,7.6vw,36px)] leading-tight sm:gap-4">
            <span>Warkop</span>
            <img src="{{ asset('customer/logo.png') }}" alt="" width="59" height="59" class="size-14.75 shrink-0 rounded-full sm:size-17">
            <span>Sumringah</span>
        </h1>
    </header>

    <main id="konten" class="pb-4">
        <div class="grid gap-2 px-1.5 pt-2 sm:px-6 sm:pt-4">
            <a href="{{ route('customer.outlet') }}" class="flex min-h-14.25 items-center justify-between gap-2 rounded-[10px] bg-tint px-4 hover:bg-table">
                <span>
                    <strong class="block text-xs sm:text-sm">{{ $demo['outlet']['name'] }}</strong>
                    <span class="block text-[11px] text-subtle sm:text-xs">Buka hari ini, {{ array_values($demo['outlet']['hours'])[now($demo['timeZone'])->isoWeekday() - 1] }}</span>
                </span>
                <x-customer.icon name="chevron" class="size-4 -rotate-90" />
            </a>
            <x-customer.table-tag :number="$demo['table']" />
        </div>

        <div class="mt-4 divide-y divide-[#e8dddd]">
            @foreach ($demo['highlights'] as $highlight)
                <section class="px-2.75 py-1.75 sm:px-6 sm:py-4" aria-labelledby="highlight-{{ $loop->index }}">
                    <div class="mb-1.5 flex min-h-5 items-center justify-between gap-2">
                        <h2 id="highlight-{{ $loop->index }}" class="font-heading text-xs font-bold sm:text-base">{{ $highlight['title'] }}</h2>
                        @unless ($loop->first)
                            <a href="{{ route('customer.catalog', $highlight['category']) }}" class="inline-flex min-h-5 items-center gap-1 text-[10px] text-subtle hover:text-warkop sm:text-xs">Lihat semua <x-customer.icon name="chevron" class="size-3 -rotate-90" /></a>
                        @endunless
                    </div>
                    <div class="grid grid-cols-3 gap-3.25 px-1.75 sm:gap-4 sm:px-0">
                        @foreach ($highlight['items'] as $itemId)
                            @php($product = $demo['items'][$itemId])
                            <article class="flex min-w-0 flex-col rounded-lg bg-white p-2 shadow-warkop">
                                <a href="{{ route('customer.item', ['item' => $itemId, 'dari' => 'beranda']) }}" class="block aspect-[85/75] overflow-hidden rounded-[4px] sm:aspect-[4/3]" tabindex="-1" aria-hidden="true">
                                    <x-customer.photo :item="$product" :lazy="!$loop->parent->first" />
                                </a>
                                <h3 class="mt-1 min-h-5 text-[9px] leading-tight font-semibold wrap-break-word sm:min-h-8 sm:text-xs">{{ $product['nama_menu'] }}</h3>
                                <p class="customer-price mt-auto mb-0.5 text-[11px] font-semibold sm:text-sm">{{ number_format($product['harga'], 0, ',', '.') }}</p>
                                <a class="customer-cta min-h-6 px-1 text-[10px] sm:min-h-9" href="{{ route('customer.item', ['item' => $itemId, 'dari' => 'beranda']) }}" aria-label="Tambah {{ $product['nama_menu'] }}">Tambah</a>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>

        <section class="customer-container py-3.5" aria-labelledby="categories-title">
            <h2 id="categories-title" class="mb-2.5 font-heading text-xs font-bold sm:text-base">Kategori</h2>
            <div class="grid gap-3.5 sm:grid-cols-2">
                @foreach ($demo['categories'] as $slug => $category)
                    <a href="{{ route('customer.catalog', $slug) }}" class="block overflow-hidden rounded-lg shadow-warkop motion-safe:transition-transform motion-safe:hover:-translate-y-0.5">
                        <img src="{{ asset('customer/'.$category['image']) }}" alt="{{ $category['name'] }}" width="1020" height="465" class="block w-full" loading="lazy">
                    </a>
                @endforeach
            </div>
        </section>
    </main>
    <x-customer.cart-link />
</x-customer.layout>
