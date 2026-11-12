<?php

namespace Modules\Accounting\Policies;

use Modules\Accounting\Models\JournalEntry;
use Modules\Core\Models\Account;

class JournalEntryPolicy
{
    public function viewAny(Account $actor): bool
    {
        return (new JournalEntry)->allowsCrud($actor, 'view');
    }

    public function create(Account $actor): bool
    {
        return (new JournalEntry)->allowsCrud($actor, 'create');
    }

    public function view(Account $actor, JournalEntry $entry): bool
    {
        return (int) $entry->branch_id === (int) request()->attributes->get('branch_id')
            && $entry->allowsCrud($actor, 'view');
    }

    public function update(Account $actor, JournalEntry $entry): bool
    {
        return ! $entry->isAutomatic() && $entry->voided_at === null
            && (int) $entry->branch_id === (int) request()->attributes->get('branch_id')
            && $entry->allowsCrud($actor, 'update');
    }

    public function delete(Account $actor, JournalEntry $entry): bool
    {
        return ! $entry->isAutomatic() && $entry->voided_at === null
            && (int) $entry->branch_id === (int) request()->attributes->get('branch_id')
            && $entry->allowsCrud($actor, 'delete');
    }
}
