<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockAllocation extends BaseModel
{
    protected $table = 'inventory_stock_allocations';

    protected function casts(): array
    {
        return ['document_line_id' => 'integer', 'lot_id' => 'integer', 'source_allocation_id' => 'integer', 'quantity' => 'integer', 'cost_minor' => 'integer', 'reversed_at' => 'datetime'];
    }

    public function lot(): BelongsTo
    {
        return $this->belongsTo(StockLot::class, 'lot_id');
    }

    public function line(): BelongsTo
    {
        return $this->belongsTo(DocumentLine::class, 'document_line_id');
    }
}
