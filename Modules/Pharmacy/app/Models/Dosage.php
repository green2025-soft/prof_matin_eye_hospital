<?php

namespace Modules\Pharmacy\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'ordering'])]
class Dosage extends BaseModel
{
    protected $table = 'phr_dosages';

    protected string $crudPermission = 'pharmacy-dosages';

    protected array $exposed = ['id', 'name', 'ordering', 'created_at'];

    protected array $searchable = ['name'];

    protected array $sortable = ['ordering', 'name', 'id'];

    protected string $defaultSort = 'ordering';

    protected function casts(): array
    {
        return ['ordering' => 'integer'];
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function hasReferences(): bool
    {
        return $this->products()->exists();
    }
}
