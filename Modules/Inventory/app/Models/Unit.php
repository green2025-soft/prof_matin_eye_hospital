<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'symbol'])]
class Unit extends BaseModel
{
    protected $table = 'inventory_units';

    protected string $crudPermission = 'inventory-units';

    protected array $exposed = ['id', 'name', 'symbol', 'created_at'];

    protected array $searchable = ['name', 'symbol'];

    public function productUnits(): HasMany
    {
        return $this->hasMany(ProductUnit::class);
    }

    public function hasReferences(): bool
    {
        return $this->productUnits()->exists();
    }
}
