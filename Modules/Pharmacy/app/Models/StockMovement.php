<?php

namespace Modules\Pharmacy\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StockMovement extends BaseModel
{
    protected $table = 'phr_stock_movements';

    protected bool $branchScoped = true;

    protected function casts(): array
    {
        return [
            'branch_id' => 'integer',
            'variant_id' => 'integer',
            'stock_entry_id' => 'integer',
            'stock_item_id' => 'integer',
            'lot_id' => 'integer',

            'quantity' => 'integer',
            'cost' => 'integer',
            'movement_date' => 'date',
            'is_reversal' => 'boolean',
        ];
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function stockEntry(): MorphTo
    {
        return $this->morphTo()->withTrashed();
    }

    public function stockItem(): MorphTo
    {
        return $this->morphTo();
    }
}
