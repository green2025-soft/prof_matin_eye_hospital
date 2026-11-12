<?php

namespace Modules\TenantCore\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['head_id', 'payment_type_id', 'entry_date', 'amount', 'note'])]
class Expense extends BaseModel
{
    protected string $crudPermission = 'expenses';

    protected bool $branchScoped = true;

    protected array $exposed = ['id', 'branch_id', 'head_id', 'payment_type_id', 'entry_date', 'amount', 'note', 'created_at', 'updated_at'];

    protected array $searchable = ['id', 'entry_date', 'note'];

    protected array $filterableIds = ['filter_head_id' => 'head_id'];

    protected array $sortable = ['entry_date', 'amount', 'id'];

    protected string $defaultSort = 'entry_date';

    protected string $defaultDirection = 'desc';

    protected array $listWith = ['head:id,name', 'paymentType:id,name'];

    protected array $formWith = [];

    protected array $exposedRelations = ['head' => ['id', 'name'], 'paymentType' => ['id', 'name']];

    protected function casts(): array
    {
        return ['branch_id' => 'integer', 'head_id' => 'integer', 'payment_type_id' => 'integer', 'entry_date' => 'date', 'amount' => 'decimal:2'];
    }

    public function head(): BelongsTo
    {
        return $this->belongsTo(ExpenseHead::class, 'head_id');
    }

    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class);
    }
}
