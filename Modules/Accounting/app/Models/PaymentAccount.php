<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\ValidationException;
use Modules\TenantCore\Models\PaymentType;

#[Fillable(['payment_type_id', 'account_head_id'])]
class PaymentAccount extends BaseModel
{
    protected $table = 'acc_payment_accounts';

    protected string $crudPermission = 'accounting-mappings';

    protected array $exposed = ['id', 'payment_type_id', 'account_head_id', 'created_at'];

    protected array $searchable = [];

    protected array $sortable = ['id'];

    protected string $defaultSort = 'id';

    protected array $listWith = ['paymentType:id,name', 'accountHead:id,code,name'];

    protected array $formWith = [];

    protected array $exposedRelations = ['paymentType' => ['id', 'name'], 'accountHead' => ['id', 'code', 'name']];

    protected static function booted(): void
    {
        parent::booted();
        static::saving(function (self $mapping): void {
            $ledger = AccountHead::postable()
                ->where('type', 'asset')
                ->whereKey($mapping->account_head_id)
                ->lockForUpdate()
                ->first(['id']);

            if ($ledger === null) {
                throw ValidationException::withMessages(['account_head_id' => 'Select an active cash, bank or wallet ledger.']);
            }
        });
    }

    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function accountHead(): BelongsTo
    {
        return $this->belongsTo(AccountHead::class);
    }
}
