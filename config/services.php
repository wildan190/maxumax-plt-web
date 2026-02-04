<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'stripe' => [
        'model' => env('STRIPE_MODEL', \App\Models\User::class),
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'easyparcel' => [
        'api_key' => env('EASYPARCEL_API_KEY'),
        'is_production' => env('EASYPARCEL_PRODUCTION', true),
    ],
    'delyva' => [
        'base_url' => env('DELYVA_BASE_URL', 'https://api.delyva.app/v1.0'),
        'company_code' => env('DELYVA_COMPANY_CODE'),
        'company_id' => env('DELYVA_COMPANY_ID'),
        'user_id' => env('DELYVA_USER_ID'),
        'customer_id' => env('DELYVA_CUSTOMER_ID'),
        'api_key' => env('DELYVA_API_KEY'),
    ],
];
