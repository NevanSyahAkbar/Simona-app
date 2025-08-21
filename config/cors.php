<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://192.168.1.249:8000'], // sementara dibuka semua
    'allowed_headers' => ['*'],
    'supports_credentials' => false,
];

