<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalePaymentAllocation extends PaymentAllocation
{
    protected $table = 'inv_sale_payment_allocations';

    protected function casts(): array
    {
        return parent::casts() + ['sale_id' => 'integer'];
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class)->withTrashed();
    }
}
