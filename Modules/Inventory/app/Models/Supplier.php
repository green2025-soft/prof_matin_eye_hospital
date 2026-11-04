<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['type', 'name', 'contact_no', 'address', 'active'])]
class Supplier extends BaseModel
{
    protected $table = 'inv_suppliers';

    protected string $crudPermission = 'inventory-suppliers';

    protected array $exposed = ['id', 'type', 'name', 'contact_no', 'address', 'active', 'created_at'];

    protected array $searchable = ['name', 'contact_no'];

    protected array $filterable = ['type' => ['medicine' => 'Medicine', 'optic' => 'Optic']];

    protected array $selectorFilters = ['type'];

    protected function casts(): array
    {
        return ['active' => 'boolean'];
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class, 'supplier_id');
    }

    public function purchaseReturns(): HasMany
    {
        return $this->hasMany(PurchaseReturn::class, 'supplier_id');
    }

    public function hasReferences(): bool
    {
        return $this->products()->exists()
            || $this->purchases()->withoutGlobalScope('branch')->withTrashed()->exists()
            || $this->purchaseReturns()->withoutGlobalScope('branch')->withTrashed()->exists();
    }
}
