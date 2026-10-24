<?php

namespace Modules\Core\Jobs;

use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Core\Services\SubscriptionService;
use RuntimeException;
use Spatie\Multitenancy\Jobs\NotTenantAware;

class GenerateDueInvoices implements NotTenantAware
{
    use Dispatchable;

    public function __construct(private bool $failOnError = true) {}

    public function handle(SubscriptionService $subscriptions): array
    {
        $result = $subscriptions->syncDue();

        if ($this->failOnError && $result['failed'] !== []) {
            throw new RuntimeException('Invoice generation failed for subscriptions: '.implode(', ', $result['failed']).'. Review the application log and retry.');
        }

        return $result;
    }
}
