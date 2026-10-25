<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Core\Helpers\Money;

#[Fillable(['name', 'slug', 'description', 'price', 'price_minor', 'currency', 'billing_interval', 'trial_days', 'features', 'user_limit', 'is_active', 'route_permissions'])]

class Plan extends CoreModel
{
    public const INTERVALS = ['monthly' => 'Monthly', 'yearly' => 'Yearly'];

    protected $attributes = ['price_minor' => 0, 'billing_interval' => 'monthly', 'trial_days' => 0, 'is_active' => true];

    protected array $exposed = ['id', 'name', 'slug', 'description', 'price_minor', 'currency', 'billing_interval', 'trial_days', 'features', 'user_limit', 'is_active', 'created_at'];

    protected array $listColumns = ['id', 'name', 'slug', 'price_minor', 'currency', 'billing_interval', 'trial_days', 'user_limit', 'is_active', 'created_at'];

    protected array $searchable = ['name', 'slug'];

    protected array $sortable = ['name', 'price_minor', 'id'];

    protected array $filterable = ['billing_interval' => self::INTERVALS, 'is_active' => ['0', '1']];

    protected array $exposedCounts = ['subscriptions'];

    protected function casts(): array
    {
        return ['price_minor' => 'integer', 'trial_days' => 'integer', 'features' => 'array', 'route_permissions' => 'array', 'user_limit' => 'integer', 'is_active' => 'boolean'];
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function getPriceAttribute(): string
    {
        return Money::decimal($this->price_minor);
    }

    public function setPriceAttribute(string|int $value): void
    {
        $this->attributes['price_minor'] = Money::minor($value);
    }

    public function allowsFeature(string $key): bool
    {
        return ($this->features[$key] ?? false) === true;
    }

    public function allowsRoutePermission(string $permission): bool
    {
        return $this->route_permissions === null || in_array($permission, $this->route_permissions, true);
    }

    public function isDefault(): bool
    {
        return $this->slug === config('billing.default_plan', 'starter');
    }

    public function hasSubscriptions(): bool
    {
        return array_key_exists('subscriptions_count', $this->getAttributes())
            ? $this->subscriptions_count > 0
            : $this->subscriptions()->exists();
    }
}
