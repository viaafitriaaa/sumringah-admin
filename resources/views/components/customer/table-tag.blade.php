@props(['number'])

<p {{ $attributes->class(['flex min-h-9.25 items-center justify-center rounded-[10px] bg-table px-2 text-center text-xs font-semibold']) }}>
    @if ($number)
        Nomor Meja: {{ $number }}
    @else
        <a href="{{ route('customer.home') }}#pilih-meja" class="underline underline-offset-2">Pilih nomor meja</a>
    @endif
</p>
