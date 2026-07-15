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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],

    'recaptcha' => [
        // Master switch. Set RECAPTCHA_ENABLED=false to instantly disable captcha
        // on every form (the widget won't render and RecaptchaRule becomes a no-op).
        'enabled'    => env('RECAPTCHA_ENABLED', true),

        // Public site key (used by the browser widget).
        'site_key'   => env('RECAPTCHA_SITE_KEY'),

        // Secret key (server-side verification only — never exposed to the browser).
        'secret_key' => env('RECAPTCHA_SECRET_KEY'),

        // Override points (rarely needed).
        'script_src' => env('RECAPTCHA_SCRIPT_SRC', 'https://www.google.com/recaptcha/api.js'),
        'verify_url' => env('RECAPTCHA_VERIFY_URL', 'https://www.google.com/recaptcha/api/siteverify'),
    ],

];
