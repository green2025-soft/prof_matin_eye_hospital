<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceItem extends CoreModel
{
    protected $fillable = ['description', 'quantity', 'unit_amount_minor', 'line_total_minor', 'position'];

    protected array $exposed = ['id', 'invoice_id', 'description', 'quantity', 'unit_amount_minor', 'line_total_minor', 'position'];

    protected function casts(): array
    {
        return ['quantity' => 'integer', 'unit_amount_minor' => 'integer', 'line_total_minor' => 'integer', 'position' => 'integer'];
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
