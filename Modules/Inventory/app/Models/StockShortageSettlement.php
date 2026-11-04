<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockShortageSettlement extends BaseModel
{
    protected $table = 'inv_stock_shortage_settlements';

    protected function casts(): array
    {
        return [
            'allocation_id' => 'integer',
            'lot_id' => 'integer',
            'quantity' => 'integer',
            'snapshot_cost' => 'integer',
            'receipt_cost' => 'integer',
            'reversed_at' => 'datetime',
        ];
    }

    public function allocation(): BelongsTo
    {
        return $this->belongsTo(StockAllocation::class, 'allocation_id');
    }

    public function lot(): BelongsTo
    {
        return $this->belongsTo(StockLot::class, 'lot_id');
    }
}
