<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

abstract class PaymentAllocation extends BaseModel
{
    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'payment_id' => 'integer',
            'amount' => 'integer',
            'discount' => 'integer',
        ];
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }
}
