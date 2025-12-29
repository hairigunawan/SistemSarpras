<?php

return [

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
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

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
        'host' => 'https://accounts.google.com',
        'base_url' => 'https://oauth2.googleapis.com',
        'timeout' => 30,
    ],

    'whatsapp' => [
        'token' => env('WHATSAPP_ACCESS_TOKEN'),
        'phone_number_id' => env('WHATSAPP_BUSINESS_PHONE_ID'),
        'api_url' => env('WHATSAPP_API_URL', 'https://graph.facebook.com/v19.0/'),
    ],

    'fonnte' => [
        'token' => env('FONNTE_TOKEN'),
        'api_url' => env('FONNTE_API_URL', 'https://api.fonnte.com/send'),
        'country' => env('FONNTE_COUNTRY', '62'),
        'timeout' => env('FONNTE_TIMEOUT', 30),
        'retries' => env('FONNTE_RETRIES', 3),
        'auth_prefix' => env('FONNTE_AUTH_PREFIX', ''),
    ],


    'supabase' => [
        'url' => env('SUPABASE_URL'),
        'key' => env('SUPABASE_KEY'),
        'bucket' => env('SUPABASE_BUCKET'),
    ],

];
