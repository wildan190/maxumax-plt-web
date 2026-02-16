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
        'is_production' => env('EASYPARCEL_PRODUCTION', env('easyparcel_production', true)),
        'fallback_email' => env('EASYPARCEL_FALLBACK_EMAIL', 'no-reply@yourdomain.com'),
    ],

    'delyva' => [
        'base_url' => env('DELYVA_BASE_URL', 'https://api.delyva.app/v1.0'),
        'company_code' => env('DELYVA_COMPANY_CODE'),
        'company_id' => env('DELYVA_COMPANY_ID'),
        'user_id' => env('DELYVA_USER_ID'),
        'customer_id' => env('DELYVA_CUSTOMER_ID'),
        'api_key' => env('DELYVA_API_KEY'),
        'access_token' => env('DELYVA_ACCESS_TOKEN'),
    ],
    'myparcelasia' => [
        'api_key' => env('MYPA_API_KEY'),
        'api_secret' => env('MYPA_API_SECRET'),
        'is_production' => env('MYPA_PRODUCTION', true),
        'base_url_prod' => 'https://app.myparcelasia.com/apiv2',
        'base_url_dev' => 'https://demo.myparcelasia.com/apiv2',
        'only' => env('MYPA_ONLY', true),
    ],
];
