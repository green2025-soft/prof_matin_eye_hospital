<?php

namespace Modules\Core\Policies;

use Modules\Core\Models\Account;
use Modules\Core\Models\Admin;
use Modules\Core\Models\Invoice;
use Modules\Core\Models\Tenant;

class BillingPolicy
{
    public function view(Account $actor): bool
    {
        return $actor->matchesApplication()
            && (request()->attributes->get('navigation_permissions', [])['billing.view'] ?? false);
    }

    public function pay(Account $actor, Invoice $invoice): bool
    {
        return ! $actor instanceof Admin && $this->view($actor)
            && Tenant::current()?->getKey() === $invoice->tenant_id && $invoice->isPayable()
            && (request()->attributes->get('navigation_permissions', [])['billing.pay'] ?? false);
    }
}
