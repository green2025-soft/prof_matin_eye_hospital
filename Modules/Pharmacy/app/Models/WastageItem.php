<?php

namespace Modules\Pharmacy\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WastageItem extends StockItem
{
    protected $table = 'phr_wastage_items';

    protected array $exposed = [...parent::COMMON_EXPOSED, 'wastage_id', 'stock_lot_id'];

    protected function casts(): array
    {
        return parent::casts() + ['wastage_id' => 'integer', 'stock_lot_id' => 'integer'];
    }

    public function wastage(): BelongsTo
    {
        return $this->belongsTo(Wastage::class);
    }

    public function stockLot(): BelongsTo
    {
        return $this->belongsTo(StockLot::class);
    }
}
