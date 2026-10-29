<?php

namespace Modules\Core\Models;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Core\Helpers\Money;
use Modules\Core\Policies\SubscriptionPolicy;
use Modules\Core\Services\ContextCache;

#[Fillable(['tenant_id', 'plan_id'])]
#[UsePolicy(SubscriptionPolicy::class)]
class Subscription extends CoreModel
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_TRIALING = 'trialing';

    public const STATUS_ACTIVE = 'active';

    public const STATUS_PAST_DUE = 'past_due';

    public const STATUS_CANCELLED = 'cancelled';

    public const STATUS_EXPIRED = 'expired';

    public const STATUSES = [self::STATUS_PENDING => 'Pending payment', self::STATUS_TRIALING => 'Trial', self::STATUS_ACTIVE => 'Active', self::STATUS_PAST_DUE => 'Past due', self::STATUS_CANCELLED => 'Cancelled', self::STATUS_EXPIRED => 'Expired'];

    protected $attributes = ['status' => self::STATUS_PENDING, 'cancel_at_period_end' => false];

    protected array $exposed = ['id', 'tenant_id', 'plan_id', 'status', 'price_minor', 'currency', 'billing_interval', 'starts_at', 'trial_ends_at', 'current_period_starts_at', 'current_period_ends_at', 'ends_at', 'cancel_at_period_end', 'created_at'];

    protected array $searchable = [];

    protected array $sortable = ['id', 'current_period_ends_at'];

    protected string $defaultSort = 'id';

    protected array $filterable = ['status' => self::STATUSES];

    protected array $listWith = ['tenant:id,name', 'plan:id,name'];

    protected array $exposedRelations = ['tenant' => ['id', 'name'], 'plan' => ['id', 'name']];

    protected static function booted(): void
    {
        $clearContext = static fn (self $subscription) => app(ContextCache::class)->flush($subscription->getConnection()->getName(), 'subscriptions');

        static::saved($clearContext);
        static::deleted($clearContext);
    }

    protected function casts(): array
    {
        return ['price_minor' => 'integer', 'starts_at' => 'immutable_datetime', 'trial_ends_at' => 'immutable_datetime', 'current_period_starts_at' => 'immutable_datetime', 'current_period_ends_at' => 'immutable_datetime', 'ends_at' => 'immutable_datetime', 'cancel_at_period_end' => 'boolean'];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public static function forTenant(Tenant $tenant): ?self
    {
        $model = new static;
        $connection = $model->getConnection()->getName();
        $snapshot = app(ContextCache::class)->remember($connection, 'subscriptions', $tenant->getKey(), static function () use ($tenant): ?array {
            $subscription = static::query()->with('plan')->where('tenant_id', $tenant->getKey())->first();

            return $subscription === null ? null : [
                'attributes' => $subscription->getAttributes(),
                'plan' => $subscription->getRelation('plan')?->getAttributes(),
            ];
        });

        if ($snapshot === null) {
            return null;
        }

        // Hydrate separate models so callers cannot mutate another request's cached objects.
        $subscription = $model->newFromBuilder($snapshot['attributes'], $connection);
        $plan = $snapshot['plan'] === null ? null : (new Plan)->newFromBuilder($snapshot['plan'], $connection);

        return $subscription->setRelation('plan', $plan);
    }

    public function getPriceAttribute(): string
    {
        return Money::decimal($this->price_minor ?? 0);
    }

    public function getNameAttribute(): string
    {
        return 'Subscription #'.$this->getKey();
    }

    public function isUsable(): bool
    {
        $now = now();

        if ($this->starts_at === null || $this->starts_at->isAfter($now)
            || $this->current_period_starts_at === null || $this->current_period_starts_at->isAfter($now)) {
            return false;
        }

        if ($this->status === self::STATUS_TRIALING) {
            return $this->trial_ends_at !== null && $this->trial_ends_at->isAfter($now);
        }

        return $this->status === self::STATUS_ACTIVE && $this->current_period_ends_at !== null && $this->current_period_ends_at->isAfter($now);
    }

    public function allowsFeature(string $key): bool
    {
        return $this->isUsable() && $this->plan->allowsFeature($key);
    }

    public function canAddUser(int $currentCount): bool
    {
        return $this->isUsable() && ($this->plan->user_limit === null || $currentCount < $this->plan->user_limit);
    }

    public function periodEnd(CarbonInterface $start): CarbonImmutable
    {
        $date = CarbonImmutable::instance($start);

        return $this->billing_interval === 'yearly' ? $date->addYearNoOverflow() : $date->addMonthNoOverflow();
    }
}
