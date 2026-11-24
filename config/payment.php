<?php

return [
    'driver' => env('PAYMENT_DRIVER', 'simulation'),
    'midtrans' => [
        'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
        'server_key' => env('MIDTRANS_SERVER_KEY'),
        'client_key' => env('MIDTRANS_CLIENT_KEY'),
        'signature_secret' => env('MIDTRANS_SIGNATURE_SECRET_KEY'),
    ],
];
