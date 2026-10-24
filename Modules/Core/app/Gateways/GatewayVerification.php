<?php

namespace Modules\Core\Gateways;

final readonly class GatewayVerification
{
    public function __construct(
        public string $status,
        public ?string $reference = null,
        public ?int $amountMinor = null,
        public ?string $currency = null,
        public ?string $merchantReference = null,
    ) {}
}
