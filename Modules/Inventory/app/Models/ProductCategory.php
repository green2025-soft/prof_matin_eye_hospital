<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['type', 'name', 'ordering'])]
class ProductCategory extends BaseModel
{
    protected $table = 'inventory_product_categories';

    protected string $crudPermission = 'inventory-categories';

    protected array $exposed = ['id', 'type', 'name', 'ordering', 'created_at'];

    protected array $searchable = ['name'];

    protected array $sortable = ['ordering', 'name', 'id'];

    protected string $defaultSort = 'ordering';

    protected array $filterable = ['type' => ['medicine' => 'Medicine', 'optic' => 'Optic']];

    protected array $selectorFilters = ['type'];

    protected function casts(): array
    {
        return ['ordering' => 'integer'];
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function hasReferences(): bool
    {
        return $this->products()->exists();
    }
}
