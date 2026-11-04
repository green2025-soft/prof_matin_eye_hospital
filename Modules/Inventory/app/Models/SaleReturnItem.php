<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleReturnItem extends StockItem
{
    protected $table = 'inv_sale_return_items';

    protected array $exposed = [
        ...parent::COMMON_EXPOSED, 'sale_return_id', 'sale_item_id', 'superseded_at', 'unit_price',
        'subtotal', 'discount', 'invoice_discount', 'vat', 'total', 'vat_mode',
    ];

    protected function casts(): array
    {
        return parent::casts() + [
            'sale_return_id' => 'integer', 'sale_item_id' => 'integer', 'superseded_at' => 'datetime',
            'unit_price' => 'integer', 'subtotal' => 'integer', 'discount' => 'integer',
            'invoice_discount' => 'integer', 'vat' => 'integer', 'total' => 'integer',
        ];
    }

    public function saleReturn(): BelongsTo
    {
        return $this->belongsTo(SaleReturn::class)->withTrashed();
    }

    public function saleItem(): BelongsTo
    {
        return $this->belongsTo(SaleItem::class);
    }
}
