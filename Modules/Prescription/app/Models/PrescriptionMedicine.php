<?php

namespace Modules\Prescription\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Modules\Pharmacy\Models\Product;
use Modules\Pharmacy\Models\ProductCategory;

#[Fillable(['product_id', 'medicine_name', 'dosage_type', 'dose', 'frequency', 'duration', 'instructions', 'ordering'])]
class PrescriptionMedicine extends BaseModel
{
    protected array $exposed = ['id', 'prescription_id', 'product_id', 'medicine_name', 'dosage_type', 'dose', 'frequency', 'duration', 'instructions', 'ordering'];

    public static function dosageTypes(): array
    {
        return [
            'tablet_capsule' => 'Tablet / Capsule',
            'drop' => 'Drop',
            'gel_ointment' => 'Gel / Ointment',
            'other' => 'Other',
        ];
    }

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

    // Read the product's generic category through the existing foreign keys.
    public function generic(): HasOneThrough
    {
        return $this->hasOneThrough(
            ProductCategory::class,
            Product::class,
            'id',
            'id',
            'product_id',
            'category_id',
        );
    }
}
