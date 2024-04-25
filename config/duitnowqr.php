<?php

// config for ZarulIzham/DuitNowQR
return [
    'url' => env('DUITNOW_QR_URL', 'https://amgatewayuat.ambg.com.my'),
    'client_id' => env('DUITNOW_QR_CLIENT_ID'),
    'client_secret' => env('DUITNOW_QR_CLIENT_SECRET'),
    'token_expiry' => env('DUITNOW_QR_TOKEN_EXPIRY', 3600),
    'channel_token' => env('DUITNOW_QR_CHANNEL_TOKEN'),
    'prefix_id' => env('DUITNOW_QR_PREFIX_ID'),
    'colour_type' => env('DUITNOW_QR_COLOUR_TYPE', 1),
    'qr_id' => env('DUITNOW_QR_QR_ID'),
    'api_key' => env('DUITNOW_QR_API_KEY'),
    'api_secret' => env('DUITNOW_QR_API_SECRET'),
    'version' => env('DUITNOW_QR_VERSION', 1),
];
