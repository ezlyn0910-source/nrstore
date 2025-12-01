<?php

return [
    'merchant_code' => env('IPAY88_MERCHANT_CODE', ''),
    'merchant_key'  => env('IPAY88_MERCHANT_KEY', ''),
    'currency'      => 'MYR',

    // Sandbox / production URL – confirm with your doc
    'payment_url'   => env('IPAY88_PAYMENT_URL', 'https://www.mobile88.com/ePayment/entry.asp'),

    // Map logical names to PaymentId from iPay88 spec
    'payment_ids'   => [
        'credit_card' => env('IPAY88_PAYMENTID_CARD', ''),

        // Online banking / FPX banks – fill with real PaymentId numbers from iPay88 appendix
        'maybank'          => env('IPAY88_PAYMENTID_MAYBANK', ''),
        'cimb'             => env('IPAY88_PAYMENTID_CIMB', ''),
        'public_bank'      => env('IPAY88_PAYMENTID_PUBLIC', ''),
        'rhb'              => env('IPAY88_PAYMENTID_RHB', ''),
        'hong_leong'       => env('IPAY88_PAYMENTID_HLBB', ''),
        'bank_islam'       => env('IPAY88_PAYMENTID_BANKISLAM', ''),
        'ambank'           => env('IPAY88_PAYMENTID_AMBANK', ''),
        'bank_rakyat'      => env('IPAY88_PAYMENTID_BANKRAKYAT', ''),
        'hsbc'             => env('IPAY88_PAYMENTID_HSBC', ''),
        'ocbc'             => env('IPAY88_PAYMENTID_OCBC', ''),
        'uob'              => env('IPAY88_PAYMENTID_UOB', ''),
        'standard_chartered' => env('IPAY88_PAYMENTID_SC', ''),
    ],
];