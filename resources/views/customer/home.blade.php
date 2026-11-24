<x-customer.layout title="Halaman Utama" page="home" :customer="$customer" :state="$state">
    <header class="flex min-h-[97px] items-center justify-center bg-warkop px-3 text-white sm:min-h-29">
        <h1 class="flex items-center justify-center gap-2.5 font-wordmark text-[clamp(22px,7.6vw,36px)] leading-tight sm:gap-4">
            <span>Warkop</span>
            <img src="{{ $customer['outlet']['logo'] }}" alt="" width="59" height="59" class="size-14.75 shrink-0 rounded-full object-cover sm:size-17">
            <span>Sumringah</span>
        </h1>
    </header>

    <main id="konten" class="pb-4">
        <div class="grid gap-2 px-1.5 pt-2 sm:px-6 sm:pt-4">
            <a href="{{ route('customer.outlet') }}" class="flex min-h-14.25 items-center justify-between gap-2 rounded-[10px] bg-tint px-4 hover:bg-table">
                <span>
                    <strong class="block text-xs sm:text-sm">{{ $customer['outlet']['name'] }}</strong>
                    <span class="block text-[11px] text-subtle sm:text-xs">Hari ini: {{ array_values($customer['outlet']['hours'])[now($customer['timeZone'])->isoWeekday() - 1] }}</span>
                </span>
                <x-customer.icon name="chevron" class="size-4 -rotate-90" />
            </a>
            <details id="pilih-meja" class="rounded-[10px] bg-table px-2.5 text-xs" @if (!$state['table'] || $suggestedTable || $errors->any()) open @endif>
                <summary class="flex min-h-9 items-center justify-between gap-2 font-semibold">
                    <span>{{ $state['table'] ? 'Nomor Meja: '.$state['table'] : 'Masukkan nomor meja' }}</span>
                    @if ($state['table']) <span class="text-[10px] underline">Ubah meja</span> @endif
                </summary>
                <form action="{{ route('customer.table') }}" method="post" class="flex items-end gap-2 pb-2.5">
                    @csrf
                    <label class="min-w-0 flex-1">
                        Kode pada meja
                        <input type="text" name="code" maxlength="255" class="customer-input mt-1" autocomplete="off" autocapitalize="characters" required
                            value="{{ old('code', $suggestedTable ?? $state['table']) }}" placeholder="Contoh: SAL1-03">
                    </label>
                    <button class="customer-cta" type="submit">Pilih</button>
                </form>
                @if ($errors->any()) <p class="pb-2 font-bold text-warkop" role="alert">{{ $errors->first() }}</p> @endif
            </details>
            @if (session('customer_notice')) <p role="status" class="px-2 text-xs text-subtle">{{ session('customer_notice') }}</p> @endif
            @if ($state['lastOrder'])
                <a href="{{ route('customer.order', $state['lastOrder']) }}" class="px-2 py-1 text-xs font-bold text-warkop underline underline-offset-2">Lihat pesanan terakhir</a>
            @endif
        </div>

        @if ($customer['latest'])
            <section class="customer-container mt-4 border-b border-tint pb-3" aria-labelledby="new-menu-title">
                <div class="mb-2 flex items-center justify-between gap-2">
                    <h2 id="new-menu-title" class="font-heading text-xs sm:text-base">Menu Baru</h2>
                    <a href="{{ route('customer.catalog') }}" class="text-[10px] font-semibold text-subtle hover:text-warkop sm:text-xs">Lihat semua</a>
                </div>
                <div class="grid grid-cols-3 gap-3">
                    @foreach ($customer['latest'] as $product)
                        <article class="flex min-w-0 flex-col rounded-lg bg-white p-2 shadow-warkop">
                            <div class="aspect-[85/75] overflow-hidden rounded sm:aspect-[4/3]"><x-customer.photo :item="$product" /></div>
                            <h3 class="mt-1 min-h-6 text-[10px] leading-tight font-semibold wrap-break-word sm:text-xs">{{ $product['nama_menu'] }}</h3>
                            <p class="mt-auto mb-1 text-[11px] font-semibold sm:text-sm">{{ \App\Services\Customer\Money::format($product['price']) }}</p>
                            <a class="customer-cta min-h-7 px-1 text-[10px]" href="{{ route('customer.item', ['item' => $product['id'], 'dari' => 'beranda']) }}" aria-label="Tambah {{ $product['nama_menu'] }}">Tambah</a>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        <section class="customer-container border-b border-tint py-3" aria-labelledby="promo-title">
            <h2 id="promo-title" class="mb-2 font-heading text-xs sm:text-base">PROMO</h2>
            <div class="grid gap-2 sm:grid-cols-2">
                @forelse ($customer['promos'] as $promo)
                    <article class="flex gap-2 rounded-lg bg-white p-2.5 shadow-warkop">
                        @if ($promo['image']) <img src="{{ $promo['image'] }}" alt="" class="size-16 shrink-0 rounded object-cover" loading="lazy"> @endif
                        <div class="min-w-0 text-[11px] wrap-break-word">
                            <h3 class="font-bold text-warkop">{{ $promo['name'] }}</h3>
                            @if ($promo['description']) <p>{{ $promo['description'] }}</p> @endif
                            <p class="mt-0.5 text-subtle">Berlaku sampai {{ \Carbon\CarbonImmutable::parse($promo['until'])->locale('id')->isoFormat('D MMM YYYY') }}</p>
                            @if ($promo['terms'])
                                <details class="mt-1"><summary class="font-semibold">Ketentuan</summary><p>{{ $promo['terms'] }}</p></details>
                            @endif
                        </div>
                    </article>
                @empty
                    <p class="text-xs text-subtle">Belum ada promo aktif.</p>
                @endforelse
            </div>
        </section>

        <section class="customer-container py-3.5" aria-labelledby="categories-title">
            <h2 id="categories-title" class="mb-2.5 font-heading text-xs font-bold sm:text-base">Kategori</h2>
            <div class="grid gap-3.5 sm:grid-cols-2">
                @forelse ($customer['categories'] as $slug => $category)
                    <a href="{{ route('customer.catalog', $slug) }}" class="relative block overflow-hidden rounded-lg shadow-warkop motion-safe:transition-transform motion-safe:hover:-translate-y-0.5">
                        <img src="{{ asset('customer/'.$category['image']) }}" alt="" width="1020" height="465" class="block w-full" loading="lazy">
                        <span class="absolute right-2 bottom-2 rounded bg-paper px-2 py-1 text-xs font-bold text-warkop">{{ $category['name'] }}</span>
                    </a>
                @empty
                    <p class="text-xs text-subtle">Katalog belum tersedia.</p>
                @endforelse
            </div>
        </section>
    </main>
    <x-customer.cart-link />
</x-customer.layout>
