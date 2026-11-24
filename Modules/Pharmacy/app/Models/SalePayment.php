<?php

namespace Modules\Pharmacy\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class SalePayment extends Payment
{
    protected $table = 'phr_sale_phr_payments';

    protected array $exposed = [
        'id', 'branch_id', 'number', 'product_type', 'payment_date', 'customer_id',
        'payment_type_id', 'amount', 'discount', 'notes', 'voided_at', 'created_at',
    ];

    protected function casts(): array
    {
        return parent::casts() + ['customer_id' => 'integer'];
    }

    public function items(): HasMany
    {
        return $this->hasMany(SalePaymentItem::class, 'payment_id');
    }
}
