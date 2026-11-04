<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class OpeningStock extends StockEntry
{
    public const TYPE = 'opening';

    protected $table = 'inv_opening_stocks';

    protected string $crudPermission = 'inventory-opening-stock';

    public function lines(): HasMany
    {
        return $this->hasMany(OpeningStockItem::class);
    }
}
