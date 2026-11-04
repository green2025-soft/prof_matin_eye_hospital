<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchasePaymentAllocation extends PaymentAllocation
{
    protected $table = 'inv_purchase_payment_allocations';

    protected function casts(): array
    {
        return parent::casts() + ['purchase_id' => 'integer'];
    }

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class)->withTrashed();
    }
}
