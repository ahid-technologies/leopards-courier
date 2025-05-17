<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Leopards Courier API Credentials
    |--------------------------------------------------------------------------
    |
    | Here you may specify your Leopards Courier API credentials.
    |
    */

    'api_key' => env('LEOPARDS_API_KEY', ''),
    'api_password' => env('LEOPARDS_API_PASSWORD', ''),

    /*
    |--------------------------------------------------------------------------
    | Leopards Courier API Environment
    |--------------------------------------------------------------------------
    |
    | Here you may specify your Leopards Courier API environment.
    | Available options: 'staging', 'production'
    |
    */

    'environment' => env('LEOPARDS_API_ENVIRONMENT', 'staging'),
];
