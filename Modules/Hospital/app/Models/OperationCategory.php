<?php

namespace Modules\Hospital\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'ordering'])]
class OperationCategory extends BaseModel
{
    protected string $crudPermission = 'operation-categories';

    protected array $exposed = ['id', 'name', 'ordering', 'created_at'];

    protected array $searchable = ['name'];

    protected array $sortable = ['ordering', 'name', 'id'];

    protected string $defaultSort = 'ordering';

    protected array $exposedCounts = ['operations'];

    protected function casts(): array
    {
        return ['ordering' => 'integer'];
    }

    public function operations(): HasMany
    {
        return $this->hasMany(Operation::class, 'category_id');
    }

    public function hasReferences(): bool
    {
        return array_key_exists('operations_count', $this->getAttributes())
            ? $this->operations_count > 0 : $this->operations()->exists();
    }
}
