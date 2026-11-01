<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockBalance extends BaseModel
{
    protected $table = 'inventory_stock_balances';

    protected bool $branchScoped = true;

    protected function casts(): array
    {
        return ['branch_id' => 'integer', 'variant_id' => 'integer', 'quantity' => 'integer', 'cost_minor' => 'integer'];
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
}
