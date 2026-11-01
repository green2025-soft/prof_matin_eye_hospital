<?php

namespace Modules\Inventory\Policies;

use Modules\Core\Models\Account;
use Modules\Inventory\Models\Document;
use Modules\Inventory\Services\InventoryAccess;

class DocumentPolicy
{
    public function __construct(private InventoryAccess $access) {}

    public function view(Account $actor, Document $document): bool
    {
        return $document->matchesBranchContext() && $this->access->allows($actor, Document::typePermissions()[$document->type].'.view');
    }

    public function void(Account $actor, Document $document): bool
    {
        return (int) $document->branch_id === (int) request()->attributes->get('branch_id')
            && $document->voided_at === null
            && $this->access->allows($actor, Document::typePermissions()[$document->type].'.void');
    }

    public function update(Account $actor, Document $document): bool
    {
        return in_array($document->type, ['purchase', 'sale', 'purchase_return', 'sale_return'], true) && ! $document->trashed()
            && (int) $document->branch_id === (int) request()->attributes->get('branch_id')
            && $document->voided_at === null
            && $this->access->allows($actor, Document::typePermissions()[$document->type].'.update');
    }

    public function delete(Account $actor, Document $document): bool
    {
        return in_array($document->type, ['purchase', 'sale', 'purchase_return', 'sale_return'], true) && ! $document->trashed()
            && (int) $document->branch_id === (int) request()->attributes->get('branch_id')
            && $this->access->allows($actor, Document::typePermissions()[$document->type].'.delete');
    }
}
