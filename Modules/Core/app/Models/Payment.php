<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Helpers\Money;
use Modules\Core\Policies\PaymentPolicy;

#[UsePolicy(PaymentPolicy::class)]
class Payment extends CoreModel
{
    public const STATUS_COMPLETED = 'completed';

    public const PROVIDERS = ['manual', 'sslcommerz', 'bkash', 'nagad', 'eps'];

    protected $attributes = ['status' => self::STATUS_COMPLETED];

    protected $guarded = ['*'];

    protected $hidden = ['idempotency_key'];

    protected array $exposed = ['id', 'invoice_id', 'tenant_id', 'provider', 'provider_reference', 'amount_minor', 'extra_charge_minor', 'currency', 'method', 'status', 'paid_at', 'created_at'];

    protected array $searchable = ['provider_reference'];

    protected array $sortable = ['id', 'paid_at', 'created_at'];

    protected string $defaultSort = 'id';

    protected array $listWith = ['invoice:id,number', 'tenant:id,name'];

    protected array $formWith = [];

    protected array $exposedRelations = ['invoice' => ['id', 'number'], 'tenant' => ['id', 'name']];

    protected function casts(): array
    {
        return ['invoice_id' => 'integer', 'tenant_id' => 'integer', 'recorded_by' => 'integer', 'amount_minor' => 'integer', 'extra_charge_minor' => 'integer', 'paid_at' => 'immutable_datetime'];
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function getAmountAttribute(): string
    {
        return Money::decimal($this->amount_minor);
    }

    public function getExtraChargeAttribute(): string
    {
        return Money::decimal($this->extra_charge_minor);
    }
}
