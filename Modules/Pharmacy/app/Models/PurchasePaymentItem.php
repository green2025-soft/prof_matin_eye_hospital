<?php

namespace Modules\Pharmacy\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchasePaymentItem extends PaymentAllocation
{
    protected $table = 'phr_purchase_payment_items';

    protected function casts(): array
    {
        return parent::casts() + ['purchase_id' => 'integer'];
    }

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class)->withTrashed();
    }
}
