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

    'rajaongkir' => [
        'base_url' => env('RAJAONGKIR_BASE_URL', 'https://rajaongkir.komerce.id/api/v1'),
        'api_key' => env('RAJAONGKIR_API_KEY'),
        'origin_destination_id' => env('RAJAONGKIR_ORIGIN_ID'),
        'couriers' => env('RAJAONGKIR_COURIERS', 'jne:sicepat:jnt:ninja:tiki:pos:anteraja'),
        'price_sort' => env('RAJAONGKIR_PRICE_SORT', 'lowest'),
    ],

    'grabexpress' => [
        'base_url' => env('GRABEXPRESS_BASE_URL'),
        'api_key' => env('GRABEXPRESS_API_KEY'),
        'quote_path' => env('GRABEXPRESS_QUOTE_PATH', '/quotes'),
        'timeout_seconds' => (int) env('GRABEXPRESS_TIMEOUT_SECONDS', 15),
    ],

    'gosend' => [
        'base_url' => env('GOSEND_BASE_URL'),
        'api_key' => env('GOSEND_API_KEY'),
        'quote_path' => env('GOSEND_QUOTE_PATH', '/quotes'),
        'timeout_seconds' => (int) env('GOSEND_TIMEOUT_SECONDS', 15),
    ],

    'delivery' => [
        'api_url' => env('DELIVERY_API_URL'),
        'api_key' => env('DELIVERY_API_KEY'),
    ],
];
