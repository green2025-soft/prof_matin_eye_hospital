<?php

namespace Modules\Hospital\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'description', 'ordering'])]
class Department extends BaseModel
{
    protected string $crudPermission = 'departments';

    protected array $exposed = ['id', 'name', 'description', 'ordering', 'created_at'];

    protected array $searchable = ['name'];

    protected array $sortable = ['ordering', 'name', 'id'];

    protected string $defaultSort = 'ordering';

    protected array $exposedCounts = ['doctors'];

    protected function casts(): array
    {
        return ['ordering' => 'integer'];
    }

    public function doctors(): HasMany
    {
        return $this->hasMany(Doctor::class);
    }

    public function hasReferences(): bool
    {
        return array_key_exists('doctors_count', $this->getAttributes())
            ? $this->doctors_count > 0 : $this->doctors()->exists();
    }
}
