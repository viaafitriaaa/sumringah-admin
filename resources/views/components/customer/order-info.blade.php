@props(['order', 'timezone', 'summary' => false])

<dl {{ $attributes->class(['customer-panel divide-y divide-rule px-3 text-[11px] shadow-warkop sm:text-sm']) }}>
    @foreach ([
        ['Nomor Pesanan', $order->number, 'receipt-id'],
        ['Tanggal & Waktu', $order->created_at->copy()->timezone($timezone)->locale('id')->isoFormat('D MMM YYYY, HH:mm'), null],
        ['Nomor Meja', $order->table_code, 'receipt-date'],
        ...($summary ? [] : [['Tipe Pesanan', $order->type === 'takeaway' ? 'Bawa pulang' : 'Makan di tempat', null]]),
        ['Metode Pembayaran', $order->payment?->provider === \App\Models\Payment::SIMULATION ? 'QRIS Demo' : 'QRIS', 'receipt-table'],
        ...($summary ? [['Total Pembayaran', 'Rp'.\App\Services\Customer\Money::format($order->total_cents), 'receipt-payment']] : []),
    ] as [$label, $value, $icon])
        <div class="flex min-h-9.5 items-center justify-between gap-2 py-1.5">
            <dt class="flex items-center gap-2.5">
                @if ($icon)
                    <x-customer.icon :name="$icon" class="size-4.5" />
                @else
                    <span class="size-4.5 shrink-0" aria-hidden="true"></span>
                @endif
                {{ $label }}
            </dt>
            <dd class="min-w-0 text-right font-bold wrap-break-word">{{ $value }}</dd>
        </div>
    @endforeach
</dl>
