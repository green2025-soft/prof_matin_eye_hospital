<?php

namespace Modules\Pharmacy\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdjustmentItem extends StockItem
{
    protected $table = 'phr_adjustment_items';

    protected array $exposed = [
        ...parent::COMMON_EXPOSED, 'adjustment_id', 'unit_price', 'batch_no', 'expiry_date',
        'direction', 'stock_lot_id',
    ];

    protected function casts(): array
    {
        return parent::casts() + [
            'adjustment_id' => 'integer', 'unit_price' => 'integer', 'expiry_date' => 'date',
            'stock_lot_id' => 'integer',
        ];
    }

    public function adjustment(): BelongsTo
    {
        return $this->belongsTo(Adjustment::class);
    }

    public function stockLot(): BelongsTo
    {
        return $this->belongsTo(StockLot::class);
    }
}
