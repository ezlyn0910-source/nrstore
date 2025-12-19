<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * These routes are called by external payment gateways
     * (Stripe, Toyyibpay) and MUST NOT require CSRF tokens.
     *
     * @var array<int, string>
     */
    protected $except = [
        'payment/stripe/webhook',
        'payment/toyyibpay/callback',
        'payment/toyyibpay/return/*',
    ];
}
