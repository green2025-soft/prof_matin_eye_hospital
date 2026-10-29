<?php

namespace Modules\Hospital\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['name', 'category_id', 'single_rate', 'both_rate', 'commission_type', 'commission_percent', 'commission_amount', 'ordering'])]
class Operation extends BaseModel
{
    protected string $crudPermission = 'operations';

    protected array $exposed = ['id', 'name', 'category_id', 'single_rate', 'both_rate', 'commission_type', 'commission_percent', 'commission_amount', 'ordering', 'created_at'];

    protected array $searchable = ['name'];

    protected array $sortable = ['ordering', 'name', 'id'];

    protected string $defaultSort = 'ordering';

    protected array $listWith = ['category:id,name'];

    protected array $formWith = [];

    protected array $exposedRelations = ['category' => ['id', 'name']];

    protected function casts(): array
    {
        return ['category_id' => 'integer', 'single_rate' => 'decimal:2', 'both_rate' => 'decimal:2', 'commission_percent' => 'decimal:2', 'commission_amount' => 'decimal:2', 'ordering' => 'integer'];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(OperationCategory::class, 'category_id');
    }
}
