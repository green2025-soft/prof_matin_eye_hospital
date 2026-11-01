<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends BaseModel
{
    protected $table = 'inventory_stock_movements';

    protected bool $branchScoped = true;

    protected function casts(): array
    {
        return ['branch_id' => 'integer', 'variant_id' => 'integer', 'document_id' => 'integer', 'document_line_id' => 'integer', 'lot_id' => 'integer', 'quantity' => 'integer', 'cost_minor' => 'integer', 'movement_date' => 'date', 'is_reversal' => 'boolean'];
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class)->withTrashed();
    }
}
