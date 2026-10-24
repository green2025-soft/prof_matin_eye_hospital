<?php

namespace Modules\Core\Models;

use Closure;
use Modules\Core\Models\Concerns\HasCrudContract;
use Modules\Core\Services\AccessManager;
use Spatie\Permission\Models\Role as BaseRole;
use Spatie\Permission\PermissionRegistrar;

class Role extends BaseRole
{
    use HasCrudContract;

    public const OWNER_NAME = 'super-admin';

    protected array $queryConstraints = ['guard_name' => 'web'];

    protected array $exposed = ['id', 'name', 'guard_name', 'created_at'];

    protected array $listWith = ['permissions:id,name,guard_name'];

    protected array $exposedRelations = ['permissions' => ['id', 'name', 'guard_name']];

    protected array $exposedCounts = ['users'];

    protected static function booted(): void
    {
        $clearAfterCommit = static function (Role $role): void {
            $role->getConnection()->afterCommit(fn () => app(PermissionRegistrar::class)->forgetCachedPermissions());
        };

        static::saved($clearAfterCommit);
        static::deleted($clearAfterCommit);
    }

    public function writeTransaction(Account $actor, Closure $operation): mixed
    {
        return app(AccessManager::class)->transaction($actor, $operation);
    }

    public function setNameAttribute(string $value): void
    {
        $this->attributes['name'] = trim($value);
    }

    public function isReserved(): bool
    {
        return strcasecmp($this->name ?? '', self::OWNER_NAME) === 0;
    }
}
