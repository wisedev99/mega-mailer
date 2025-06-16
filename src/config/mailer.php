<?php

return [
    'host' => env('MAIL_HOST', 'localhost'),
    'port' => env('MAIL_PORT', 25),
    'username' => env('MAIL_USERNAME', ''),
    'password' => env('MAIL_PASSWORD', ''),
    'from_address' => env('MAIL_FROM_ADDRESS', 'no-reply@example.com'),
    'from_name' => env('MAIL_FROM_NAME', 'Mega Mailer'),
    'smtp_auth' => env('MAIL_SMTP_AUTH', false),
    'encryption' => env('MAIL_ENCRYPTION', false),
    'auto_tls' => env('MAIL_AUTO_TLS', false),
    'charset' => env('MAIL_CHARSET', 'UTF-8'),
    'smtp_options' => [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true,
        ],
    ],
];
