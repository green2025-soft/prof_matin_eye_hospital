<?php

namespace Modules\Pharmacy\Policies;

use Modules\Core\Models\Account;
use Modules\Pharmacy\Models\StockEntry;
use Modules\Pharmacy\Services\PharmacyAccess;

class StockEntryPolicy
{
    public function __construct(private PharmacyAccess $access) {}

    public function view(Account $actor, StockEntry $entry): bool
    {
        return $entry->matchesBranchContext() && $this->access->allows($actor, $entry->crudPermission().'.view');
    }

    public function void(Account $actor, StockEntry $entry): bool
    {
        return (int) $entry->branch_id === (int) request()->attributes->get('branch_id')
            && $entry->voided_at === null
            && $this->access->allows($actor, $entry->crudPermission().'.void');
    }

    public function update(Account $actor, StockEntry $entry): bool
    {
        return $entry::REVISABLE && ! $entry->trashed()
            && (int) $entry->branch_id === (int) request()->attributes->get('branch_id')
            && $entry->voided_at === null
            && $this->access->allows($actor, $entry->crudPermission().'.update');
    }

    public function delete(Account $actor, StockEntry $entry): bool
    {
        return $entry::REVISABLE && ! $entry->trashed()
            && (int) $entry->branch_id === (int) request()->attributes->get('branch_id')
            && $this->access->allows($actor, $entry->crudPermission().'.delete');
    }
}
