<?php

namespace Modules\TenantCore\Models;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Modules\TenantCore\Policies\AuditLogPolicy;

#[UsePolicy(AuditLogPolicy::class)]
class AuditLog extends BaseModel
{
    public const EVENTS = ['created' => 'Created', 'updated' => 'Updated', 'deleted' => 'Deleted', 'login' => 'Login', 'logout' => 'Logout'];

    public $timestamps = false;

    protected array $exposed = ['id', 'branch_id', 'branch_name', 'actor_id', 'actor_type', 'actor_name', 'event', 'resource', 'record_id', 'record_name', 'old_values', 'new_values', 'ip_address', 'user_agent', 'created_at'];

    protected array $listColumns = ['id', 'branch_id', 'branch_name', 'actor_id', 'actor_type', 'actor_name', 'event', 'resource', 'record_id', 'record_name', 'created_at'];

    protected array $searchable = ['actor_name', 'record_name'];

    protected array $filterable = ['event' => self::EVENTS];

    protected array $sortable = ['id', 'created_at'];

    protected string $defaultSort = 'id';

    protected array $formWith = [];

    protected array $allowedIncludes = [];

    protected function casts(): array
    {
        return ['branch_id' => 'integer', 'actor_id' => 'integer', 'old_values' => 'array', 'new_values' => 'array', 'created_at' => 'immutable_datetime'];
    }

    protected static function booted(): void
    {
        parent::booted();

        $denyWrite = static function (): void {
            throw new AuthorizationException('Audit logs are read-only.');
        };

        static::saving($denyWrite);
        static::deleting($denyWrite);
    }
}
