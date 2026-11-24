@props(['title', 'page', 'customer', 'state', 'product' => null])

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#810003">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex">
    <title>{{ $title }} | Warkop Sumringah</title>
    <link rel="icon" href="{{ asset('customer/favicon.png') }}" type="image/png" sizes="96x96">
    @vite(['resources/css/customer.css', 'resources/js/customer/app.js'])
</head>
<body class="bg-paper text-ink antialiased sm:bg-[#e9e1e1]">
    <div class="customer-shell sm:border-x sm:border-[#ded3d3]" data-customer-page="{{ $page }}">
        <a class="sr-only focus:not-sr-only focus:absolute focus:z-40 focus:bg-white focus:p-2" href="#konten">Lompat ke konten</a>
        {{ $slot }}
        <p class="customer-toast" role="status" aria-live="polite" data-toast></p>
    </div>
    <script type="application/json" id="customer-data">{!! \Illuminate\Support\Js::encode([
        'state' => $state,
        'product' => $product,
        'urls' => [
            'catalog' => route('customer.catalog'),
            'item' => route('customer.item', ['item' => '__item__']),
            'cart' => route('customer.cart'),
            'cartState' => route('customer.cart.state'),
            'cartUpdate' => route('customer.cart.update'),
            'checkout' => route('customer.checkout.store'),
        ],
    ]) !!}</script>
</body>
</html>
