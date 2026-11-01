<?php

namespace Modules\Inventory\Services;

use Modules\Inventory\Models\Document;
use Modules\Inventory\Models\Purchase;
use Modules\Inventory\Models\PurchaseReturn;

class PurchaseReturnService extends ReturnEntryService
{
    protected string $model = PurchaseReturn::class;

    protected string $sourceModel = Purchase::class;

    protected function postStock(Document $document): int
    {
        return $this->stock->returnPurchase($document);
    }
}
