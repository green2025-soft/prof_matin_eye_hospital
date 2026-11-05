<?php

namespace Modules\Pharmacy\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseItem extends StockItem
{
    protected $table = 'phr_purchase_items';

    protected array $exposed = [
        ...parent::COMMON_EXPOSED, 'purchase_id', 'returned_base_quantity', 'superseded_at',
        'unit_price', 'sale_price_per_piece', 'subtotal', 'discount',
        'discount_calculation', 'discount_percent', 'discount_unit_amount', 'invoice_discount',
        'vat_mode', 'vat_percent', 'vat_calculation', 'vat_unit_amount', 'vat', 'total',
        'batch_no', 'expiry_date',
    ];

    protected function casts(): array
    {
        return parent::casts() + [
            'purchase_id' => 'integer', 'returned_base_quantity' => 'integer', 'superseded_at' => 'datetime',
            'unit_price' => 'integer', 'sale_price_per_piece' => 'integer', 'subtotal' => 'integer',
            'discount' => 'integer', 'discount_percent' => 'decimal:2', 'discount_unit_amount' => 'integer',
            'invoice_discount' => 'integer', 'vat_percent' => 'decimal:2', 'vat_unit_amount' => 'integer',
            'vat' => 'integer', 'total' => 'integer', 'expiry_date' => 'date',
        ];
    }

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class)->withTrashed();
    }

    public function returns(): HasMany
    {
        return $this->hasMany(PurchaseReturnItem::class);
    }
}
