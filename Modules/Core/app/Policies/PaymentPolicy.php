<?php

namespace Modules\Core\Policies;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\Account;
use Modules\Core\Models\Admin;
use Modules\Core\Models\Payment;
use Modules\Core\Models\Tenant;

class PaymentPolicy extends PlatformPolicy
{
    protected string $permission = 'payments';

    public function view(Account $actor, Model $record): bool
    {
        if (! $record instanceof Payment) {
            return false;
        }
        if ($actor instanceof Admin) {
            return $this->allowed($actor, 'view');
        }

        return Tenant::current()?->getKey() === $record->tenant_id
            && ($actor->hasOwnerRole() || $actor->can('billing.view'));
    }

    public function update(Account $actor, Model $record): bool
    {
        return false;
    }

    public function delete(Account $actor, Model $record): bool
    {
        return false;
    }
}
