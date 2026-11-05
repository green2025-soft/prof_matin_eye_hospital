<?php

namespace Modules\Pharmacy\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends BaseModel
{
    protected $table = 'phr_payments';

    protected string $crudPermission = 'pharmacy-payments';

    protected bool $branchScoped = true;

    protected array $exposed = [
        'id', 'branch_id', 'number', 'direction', 'product_type', 'payment_date',
        'customer_id', 'supplier_id', 'payment_type_id',
        'amount', 'discount', 'notes', 'voided_at', 'created_at',
    ];

    protected array $searchable = ['number'];

    protected array $sortable = ['payment_date', 'id'];

    protected string $defaultSort = 'id';

    protected string $defaultDirection = 'desc';

    protected function casts(): array
    {
        return [
            'branch_id' => 'integer',
            'customer_id' => 'integer',
            'supplier_id' => 'integer',
            'payment_type_id' => 'integer',
            'payment_date' => 'date',
            'amount' => 'integer',
            'discount' => 'integer',
            'voided_at' => 'datetime',
        ];
    }

    public function purchaseAllocations(): HasMany
    {
        return $this->hasMany(PurchasePaymentAllocation::class, 'payment_id');
    }

    public function saleAllocations(): HasMany
    {
        return $this->hasMany(SalePaymentAllocation::class, 'payment_id');
    }
}
