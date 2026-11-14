<?php

namespace Modules\TenantCore\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['payment_type_id', 'entry_date', 'note'])]
class Expense extends BaseModel
{
    protected string $crudPermission = 'expenses';

    protected bool $branchScoped = true;

    protected array $exposed = ['id', 'branch_id', 'payment_type_id', 'entry_date', 'amount', 'note', 'created_at', 'updated_at'];

    protected array $searchable = ['id', 'entry_date', 'note'];

    protected array $filterableRelations = ['filter_head_id' => ['items', 'head_id']];

    protected array $sortable = ['entry_date', 'amount', 'id'];

    protected string $defaultSort = 'entry_date';

    protected string $defaultDirection = 'desc';

    protected array $listWith = ['items.head:id,name', 'paymentType:id,name'];

    protected array $formWith = ['items.head:id,name'];

    protected array $exposedRelations = ['paymentType' => ['id', 'name']];

    protected function casts(): array
    {
        return ['branch_id' => 'integer', 'payment_type_id' => 'integer', 'entry_date' => 'date', 'amount' => 'decimal:2'];
    }

    public function items(): HasMany
    {
        return $this->hasMany(ExpenseItem::class)
            ->orderBy('id');
    }

    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class);
    }
}
