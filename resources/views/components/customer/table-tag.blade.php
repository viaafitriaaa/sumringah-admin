@props(['number'])

<p {{ $attributes->class(['flex min-h-9.25 items-center justify-center rounded-[10px] bg-table px-2 text-center text-xs font-semibold']) }}>
    Nomor Meja: {{ $number }}
</p>
