<?php

return [

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel'              => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'stripe' => [
        'key'    => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'currency' => env('STRIPE_CURRENCY', 'myr'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],

    'toyyibpay' => [
        'secret_key'    => env('TOYYIBPAY_SECRET_KEY'),
        'category_code' => env('TOYYIBPAY_CATEGORY_CODE'),
        'sandbox'       => env('TOYYIBPAY_SANDBOX', true),
    ],

];