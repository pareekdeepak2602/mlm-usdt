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

'transaction_verifier' => [
    'key' => env('TRANSACTION_VERIFIER_KEY'),
    'secret' => env('TRANSACTION_VERIFIER_SECRET'),
    'app_token' => env('TRANSACTION_VERIFIER_APP_TOKEN'),
    'url' => env('TRANSACTION_VERIFIER_URL'),
    'network' => env('TRANSACTION_VERIFIER_NETWORK', 'bsc_testnet'),
    'company_wallet' => env('COMPANY_WALLET', '0x5CE2C945eeD9FBA974363fF028D86ed641b7b185'),
],
];
