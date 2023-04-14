<?php

return [
    'defaults' => [
        'guard' => 'users',
        'passwords' => 'users',
    ],

    'guards' => [
        'users' => [
            'driver' => 'jwt',
            'provider' => 'users',
        ],
        'guests' => [
            'driver' => 'jwt',
            'provider' => 'guests',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => \App\Models\User::class
        ],
        'guests' => [
            'driver' => 'eloquent',
            'model' => \App\Models\Guest::class
        ],
    ]
];
