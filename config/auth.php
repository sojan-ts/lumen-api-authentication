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
        'guest' => [
            'driver' => 'jwt',
            'provider' => 'guests',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => \App\User::class
        ],
        'guests' => [
            'driver' => 'eloquent',
            'model' => \App\Guest::class
        ],
    ]
];
