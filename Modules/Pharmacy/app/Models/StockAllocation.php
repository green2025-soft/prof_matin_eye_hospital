<?php

namespace Modules\Pharmacy\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StockAllocation extends BaseModel
{
    protected $table = 'phr_stock_allocations';

    protected function casts(): array
    {
        return [
            'stock_item_id' => 'integer',
            'lot_id' => 'integer',
            'source_allocation_id' => 'integer',
            'quantity' => 'integer',
            'cost' => 'integer',
            'reversed_at' => 'datetime',
        ];
    }

    public function lot(): BelongsTo
    {
        return $this->belongsTo(StockLot::class, 'lot_id');
    }

    public function stockItem(): MorphTo
    {
        return $this->morphTo();
    }

    public function returns(): HasMany
    {
        return $this->hasMany(self::class, 'source_allocation_id');
    }

    public function shortageSettlements(): HasMany
    {
        return $this->hasMany(StockShortageSettlement::class, 'allocation_id');
    }
}
