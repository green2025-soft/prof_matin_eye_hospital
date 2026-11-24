<?php

namespace Modules\Pharmacy\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['payment_id', 'sale_id', 'amount', 'discount'])]
class SalePaymentItem extends PaymentAllocation
{
    protected $table = 'phr_sale_payment_items';

    protected array $exposed = ['id', 'payment_id', 'sale_id', 'amount', 'discount'];

    protected function casts(): array
    {
        return parent::casts() + ['sale_id' => 'integer'];
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(SalePayment::class, 'payment_id');
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class)->withTrashed();
    }
}
