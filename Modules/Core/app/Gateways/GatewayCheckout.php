<?php

namespace Modules\Core\Gateways;

final readonly class GatewayCheckout
{
    public function __construct(public string $reference, public string $url) {}
}
