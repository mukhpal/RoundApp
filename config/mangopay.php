<?php

return [
    'client_id' => env('MANGOPAY_CLIENT_ID'),
    'secret' => env('MANGOPAY_CLIENT_SECRET'),
    'tmp_folder' => env('MANGOPAY_TMP_FOLDER'),
    'env' => env('MANGOPAY_ENV', 'dev'),
    'seller' => [
        'id' => env('MANGOPAY_SELLER_ID'),
        'wallet_id' => env('MANGOPAY_WALLET_ID'),
    ],
    'environments' => [
        'dev' => [
            'url' => env('MANGOPAY_DEV_URL', 'https://api.sandbox.mangopay.com'),
        ],
        'prod' => [
            'url' => env('MANGOPAY_PROD_URL', 'https://api.mangopay.com'),
        ]
    ]
];
