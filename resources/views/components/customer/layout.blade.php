@props(['title', 'page', 'demo'])

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#810003">
    <meta name="robots" content="noindex">
    <title>{{ $title }} | Warkop Sumringah</title>
    <link rel="icon" href="{{ asset('customer/logo.png') }}" type="image/png">
    @vite(['resources/css/customer.css', 'resources/js/customer/app.js'])
</head>
<body class="bg-paper text-ink antialiased sm:bg-[#e9e1e1]">
    <div class="customer-shell sm:border-x sm:border-[#ded3d3]" data-customer-page="{{ $page }}">
        <a class="sr-only focus:not-sr-only focus:absolute focus:z-40 focus:bg-white focus:p-2" href="#konten">Lompat ke konten</a>
        {{ $slot }}
        <p class="customer-toast" role="status" aria-live="polite" data-toast></p>
    </div>
    <script type="application/json" id="customer-data">{!! \Illuminate\Support\Js::encode([
        'catalog' => $demo['items'],
        'options' => $demo['options'],
        'table' => $demo['table'],
        'taxRate' => $demo['taxRate'],
        'timeZone' => $demo['timeZone'],
        'assets' => asset('customer'),
        'urls' => [
            'catalog' => route('customer.catalog'),
            'item' => route('customer.item', ['item' => '__item__']),
            'cart' => route('customer.cart'),
            'checkout' => route('customer.checkout'),
            'success' => route('customer.success'),
        ],
    ]) !!}</script>
</body>
</html>
