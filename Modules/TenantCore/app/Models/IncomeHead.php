<?php

namespace Modules\TenantCore\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'ordering'])]
class IncomeHead extends BaseModel
{
    protected string $crudPermission = 'income-heads';

    protected array $exposed = ['id', 'name', 'ordering', 'created_at'];

    protected array $searchable = ['name'];

    protected array $sortable = ['ordering', 'name', 'id'];

    protected string $defaultSort = 'ordering';

    protected function casts(): array
    {
        return ['ordering' => 'integer'];
    }

    public function items(): HasMany
    {
        return $this->hasMany(IncomeItem::class, 'head_id');
    }

    public function hasReferences(): bool
    {
        return $this->items_exists ?? $this->items()->exists();
    }

    public function listQuery(): Builder
    {
        return parent::listQuery()
            ->withExists('items');
    }
}
