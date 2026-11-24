@props(['label' => 'Total Pembayaran'])

<footer {{ $attributes->class(['customer-bar']) }}>
    <div class="min-w-0 flex-1">
        <p class="text-[11px] sm:text-xs">{{ $label }}</p>
        <strong class="customer-price text-sm sm:text-base" data-total>Rp0</strong>
    </div>
    {{ $slot }}
</footer>
