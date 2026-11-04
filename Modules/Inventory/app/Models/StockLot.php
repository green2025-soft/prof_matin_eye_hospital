<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StockLot extends BaseModel
{
    protected $table = 'inv_stock_lots';

    protected bool $branchScoped = true;

    protected function casts(): array
    {
        return [
            'branch_id' => 'integer',
            'variant_id' => 'integer',
            'source_item_id' => 'integer',
            'parent_lot_id' => 'integer',
            'received_at' => 'date',

            'initial_quantity' => 'integer',
            'remaining_quantity' => 'integer',
            'initial_cost' => 'integer',
            'remaining_cost' => 'integer',

            'expiry_date' => 'date',
            'reversed_at' => 'datetime',
        ];
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function sourceItem(): MorphTo
    {
        return $this->morphTo();
    }

    public function childLots(): HasMany
    {
        return $this->hasMany(self::class, 'parent_lot_id');
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(StockAllocation::class, 'lot_id');
    }

    public function shortageSettlements(): HasMany
    {
        return $this->hasMany(StockShortageSettlement::class, 'lot_id');
    }
}
