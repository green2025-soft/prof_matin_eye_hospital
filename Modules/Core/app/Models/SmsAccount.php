<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Policies\SmsPolicy;

#[UsePolicy(SmsPolicy::class)]
class SmsAccount extends CoreModel
{
    protected $guarded = ['*'];

    protected $hidden = ['gateway'];

    protected $attributes = ['enabled' => false, 'balance' => 0, 'rate' => 0];

    protected array $exposed = ['id', 'tenant_id', 'enabled', 'balance', 'rate', 'monthly_limit', 'allowed_features', 'created_at'];

    protected array $listWith = ['tenant:id,name,slug'];

    protected function casts(): array
    {
        return [
            'tenant_id' => 'integer',
            'enabled' => 'boolean',
            'balance' => 'integer',
            'rate' => 'integer',
            'monthly_limit' => 'integer',
            'allowed_features' => 'array',
            'gateway' => 'encrypted:array',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    // One current-tenant snapshot serves menus, policies and module settings.
    public static function availableFeatures(): array
    {
        $account = Tenant::current()?->smsAccount;
        if (! $account?->enabled) {
            return [];
        }

        return array_intersect_key(
            config('settings.sms_features', []),
            array_fill_keys($account->allowed_features ?? [], true),
        );
    }
}
