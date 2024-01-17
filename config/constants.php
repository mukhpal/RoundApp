<?php
return [
    'pool' => [
        'age' => [
            '18-22' => 'Da 18 a 22 anni',
            '23-30' => 'Da 23 a 30 anni',
            '31-39' => 'Da 31 a 39 anni',
            '40-99' => 'Da 40 a 99 anni'
        ],
        'gender' => [
            'none' =>['text' => 'Non specificato', 'value' => 0],
            'male' =>['text' => 'Maschile', 'value' => 1],
            'female' =>['text' => 'Femminile', 'value' => 2],
        ],
        'paymentType' => [
            'one' => 'Una visualizzazione',
            'all' => 'Tutte le visualizzazioni',
            'one_a_day' => 'Una visualizzazione al giorno'
        ],
        'userType' => [
            'advertiser' => 'Inserzionista',
            'consumer' => 'Consumatore',
        ],
        'customerType' => [
            'individual' => 'Privato',
            'business' => [
                '' => 'Business',
                'agency' => 'Media Agency',
                'brand' => 'Brand',
            ],
        ]
    ],

];
