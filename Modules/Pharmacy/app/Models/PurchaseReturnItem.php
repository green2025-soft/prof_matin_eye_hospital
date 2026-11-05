<?php

namespace Modules\Pharmacy\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseReturnItem extends StockItem
{
    protected $table = 'phr_purchase_return_items';

    protected array $exposed = [
        ...parent::COMMON_EXPOSED, 'purchase_return_id', 'purchase_item_id', 'superseded_at', 'unit_price',
        'subtotal', 'discount', 'invoice_discount', 'vat', 'total', 'vat_mode',
    ];

    protected function casts(): array
    {
        return parent::casts() + [
            'purchase_return_id' => 'integer', 'purchase_item_id' => 'integer', 'superseded_at' => 'datetime',
            'unit_price' => 'integer', 'subtotal' => 'integer', 'discount' => 'integer',
            'invoice_discount' => 'integer', 'vat' => 'integer', 'total' => 'integer',
        ];
    }

    public function purchaseReturn(): BelongsTo
    {
        return $this->belongsTo(PurchaseReturn::class)->withTrashed();
    }

    public function purchaseItem(): BelongsTo
    {
        return $this->belongsTo(PurchaseItem::class);
    }
}
