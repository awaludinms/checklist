<?php
return [
    'default' => [
        'guard' => 'api',
        'password' => 'users',
    ],
    'guards' => [
        'api' => [
            'driver' => 'jwt',
            'provider' => 'users'
        ]
    ]
];
