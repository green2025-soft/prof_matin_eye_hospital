<?php

namespace Modules\Core\Contracts;

use Modules\Core\Gateways\GatewayCheckout;
use Modules\Core\Gateways\GatewayVerification;
use Modules\Core\Models\CheckoutAttempt;

interface PaymentGateway
{
    public function start(CheckoutAttempt $attempt, array $settings, string $callbackUrl): GatewayCheckout;

    public function verify(CheckoutAttempt $attempt, array $settings, array $callback): GatewayVerification;
}
