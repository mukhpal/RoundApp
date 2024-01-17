<?php
return [
    "providers" => [
        "mangopay" => [
            "client_id" => env('CASH_MANGOPAY_CLIENT_ID'),
            "secret" => env('CASH_MANGOPAY_CLIENT_SECRET'),
            "tmp_folder" => env('CASH_MANGOPAY_TMP_FOLDER', 'storage/tmp/mangopay'),
            "base_url" => env('CASH_MANGOPAY_BASE_URL', 'https://api.sandbox.mangopay.com'),
        ]
    ]
];
