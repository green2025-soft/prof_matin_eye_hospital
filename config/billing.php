<?php

use Modules\Core\Gateways\BkashGateway;
use Modules\Core\Gateways\EpsGateway;
use Modules\Core\Gateways\NagadGateway;
use Modules\Core\Gateways\SslcommerzGateway;

return [
    'currency' => env('BILLING_CURRENCY', 'BDT'),
    'currencies' => ['BDT', 'USD', 'EUR', 'GBP', 'INR'],
    'default_plan' => env('BILLING_DEFAULT_PLAN', 'starter'),
    'invoice_prefix' => env('BILLING_INVOICE_PREFIX', 'INV'),
    'callback_base_url' => env('PAYMENT_CALLBACK_BASE_URL'),
    'drivers' => [
        'sslcommerz' => SslcommerzGateway::class,
        'bkash' => BkashGateway::class,
        'nagad' => NagadGateway::class,
        'eps' => EpsGateway::class,
    ],
];
