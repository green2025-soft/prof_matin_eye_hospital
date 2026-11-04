<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OpeningStockItem extends StockItem
{
    protected $table = 'inv_opening_stock_items';

    protected array $exposed = [...parent::COMMON_EXPOSED, 'opening_stock_id', 'unit_price', 'batch_no', 'expiry_date'];

    protected function casts(): array
    {
        return parent::casts() + ['opening_stock_id' => 'integer', 'unit_price' => 'integer', 'expiry_date' => 'date'];
    }

    public function openingStock(): BelongsTo
    {
        return $this->belongsTo(OpeningStock::class);
    }
}
