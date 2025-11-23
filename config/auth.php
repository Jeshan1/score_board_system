<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | Set the default guard to 'sanctum' (or 'api') for token-based auth.
    |
    */

    'defaults' => [
        'guard' => 'sanctum',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    */

    'guards' => [
        'sanctum' => [
            'driver' => 'sanctum',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'sanctum',
            'provider' => 'users',
        ],

        // Optional: keep web guard if you need session-based login
        // 'web' => [
        //     'driver' => 'session',
        //     'provider' => 'users',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Reset Settings
    |--------------------------------------------------------------------------
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

];
