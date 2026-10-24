<?php

namespace Modules\Core\Policies;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\Account;
use Modules\Core\Models\Admin;
use Modules\Core\Models\Invoice;
use Modules\Core\Models\Tenant;

class InvoicePolicy extends PlatformPolicy
{
    protected string $permission = 'invoices';

    public function view(Account $actor, Model $record): bool
    {
        if (! $record instanceof Invoice) {
            return false;
        }
        if ($actor instanceof Admin) {
            return $this->allowed($actor, 'view');
        }

        return $record->issued_at !== null && Tenant::current()?->getKey() === $record->tenant_id
            && ($actor->hasOwnerRole() || $actor->can('billing.view'));
    }

    public function update(Account $actor, Model $record): bool
    {
        return $record instanceof Invoice && $record->isDraft() && $record->subscription_id === null && $this->allowed($actor, 'update');
    }

    public function issue(Account $actor, Invoice $invoice): bool
    {
        return $invoice->issued_at === null && $invoice->status !== Invoice::STATUS_VOID && $this->allowed($actor, 'issue');
    }

    public function void(Account $actor, Invoice $invoice): bool
    {
        return in_array($invoice->status, [Invoice::STATUS_DRAFT, Invoice::STATUS_ISSUED], true)
            && $invoice->paid_minor === 0 && $this->allowed($actor, 'void');
    }

    public function delete(Account $actor, Model $record): bool
    {
        return false;
    }
}
