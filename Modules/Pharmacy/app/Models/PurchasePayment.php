<?php

namespace Modules\Pharmacy\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchasePayment extends Payment
{
    protected $table = 'phr_purchase_phr_payments';

    protected array $exposed = [
        'id', 'branch_id', 'number', 'product_type', 'payment_date', 'supplier_id',
        'payment_type_id', 'amount', 'discount', 'notes', 'voided_at', 'created_at',
    ];

    protected function casts(): array
    {
        return parent::casts() + ['supplier_id' => 'integer'];
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchasePaymentItem::class, 'payment_id');
    }
}
