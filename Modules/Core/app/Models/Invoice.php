<?php

namespace Modules\Core\Models;

use Closure;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Core\Helpers\Money;
use Modules\Core\Policies\InvoicePolicy;

#[UsePolicy(InvoicePolicy::class)]
class Invoice extends CoreModel
{
    public const STATUS_DRAFT = 'draft';

    public const STATUS_ISSUED = 'issued';

    public const STATUS_PARTIALLY_PAID = 'partially_paid';

    public const STATUS_PAID = 'paid';

    public const STATUS_VOID = 'void';

    public const MAX_MINOR = 999999999999;

    public const STATUSES = ['draft' => 'Draft', 'issued' => 'Issued', 'partially_paid' => 'Partially paid', 'paid' => 'Paid', 'void' => 'Void'];

    protected $attributes = ['status' => self::STATUS_DRAFT, 'subtotal_minor' => 0, 'total_minor' => 0, 'paid_minor' => 0];

    protected $fillable = ['notes', 'due_at'];

    protected array $exposed = ['id', 'number', 'tenant_id', 'subscription_id', 'currency', 'status', 'subtotal_minor', 'total_minor', 'paid_minor', 'due_at', 'issued_at', 'created_at'];

    protected array $searchable = ['number'];

    protected array $sortable = ['id', 'number', 'created_at'];

    protected string $defaultSort = 'id';

    protected array $filterable = ['status' => self::STATUSES];

    protected array $listWith = ['tenant:id,name'];

    protected array $formWith = ['items', 'tenant:id,name'];

    protected array $exposedRelations = ['tenant' => ['id', 'name']];

    protected function casts(): array
    {
        return ['tenant_id' => 'integer', 'subscription_id' => 'integer', 'subtotal_minor' => 'integer', 'total_minor' => 'integer', 'paid_minor' => 'integer', 'merchant_snapshot' => 'array', 'customer_snapshot' => 'array', 'due_at' => 'immutable_date', 'issued_at' => 'immutable_datetime', 'voided_at' => 'immutable_datetime', 'period_start' => 'immutable_datetime', 'period_end' => 'immutable_datetime'];
    }

    public function resolveRouteBindingQuery($query, $value, $field = null)
    {
        $query = parent::resolveRouteBindingQuery($query, $value, $field);

        if ($tenant = Tenant::current()) {
            $query->where($this->qualifyColumn('tenant_id'), $tenant->getKey());
        }

        return $query;
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class)->orderBy('position')->orderBy('id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class)->orderByDesc('id');
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isPayable(): bool
    {
        return $this->issued_at !== null && in_array($this->status, [self::STATUS_ISSUED, self::STATUS_PARTIALLY_PAID], true);
    }

    public function outstandingMinor(): int
    {
        return max(0, $this->total_minor - $this->paid_minor);
    }

    public function locked(Closure $operation): mixed
    {
        return $this->getConnection()->transaction(function () use ($operation): mixed {
            $subscription = $this->subscription_id === null ? null
                : Subscription::query()->whereKey($this->subscription_id)->lockForUpdate()->firstOrFail();
            $current = static::query()->whereKey($this->getKey())->lockForUpdate()->firstOrFail();

            return $operation($current, $subscription);
        }, 3);
    }

    public function getTotalAmountAttribute(): string
    {
        return Money::decimal($this->total_minor);
    }

    public function getBalanceAmountAttribute(): string
    {
        return Money::decimal($this->outstandingMinor());
    }
}
