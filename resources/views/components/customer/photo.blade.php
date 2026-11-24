@props(['item', 'lazy' => true])

@if ($item['fallback'])
    @if ($item['categoryIllustration'])
        <span {{ $attributes->class(['relative block size-full overflow-hidden bg-paper']) }}>
            <img src="{{ $item['image'] }}" alt="" @if ($lazy) loading="lazy" @endif
                class="absolute inset-y-0 right-0 h-full w-[200%] max-w-none object-cover object-right">
        </span>
    @else
        <span {{ $attributes->class(['relative isolate grid size-full place-items-center overflow-hidden bg-tint text-center']) }}>
            <img src="{{ $item['image'] }}" alt="" @if ($lazy) loading="lazy" @endif
                class="absolute inset-0 -z-10 size-full object-contain p-2 opacity-25">
            <span class="rounded-sm bg-paper px-1.5 py-0.5 text-[9px] leading-tight font-bold text-warkop sm:text-[11px]">Foto belum tersedia</span>
        </span>
    @endif
@else
    <img src="{{ $item['image'] }}" alt="" @if ($lazy) loading="lazy" @endif
        {{ $attributes->class(['customer-product-image']) }}>
@endif
