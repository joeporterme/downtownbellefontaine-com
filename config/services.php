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

    'google' => [
        // Single key still works out of the box. To lock things down, set a
        // browser key (HTTP-referrer restricted, Maps JS + Places) and a server
        // key (IP restricted, Geocoding / Street View / Places) separately; each
        // falls back to GOOGLE_API_KEY when unset.
        'maps_api_key' => env('GOOGLE_API_KEY'),
        'maps_browser_key' => env('GOOGLE_MAPS_BROWSER_KEY', env('GOOGLE_API_KEY')),
        'maps_server_key' => env('GOOGLE_MAPS_SERVER_KEY', env('GOOGLE_API_KEY')),
    ],

    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
    ],

    'contact' => [
        // Set CONTACT_FORM_RECIPIENT in the environment. If unset, submissions
        // are still saved to the admin inbox (ContactMessage) — only the email
        // notification is skipped. Kept out of committed code to avoid shipping
        // a personal address in version control.
        'recipient' => env('CONTACT_FORM_RECIPIENT'),
    ],

];
