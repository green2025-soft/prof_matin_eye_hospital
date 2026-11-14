<?php

namespace Modules\TenantCore\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['head_id', 'amount'])]
class ExpenseItem extends BaseModel
{
    protected array $exposed = ['id', 'expense_id', 'head_id', 'amount', 'created_at', 'updated_at'];

    protected array $sortable = ['id'];

    protected string $defaultSort = 'id';

    protected function casts(): array
    {
        return ['expense_id' => 'integer', 'head_id' => 'integer', 'amount' => 'decimal:2'];
    }

    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class);
    }

    public function head(): BelongsTo
    {
        return $this->belongsTo(ExpenseHead::class, 'head_id');
    }
}
