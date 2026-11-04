<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class SaleItem extends StockItem
{
    protected $table = 'inv_sale_items';

    protected array $exposed = [
        ...parent::COMMON_EXPOSED, 'sale_id', 'returned_base_quantity', 'superseded_at', 'unit_price',
        'subtotal', 'discount', 'invoice_discount', 'vat', 'total', 'vat_mode',
    ];

    protected function casts(): array
    {
        return parent::casts() + [
            'sale_id' => 'integer', 'returned_base_quantity' => 'integer', 'superseded_at' => 'datetime',
            'unit_price' => 'integer', 'subtotal' => 'integer', 'discount' => 'integer',
            'invoice_discount' => 'integer', 'vat' => 'integer', 'total' => 'integer',
        ];
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class)->withTrashed();
    }

    public function returns(): HasMany
    {
        return $this->hasMany(SaleReturnItem::class);
    }

    public function shortageSettlements(): HasManyThrough
    {
        return $this->hasManyThrough(
            StockShortageSettlement::class,
            StockAllocation::class,
            'stock_item_id',
            'allocation_id',
            'id',
            'id'
        )->where('inv_stock_allocations.stock_item_type', $this->getMorphClass())
            ->whereNull('inv_stock_allocations.source_allocation_id')
            ->whereNull('inv_stock_allocations.lot_id')
            ->whereNull('inv_stock_allocations.reversed_at');
    }
}
