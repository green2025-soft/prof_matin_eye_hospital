<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['type', 'name', 'contact_no', 'address', 'active'])]
class Supplier extends BaseModel
{
    protected $table = 'inventory_suppliers';

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

    public function hasReferences(): bool
    {
        return $this->products()->exists()
            || $this->getConnection()->table('inventory_documents')->where('supplier_id', $this->getKey())->exists();
    }
}
