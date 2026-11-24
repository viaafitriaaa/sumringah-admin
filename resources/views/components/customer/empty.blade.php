@props(['title', 'description', 'href', 'label' => 'Lihat menu'])

<section {{ $attributes->class(['px-5 py-12 text-center']) }}>
    <h2 class="font-heading text-lg">{{ $title }}</h2>
    <p class="mx-auto mt-2 max-w-68 text-sm text-subtle">{{ $description }}</p>
    <a href="{{ $href }}" class="customer-cta mt-5">{{ $label }}</a>
</section>
