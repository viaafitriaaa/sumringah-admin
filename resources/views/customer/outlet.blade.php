<x-customer.layout title="Info Outlet" page="outlet" :customer="$customer" :state="$state">
    <x-customer.header title="Info Outlet" :back="route('customer.home')" class="min-h-18.5" />
    <main id="konten">
        <section class="border-b border-rule px-5.5 pt-5 pb-4 sm:px-6" aria-labelledby="outlet-name">
            <div class="flex items-start gap-2.5">
                <x-customer.icon name="location" extension="png" class="mt-0.5 size-5" />
                <div class="min-w-0 flex-1">
                    <h2 id="outlet-name" class="font-heading text-sm">{{ $customer['outlet']['name'] }}</h2>
                    <p class="mt-0.5 text-xs leading-snug">{{ $customer['outlet']['address'] ?? 'Alamat belum ditambahkan.' }}</p>
                </div>
                <button type="button" data-share class="customer-icon-button -mr-2" aria-label="Bagikan info outlet">
                    <x-customer.icon name="share" extension="png" class="size-4" />
                </button>
            </div>
            <div class="mt-3 flex justify-center">
                <button type="button" data-outlet-contact class="inline-flex min-h-9 items-center gap-2 rounded-sm bg-[#dedada] px-4 text-xs hover:bg-tint">
                    <x-customer.icon name="phone" class="size-4" />Hubungi Outlet
                </button>
            </div>
        </section>

        <section class="customer-container pt-3.5" aria-labelledby="outlet-hours">
            <h2 id="outlet-hours" class="mb-1 px-2 font-heading text-base">Jam Operasional</h2>
            <dl class="divide-y divide-rule border-b border-rule">
                @foreach ($customer['outlet']['hours'] as $day => $hours)
                    <div @class(['grid min-h-10.5 grid-cols-[minmax(75px,1fr)_2fr] items-center px-2 text-[11px] sm:text-sm', 'font-extrabold' => now($customer['timeZone'])->isoWeekday() === $loop->iteration, 'text-subtle' => now($customer['timeZone'])->isoWeekday() !== $loop->iteration])>
                        <dt class="uppercase">{{ $day }}</dt><dd>{{ $hours }}</dd>
                    </div>
                @endforeach
            </dl>
        </section>
    </main>

    <dialog data-contact-dialog class="customer-panel fixed inset-0 m-auto w-[min(22rem,calc(100%-2rem))] bg-paper p-5 backdrop:bg-black/40">
        <h2 class="font-heading text-base">Hubungi Outlet</h2>
        @unless ($customer['outlet']['whatsapp'] || $customer['outlet']['email'])
            <p class="mt-2 text-sm text-subtle">Kontak outlet belum ditambahkan.</p>
        @endunless
        <div class="mt-4 flex flex-wrap justify-end gap-2">
            <form method="dialog"><button class="customer-cta customer-cta--outline">Tutup</button></form>
            @if ($customer['outlet']['address'])
                <a href="https://www.google.com/maps/search/?api=1&amp;query={{ urlencode($customer['outlet']['name'].' '.$customer['outlet']['address']) }}" target="_blank" rel="noopener noreferrer" class="customer-cta">Lihat di Peta</a>
            @endif
            @if ($customer['outlet']['whatsapp'])
                <a href="https://wa.me/{{ $customer['outlet']['whatsapp'] }}" target="_blank" rel="noopener noreferrer" class="customer-cta">WhatsApp</a>
            @elseif ($customer['outlet']['email'])
                <a href="mailto:{{ $customer['outlet']['email'] }}" class="customer-cta">Email outlet</a>
            @endif
        </div>
    </dialog>
</x-customer.layout>
