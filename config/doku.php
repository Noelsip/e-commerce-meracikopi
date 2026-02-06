<?php

return [
    'client_id' => env('DOKU_CLIENT_ID', 'BRN-0213-1769756224228'),
    'secret_key' => env('DOKU_SECRET_KEY', ''),
    'api_key' => env('DOKU_API_KEY', ''),
    'public_key' => env('DOKU_PUBLIC_KEY', ''),    'merchant_private_key' => env('MERCHANT_PRIVATE_KEY'),
    'merchant_public_key' => env('MERCHANT_PUBLIC_KEY'),    'is_production' => env('DOKU_IS_PRODUCTION', false),
    'base_url' => env('DOKU_IS_PRODUCTION', false) 
        ? 'https://api.doku.com' 
        : 'https://api-sandbox.doku.com',
    
    // Fallback mode: jika true, gunakan mock data saat DOKU gagal (untuk development)
    // Set ke false di production untuk memastikan hanya pakai DOKU asli
    'fallback_enabled' => env('DOKU_FALLBACK_ENABLED', true),
    
    'snap' => [
        'token_url' => env('DOKU_SNAP_TOKEN_URL'),
        'return_url' => env('DOKU_SNAP_RETURN_URL'),
        'cancel_url' => env('DOKU_SNAP_CANCEL_URL'),
        'error_url' => env('DOKU_SNAP_ERROR_URL'),
    ],
];