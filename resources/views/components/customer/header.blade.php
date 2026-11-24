@props(['title', 'back', 'close' => false, 'elevated' => true, 'compact' => false])

<header {{ $attributes->class(['grid grid-cols-[40px_1fr_40px] items-center gap-1 px-2.5 sm:px-5', 'min-h-11 sm:min-h-13.5' => $compact, 'min-h-13.5' => !$compact, 'shadow-warkop' => $elevated]) }}>
    <a href="{{ $back }}" class="customer-icon-button" aria-label="{{ $close ? 'Kembali ke beranda' : 'Kembali' }}">
        @if ($close)
            <span class="text-2xl leading-none" aria-hidden="true">×</span>
        @else
            <x-customer.icon name="back" class="size-6" />
        @endif
    </a>
    <h1 class="min-w-0 text-center font-heading text-base font-bold sm:text-lg">{{ $title }}</h1>
    {{ $slot }}
</header>
