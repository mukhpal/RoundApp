<?php

return [
    /*
    |--------------------------------------------------------------------------
    |
    |--------------------------------------------------------------------------
    |
    |
    */
    'provider' => 'mangopay',
    'providers' => [
        'mangopay' => [
            'client_id' => env('PAYMENT_PROVIDER_MANGOPAY_CLIENT_ID'),
            'secret' => env('PAYMENT_PROVIDER_MANGOPAY_CLIENT_ID', 'sync'),
        ]
    ]
];
