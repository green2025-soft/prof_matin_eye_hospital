<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentAllocation extends BaseModel
{
    protected $table = 'inventory_payment_allocations';

    public $timestamps = false;

    protected function casts(): array
    {
        return ['payment_id' => 'integer', 'document_id' => 'integer', 'amount_minor' => 'integer', 'discount_minor' => 'integer'];
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class)->withTrashed();
    }
}
