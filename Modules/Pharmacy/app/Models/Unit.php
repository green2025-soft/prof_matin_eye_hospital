<?php

namespace Modules\Pharmacy\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'symbol'])]
class Unit extends BaseModel
{
    protected $table = 'phr_units';

    protected string $crudPermission = 'pharmacy-units';

    protected array $exposed = ['id', 'name', 'symbol', 'created_at'];

    protected array $searchable = ['name', 'symbol'];

    public function productUnits(): HasMany
    {
        return $this->hasMany(ProductUnit::class);
    }

    public function listQuery(): Builder
    {
        return parent::listQuery()
            ->withExists('productUnits');
    }

    public function hasReferences(): bool
    {
        return $this->product_units_exists ?? $this->productUnits()->exists();
    }
}
