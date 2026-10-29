<?php

namespace Modules\Core\Models;

use Closure;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Builder;
use Modules\Core\Models\Concerns\HasCrudContract;
use Modules\Core\Policies\RolePolicy;
use Modules\Core\Services\AccessManager;
use Spatie\Permission\Models\Role as BaseRole;
use Spatie\Permission\PermissionRegistrar;

#[UsePolicy(RolePolicy::class)]
class Role extends BaseRole
{
    use HasCrudContract {
        listQuery as private baseListQuery;
    }

    public const OWNER_NAME = 'super-admin';

    protected array $queryConstraints = ['guard_name' => 'web'];

    protected array $exposed = ['id', 'name', 'guard_name', 'created_at'];

    protected array $listWith = ['permissions:id,name,guard_name'];

    protected array $exposedRelations = ['permissions' => ['id', 'name', 'guard_name']];

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

    public function resolveRouteBindingQuery($query, $value, $field = null)
    {
        return parent::resolveRouteBindingQuery($query, $value, $field)
            ->where($this->qualifyColumn('guard_name'), 'web');
    }

    public function setNameAttribute(string $value): void
    {
        $this->attributes['name'] = trim($value);
    }

    public function isReserved(): bool
    {
        return strcasecmp($this->name ?? '', self::OWNER_NAME) === 0;
    }

    public function listQuery(): Builder
    {
        $query = $this->baseListQuery();
        $registrar = app(PermissionRegistrar::class);
        $pivotTable = config('permission.table_names.model_has_roles');
        $assignments = $this->getConnection()->table($pivotTable)->selectRaw('COUNT(*)')
            ->whereColumn($pivotTable.'.'.$registrar->pivotRole, $this->qualifyColumn($this->getKeyName()));

        return $query->selectSub($assignments, 'accounts_count');
    }

    public function hasAssignedAccounts(): bool
    {
        $attributes = $this->getAttributes();
        if (array_key_exists('accounts_count', $attributes)) {
            return $attributes['accounts_count'] > 0;
        }

        return $this->getConnection()->table(config('permission.table_names.model_has_roles'))
            ->where(app(PermissionRegistrar::class)->pivotRole, $this->getKey())->exists();
    }
}
