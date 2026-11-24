<?php

return [
    'timezone' => 'Asia/Jakarta',
    // Temporary, pending the outlet's PBJT decision. 1 basis point is 0.01%.
    'tax_bps' => (int) env('CUSTOMER_PBJT_BPS', 0),
    // The application also checks the runtime environment before allowing simulation.
    'simulate_payment' => env('CUSTOMER_SIMULATE_PAYMENT', true),
];
