<?php

namespace Modules\Pharmacy\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchasePaymentItem extends PaymentAllocation
{
    protected $table = 'phr_purchase_payment_items';

    protected array $exposed = ['id', 'payment_id', 'purchase_id', 'amount', 'discount'];

    protected function casts(): array
    {
        return parent::casts() + ['purchase_id' => 'integer'];
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(PurchasePayment::class, 'payment_id');
    }

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class)->withTrashed();
    }
}
