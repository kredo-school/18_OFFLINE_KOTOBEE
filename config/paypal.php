<?php
/**
 * PayPal Setting & API Credentials
 * Created by Raza Mehdi <srmk@outlook.com>.
 */

return [
    'mode' => env('PAYPAL_MODE', 'sandbox'),

    'plans' => [
        'basic'    => 'P-5NS58686D00228848NE5HGNQ',
        'standard' => 'P-9YY274435N954903MNE5HGOA',
        'premium'  => 'P-2FA66219A6091091ENE5HGOQ',
    ],

    'base_url' => env('PAYPAL_BASE_URL', 'https://api-m.sandbox.paypal.com'),

    'sandbox' => [
        'client_id' => env('PAYPAL_CLIENT_ID', ''),
        'client_secret' => env('PAYPAL_SECRET', ''),
        'app_id' => '',
    ],

    'payment_action' => 'Sale',
    'currency'       => env('PAYPAL_CURRENCY', 'USD'),
    'notify_url'     => '',  
];
