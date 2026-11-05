<?php

namespace Modules\Pharmacy\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['type', 'name', 'number'])]
class Shelf extends BaseModel
{
    protected $table = 'phr_shelves';

    protected string $crudPermission = 'pharmacy-shelves';

    protected array $exposed = ['id', 'type', 'name', 'number', 'created_at'];

    protected array $searchable = ['name', 'number'];

    protected array $filterable = ['type' => ['medicine' => 'Medicine', 'optic' => 'Optic']];

    protected array $selectorFilters = ['type'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function hasReferences(): bool
    {
        return $this->products()->exists();
    }
}
