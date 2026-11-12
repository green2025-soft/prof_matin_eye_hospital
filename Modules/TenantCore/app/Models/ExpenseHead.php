<?php

namespace Modules\TenantCore\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'ordering'])]
class ExpenseHead extends BaseModel
{
    protected string $crudPermission = 'expense-heads';

    protected array $exposed = ['id', 'name', 'ordering', 'created_at'];

    protected array $searchable = ['name'];

    protected array $sortable = ['ordering', 'name', 'id'];

    protected string $defaultSort = 'ordering';

    protected function casts(): array
    {
        return ['ordering' => 'integer'];
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'head_id');
    }

    public function hasReferences(): bool
    {
        return $this->expenses_exists ?? $this->expenses()->withoutGlobalScope('branch')->exists();
    }

    public function listQuery(): Builder
    {
        return parent::listQuery()
            ->withExists([
                'expenses' => fn (Builder $expenses) => $expenses->withoutGlobalScope('branch'),
            ]);
    }
}
