<?php

namespace Modules\Core\Policies;

use Modules\Core\Models\Account;
use Modules\Core\Models\Admin;
use Modules\Core\Models\Invoice;
use Modules\Core\Models\Tenant;
use Modules\Core\Services\AccessManager;

class BillingPolicy
{
    public function __construct(private AccessManager $access) {}

    public function view(Account $actor): bool
    {
        return $this->access->actorMatchesContext($actor)
            && ($this->access->isOwner($actor) || $actor->can('billing.view'));
    }

    public function pay(Account $actor, Invoice $invoice): bool
    {
        return ! $actor instanceof Admin && $this->view($actor)
            && Tenant::current()?->getKey() === $invoice->tenant_id && $invoice->isPayable()
            && ($this->access->isOwner($actor) || $actor->can('billing.pay'));
    }
}
