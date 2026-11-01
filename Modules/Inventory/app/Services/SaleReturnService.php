<?php

namespace Modules\Inventory\Services;

use Modules\Inventory\Models\Document;
use Modules\Inventory\Models\Sale;
use Modules\Inventory\Models\SaleReturn;

class SaleReturnService extends ReturnEntryService
{
    protected string $model = SaleReturn::class;

    protected string $sourceModel = Sale::class;

    protected function postStock(Document $document): int
    {
        return $this->stock->returnSale($document);
    }
}
