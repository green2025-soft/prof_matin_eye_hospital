<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Core\Models\Concerns\HasCrudContract;
use Spatie\Multitenancy\Models\Tenant as BaseTenant;

#[Fillable(['name', 'slug'])]
#[Hidden(['database_token'])]
class Tenant extends BaseTenant
{
    use HasCrudContract;

    public const STATUS_PENDING = 'pending';

    public const STATUS_PROVISIONING = 'provisioning';

    public const STATUS_ACTIVE = 'active';

    public const STATUS_FAILED = 'failed';

    public const STATUS_SUSPENDED = 'suspended';

    public const STATUSES = [
        self::STATUS_PENDING => 'Pending',
        self::STATUS_PROVISIONING => 'Provisioning',
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_FAILED => 'Failed',
        self::STATUS_SUSPENDED => 'Suspended',
    ];

    protected $attributes = ['status' => self::STATUS_PENDING];

    protected array $exposed = ['id', 'name', 'slug', 'owner_email', 'status', 'created_at'];

    protected array $filterable = ['status' => self::STATUSES];

    protected array $listWith = ['domains:id,tenant_id,domain'];

    protected array $exposedRelations = ['domains' => ['id', 'tenant_id', 'domain']];

    public function domains(): HasMany
    {
        return $this->hasMany(Domain::class);
    }

    public function isReady(): bool
    {
        return in_array($this->status, [self::STATUS_ACTIVE, self::STATUS_SUSPENDED], true);
    }

    public function statusOptions(): array
    {
        return $this->isReady()
            ? array_intersect_key(self::STATUSES, array_flip([self::STATUS_ACTIVE, self::STATUS_SUSPENDED]))
            : [$this->status => self::STATUSES[$this->status] ?? ucfirst($this->status)];
    }
}
