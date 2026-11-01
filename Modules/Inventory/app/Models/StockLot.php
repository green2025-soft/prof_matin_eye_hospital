<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockLot extends BaseModel
{
    protected $table = 'inventory_stock_lots';

    protected bool $branchScoped = true;

    protected function casts(): array
    {
        return ['branch_id' => 'integer', 'variant_id' => 'integer', 'source_line_id' => 'integer', 'parent_lot_id' => 'integer', 'received_at' => 'date', 'initial_quantity' => 'integer', 'remaining_quantity' => 'integer', 'initial_cost_minor' => 'integer', 'remaining_cost_minor' => 'integer', 'expiry_date' => 'date', 'reversed_at' => 'datetime'];
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function sourceLine(): BelongsTo
    {
        return $this->belongsTo(DocumentLine::class, 'source_line_id');
    }
}
