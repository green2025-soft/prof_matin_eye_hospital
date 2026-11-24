<?php

namespace Modules\Pharmacy\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\TenantCore\Models\PaymentType;

abstract class Payment extends BaseModel
{
    protected string $crudPermission = 'pharmacy-payments';

    protected bool $branchScoped = true;

    protected array $searchable = ['number'];

    protected array $sortable = ['payment_date', 'id'];

    protected string $defaultSort = 'id';

    protected string $defaultDirection = 'desc';

    abstract public function items(): HasMany;

    protected function casts(): array
    {
        return [
            'branch_id' => 'integer',
            'payment_type_id' => 'integer',
            'payment_date' => 'date',
            'amount' => 'integer',
            'discount' => 'integer',
            'voided_at' => 'datetime',
        ];
    }

    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class);
    }
}
