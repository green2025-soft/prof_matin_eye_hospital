<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

abstract class StockItem extends BaseModel
{
    protected const COMMON_EXPOSED = [
        'id', 'variant_id', 'product_unit_id', 'quantity', 'factor', 'base_quantity',
        'cost', 'created_at', 'updated_at',
    ];

    protected array $exposed = self::COMMON_EXPOSED;

    protected function casts(): array
    {
        return [
            'variant_id' => 'integer',
            'product_unit_id' => 'integer',
            'quantity' => 'integer',
            'factor' => 'integer',
            'base_quantity' => 'integer',
            'cost' => 'integer',
        ];
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function productUnit(): BelongsTo
    {
        return $this->belongsTo(ProductUnit::class, 'product_unit_id');
    }

    public function allocations(): MorphMany
    {
        return $this->morphMany(StockAllocation::class, 'stock_item');
    }
}
