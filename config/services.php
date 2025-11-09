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
    'token' => env('EAASuZBf54mZBMBPZB3M6HKc1bgS2KQeVCMTCJzOX0jH8qmhtJufa78UwRHJlW77NCZCaXrkEdBzUrhDXZCjSUQPAvxtKGZBCZBlWOCqyVhbOgtQkNudbpfcNjcvSz7Ar35UtKqqDQ5h5bNHI5VjXGOojik0ZBW5jZAcYxV4HkXsBVpzZBfNtcdXapLGxAGcLL64POrqZCxGP9T0QL7apb5RYKkSc80LfEeKPgSvhAhWS7CIZCY3e62XUY2ZCt3Fc2JcQZA3BHayRuxMhZAS50RXc2TW7ZAt4eu4H'),
    'phone_id' => env('819120471293431'),
    'api_url' => env('https://graph.facebook.com/v16.0/')
],


];
