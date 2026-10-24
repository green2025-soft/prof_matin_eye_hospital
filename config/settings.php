<?php

$chargeRules = ['required', 'string', 'regex:/\A\d{1,10}(?:\.\d{1,2})?\z/'];
$gatewayDefaults = ['enabled' => false, 'sandbox' => true, 'extra_charge' => '0.00'];
$gatewayRules = [
    'enabled' => ['required', 'boolean'],
    'sandbox' => ['required', 'boolean'],
    'extra_charge' => $chargeRules,
];

return [
    'tenant_groups' => ['company', 'branding', 'general'],
    'payment_groups' => ['manual', 'sslcommerz', 'bkash', 'nagad', 'eps'],
    'groups' => [
        'company' => ['label' => 'Company', 'description' => 'Organization contact details and tax identification.', 'icon' => 'building'],
        'branding' => ['label' => 'Branding', 'description' => 'The name and tagline shown in your workspace.', 'icon' => 'palette'],
        'billing' => ['label' => 'Billing', 'description' => 'Invoice currency, numbering prefix, and payment instructions.', 'icon' => 'file-invoice-dollar'],
        'general' => ['label' => 'General', 'description' => 'Workspace timezone and language preferences.', 'icon' => 'sliders'],
        'manual' => ['label' => 'Manual payment', 'description' => 'Manual payment availability, customer instructions, and a fixed extra charge.', 'icon' => 'money-bill-transfer'],
        'sslcommerz' => ['label' => 'SSLCommerz', 'description' => 'Store credentials and payment environment.', 'icon' => 'credit-card'],
        'bkash' => ['label' => 'bKash', 'description' => 'Application credentials for bKash payments.', 'icon' => 'mobile-screen-button'],
        'nagad' => ['label' => 'Nagad', 'description' => 'Merchant identity and signing keys for Nagad.', 'icon' => 'mobile-screen-button'],
        'eps' => ['label' => 'EPS', 'description' => 'Merchant and API credentials for EPS payments.', 'icon' => 'credit-card'],
    ],
    'credentials' => [
        'sslcommerz' => [
            'store_id' => ['label' => 'Store ID', 'max' => 255],
            'store_password' => ['label' => 'Store password', 'min' => 5, 'max' => 4096],
        ],
        'bkash' => [
            'app_key' => ['label' => 'App key', 'max' => 255],
            'app_secret' => ['label' => 'App secret', 'max' => 4096],
            'username' => ['label' => 'Username', 'max' => 255],
            'password' => ['label' => 'Password', 'min' => 5, 'max' => 4096],
        ],
        'nagad' => [
            'merchant_id' => ['label' => 'Merchant ID', 'max' => 255],
            'merchant_number' => ['label' => 'Merchant number', 'max' => 50],
            'public_key' => ['label' => 'Public key', 'max' => 8192, 'multiline' => true],
            'private_key' => ['label' => 'Private key', 'max' => 16384, 'multiline' => true],
        ],
        'eps' => [
            'merchant_id' => ['label' => 'Merchant ID', 'max' => 255],
            'store_id' => ['label' => 'Store ID', 'max' => 255],
            'username' => ['label' => 'Username', 'max' => 255],
            'password' => ['label' => 'Password', 'min' => 5, 'max' => 4096],
            'hash_key' => ['label' => 'Hash key', 'max' => 4096],
        ],
    ],
    'defaults' => [
        'company' => ['name' => env('APP_NAME', 'Laravel Modular Boilerplate'), 'email' => env('MAIL_FROM_ADDRESS', 'hello@example.com'), 'phone' => '', 'address' => '', 'tax_id' => ''],
        'branding' => ['app_name' => env('APP_NAME', 'Laravel Modular Boilerplate'), 'tagline' => ''],
        'billing' => ['currency' => 'BDT', 'invoice_prefix' => 'INV', 'payment_instructions' => ''],
        'general' => ['timezone' => env('APP_TIMEZONE', 'Asia/Dhaka'), 'locale' => env('APP_LOCALE', 'en')],
        'manual' => ['enabled' => true, 'instructions' => '', 'extra_charge' => '0.00'],
        'sslcommerz' => $gatewayDefaults,
        'bkash' => $gatewayDefaults,
        'nagad' => $gatewayDefaults,
        'eps' => $gatewayDefaults,
    ],
    'rules' => [
        'company' => [
            'name' => ['required', 'string', 'min:5', 'max:255'],
            'email' => ['nullable', 'email:rfc', 'max:255'],
            'phone' => ['nullable', 'string', 'digits_between:5,30'],
            'address' => ['nullable', 'string', 'min:5', 'max:2000'],
            'tax_id' => ['nullable', 'string', 'max:100'],
        ],
        'branding' => [
            'app_name' => ['required', 'string', 'min:5', 'max:120'],
            'tagline' => ['nullable', 'string', 'min:5', 'max:255'],
        ],
        'billing' => [
            'currency' => ['required', 'string', 'regex:/\A[A-Z]{3}\z/i'],
            'invoice_prefix' => ['required', 'string', 'max:20', 'regex:/\A[A-Z0-9_-]+\z/i'],
            'payment_instructions' => ['nullable', 'string', 'min:5', 'max:4000'],
        ],
        'general' => [
            'timezone' => ['required', 'timezone'],
            'locale' => ['required', 'string', 'max:12', 'regex:/\A[a-z]{2}(?:[_-][A-Z]{2})?\z/'],
        ],
        'manual' => [
            'enabled' => ['required', 'boolean'],
            'instructions' => ['nullable', 'string', 'min:5', 'max:4000'],
            'extra_charge' => $chargeRules,
        ],
        'sslcommerz' => $gatewayRules,
        'bkash' => $gatewayRules,
        'nagad' => $gatewayRules,
        'eps' => $gatewayRules,
    ],
];
