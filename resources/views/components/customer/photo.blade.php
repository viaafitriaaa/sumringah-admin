@props(['item', 'lazy' => true])

<img src="{{ asset('customer/'.$item['foto']) }}" alt=""
    @if ($lazy) loading="lazy" @endif
    {{ $attributes->class(['customer-product-image', 'customer-product-image--banner' => str_starts_with($item['foto'], 'cat-')]) }}>
