<?php

namespace Modules\TenantCore\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['head_id', 'amount'])]
class IncomeItem extends BaseModel
{
    protected array $exposed = ['id', 'income_id', 'head_id', 'amount', 'created_at', 'updated_at'];

    protected array $sortable = ['id'];

    protected string $defaultSort = 'id';

    protected function casts(): array
    {
        return ['income_id' => 'integer', 'head_id' => 'integer', 'amount' => 'decimal:2'];
    }

    public function income(): BelongsTo
    {
        return $this->belongsTo(Income::class);
    }

    public function head(): BelongsTo
    {
        return $this->belongsTo(IncomeHead::class, 'head_id');
    }
}
