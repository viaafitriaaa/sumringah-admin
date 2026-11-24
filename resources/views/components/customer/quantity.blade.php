@props(['label', 'max', 'value' => 0])

<div {{ $attributes->class(['flex shrink-0 items-center gap-0.5']) }} data-quantity data-max="{{ $max }}">
    <button type="button" data-step="-1" class="customer-icon-button size-8" aria-label="Kurangi {{ $label }}">
        <x-customer.icon name="minus" class="size-4" />
    </button>
    <output data-count class="min-w-5 text-center text-xs font-bold tabular-nums" aria-label="{{ $label }}">{{ $value }}</output>
    <button type="button" data-step="1" class="customer-icon-button size-8" aria-label="Tambah {{ $label }}">
        <x-customer.icon name="plus" class="size-4" />
    </button>
</div>
