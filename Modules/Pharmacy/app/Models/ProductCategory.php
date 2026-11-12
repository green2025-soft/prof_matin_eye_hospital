<?php

namespace Modules\Pharmacy\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['type', 'name', 'ordering'])]
class ProductCategory extends BaseModel
{
    protected $table = 'phr_product_categories';

    protected string $crudPermission = 'pharmacy-categories';

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

    public function listQuery(): Builder
    {
        return parent::listQuery()
            ->withExists('products');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function hasReferences(): bool
    {
        return $this->products_exists ?? $this->products()->exists();
    }
}
