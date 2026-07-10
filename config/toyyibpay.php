<?php

return [
    /*
    |--------------------------------------------------------------------------
    | ToyyibPay Configuration
    |--------------------------------------------------------------------------
    | Secret key dan category code boleh didapati dari dashboard ToyyibPay.
    | Gunakan URL sandbox untuk pembangunan dan URL produksi untuk keluaran.
    */

    'secret_key'    => env('TOYYIBPAY_SECRET_KEY', ''),
    'category_code' => env('TOYYIBPAY_CATEGORY_CODE', ''),
    'url'           => env('TOYYIBPAY_URL', 'https://toyyibpay.com/'),

    // Endpoint API
    'create_bill_endpoint' => 'index.php/api/createBill',
];
