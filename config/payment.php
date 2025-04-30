<?php

return [
    'vnpay' => [
        'vnp_TmnCode' => env('VNP_TMN_CODE'),
        'vnp_HashSecret' => env('VNP_HASH_SECRET'),
        'vnp_Url' => env('VNP_URL'),
        'vnp_ReturnUrl' => env('VNP_RETURN_URL'),
    ],

    'momo' => [
        // 
    ],

    'frontend' => [
        'payment_success_url' => env('FRONTEND_PAYMENT_SUCCESS_URL', env('APP_URL').'/order-history'),
        'payment_failed_url' => env('FRONTEND_PAYMENT_FAILED_URL', env('APP_URL').'/order-history'),
    ],

];
