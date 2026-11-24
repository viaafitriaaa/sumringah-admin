@props(['summary' => false])

<dl {{ $attributes->class(['customer-panel divide-y divide-rule px-3 text-[11px] shadow-warkop sm:text-sm']) }}>
    @foreach ([
        ['Nomor Pesanan', 'id', 'receipt-id'],
        ['Tanggal & Waktu', 'date', null],
        ['Nomor Meja', 'table', 'receipt-date'],
        ...($summary ? [] : [['Tipe Pesanan', 'type', null]]),
        ['Metode Pembayaran', 'method', 'receipt-table'],
        ...($summary ? [['Total Pembayaran', 'total', 'receipt-payment']] : []),
    ] as [$label, $key, $icon])
        <div class="flex min-h-9.5 items-center justify-between gap-2 py-1.5">
            <dt class="flex items-center gap-2.5">
                @if ($icon)
                    <x-customer.icon :name="$icon" class="size-4.5" />
                @else
                    <span class="size-4.5 shrink-0" aria-hidden="true"></span>
                @endif
                {{ $label }}
            </dt>
            <dd data-order-field="{{ $key }}" @class(['min-w-0 text-right font-bold wrap-break-word', 'text-warkop' => $key === 'total'])></dd>
        </div>
    @endforeach
</dl>
