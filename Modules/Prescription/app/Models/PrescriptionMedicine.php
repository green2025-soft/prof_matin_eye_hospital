<?php

namespace Modules\Prescription\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Pharmacy\Models\Product;

#[Fillable(['product_id', 'medicine_name', 'dose', 'frequency', 'duration', 'instructions', 'ordering'])]
class PrescriptionMedicine extends BaseModel
{
    protected array $exposed = ['id', 'prescription_id', 'product_id', 'medicine_name', 'dose', 'frequency', 'duration', 'instructions', 'ordering'];

    protected function casts(): array
    {
        return ['prescription_id' => 'integer', 'product_id' => 'integer', 'ordering' => 'integer'];
    }

    public function prescription(): BelongsTo
    {
        return $this->belongsTo(Prescription::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
