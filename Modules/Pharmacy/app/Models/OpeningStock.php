<?php

namespace Modules\Pharmacy\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class OpeningStock extends StockEntry
{
    public const TYPE = 'opening';

    protected $table = 'phr_opening_stocks';

    protected string $crudPermission = 'pharmacy-opening-stock';

    public function lines(): HasMany
    {
        return $this->hasMany(OpeningStockItem::class);
    }
}
