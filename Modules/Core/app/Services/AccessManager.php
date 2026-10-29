<?php

namespace Modules\Core\Services;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Modules\Core\Enums\AccessPermission;
use Modules\Core\Enums\PlatformPermission;
use Modules\Core\Models\Account;
use Modules\Core\Models\Admin;
use Modules\Core\Models\BaseModel;
use Modules\Core\Models\Plan;
use Modules\Core\Models\Role;
use Modules\Core\Models\Tenant;
use Spatie\Permission\Models\Permission;

class AccessManager
{
    public function actorMatchesContext(Account $account): bool
    {
        $tenant = Tenant::current();
        $connection = $account->getConnectionName();

        if ($tenant === null) {
            return $account instanceof Admin && $connection === 'central' && config('database.default') === 'central';
        }

        return ! $account instanceof Admin
            && $connection === 'tenant'
            && config('database.default') === 'tenant'
            && $account->getConnection()->getDatabaseName() === $tenant->getDatabaseName();
    }

    public function assertActorContext(Account $account): void
    {
        if (! $this->actorMatchesContext($account)) {
            throw new AuthorizationException('This account does not belong to the current application.');
        }
    }

    public function isOwner(Account $account): bool
    {
        return $this->actorMatchesContext($account) && $account->hasOwnerRole();
    }

    public function allowsCrud(Account $actor, mixed $subject, string $action): bool
    {
        $model = $this->crudModel($actor, $subject);

        if ($model === null || ! in_array($action, ['view', 'create', 'update', 'delete'], true)) {
            return false;
        }

        if (in_array($action, ['update', 'delete'], true) && ! $model->exists) {
            return false;
        }

        if (! $this->isOwner($actor) && ! $actor->can($model->crudPermission().'.'.$action)) {
            return false;
        }

        return $action !== 'delete' || ! $model->hasReferences();
    }

    private function crudModel(Account $actor, mixed $subject): ?BaseModel
    {
        if (is_string($subject) && is_subclass_of($subject, BaseModel::class)) {
            $subject = new $subject;
        }

        if (! $subject instanceof BaseModel || $subject->crudPermission() === '' || ! $this->actorMatchesContext($actor)) {
            return null;
        }

        $connection = $subject->getConnection();
        $actorConnection = $actor->getConnection();

        if ($connection->getName() !== $actorConnection->getName()
            || $connection->getDatabaseName() !== $actorConnection->getDatabaseName()
            || ! $subject->matchesBranchContext()) {
            return null;
        }

        return $subject;
    }

    public function permissionNames(Account $account): Collection
    {
        $this->assertActorContext($account);
        $connection = $account->getConnection()->getName();

        return $account->getAllPermissions()
            ->filter(fn (Permission $permission): bool => $permission->guard_name === $account->permissionGuardName() && $permission->getConnection()->getName() === $connection)
            ->pluck('name')->unique()->values();
    }

    public function navigationPermissions(Account $actor, ?Plan $plan): array
    {
        $this->assertActorContext($actor);
        $central = $actor instanceof Admin;

        if ($this->isOwner($actor)) {
            $permissions = array_merge(
                array_column(AccessPermission::cases(), 'value'),
                $central ? array_column(PlatformPermission::cases(), 'value') : array_values(config('permissions.tenant_routes', [])),
            );
        } else {
            $permissions = $this->permissionNames($actor)->all();
        }

        $allowed = [];

        foreach (array_unique($permissions) as $permission) {
            if ($central || str_starts_with($permission, 'billing.') || $plan?->allowsRoutePermission($permission)) {
                $allowed[$permission] = true;
            }
        }

        return $allowed;
    }

    public function permissionsWithinActor(Account $actor, iterable $permissions): bool
    {
        if (! $this->actorMatchesContext($actor)) {
            return false;
        }

        $connection = $actor->getConnection()->getName();
        $names = [];

        foreach ($permissions as $permission) {
            if (is_string($permission) && $permission !== '') {
                $names[] = $permission;

                continue;
            }

            if (! $permission instanceof Permission || $permission->guard_name !== $actor->permissionGuardName()
                || $permission->getConnection()->getName() !== $connection) {
                return false;
            }

            $names[] = $permission->name;
        }

        return collect($names)->diff($this->permissionNames($actor))->isEmpty();
    }

    public function roleWithinActor(Account $actor, Role $role): bool
    {
        if (! $this->actorMatchesContext($actor) || $role->guard_name !== 'web'
            || $role->getConnection()->getName() !== $actor->getConnection()->getName()
            || $role->isReserved()) {
            return false;
        }

        return $this->isOwner($actor) || $this->permissionsWithinActor($actor, $role->loadMissing('permissions')->permissions);
    }

    public function rolesQuery(Account $actor): Builder
    {
        $this->assertActorContext($actor);
        $query = Role::on($actor->getConnectionName())->where('guard_name', 'web');

        if (! $this->isOwner($actor)) {
            $permissions = $this->permissionNames($actor)->all();
            $query->where('name', '!=', Role::OWNER_NAME)
                ->whereDoesntHave('permissions', function (Builder $query) use ($permissions): void {
                    $query->where(function (Builder $query) use ($permissions): void {
                        $query->where('guard_name', '!=', 'web')->orWhereNotIn('name', $permissions);
                    });
                });
        }

        return $query;
    }

    public function rolesForAssignment(Account $actor, array $roleIds, bool $allowOwner = true): \Illuminate\Database\Eloquent\Collection
    {
        $this->assertActorContext($actor);
        $roles = Role::on($actor->getConnectionName())->where('guard_name', 'web')
            ->whereIn('id', $roleIds)->orderBy('id')->lockForUpdate()->with('permissions')->get();

        if ($roles->count() !== count($roleIds)) {
            throw ValidationException::withMessages(['roles' => 'Select valid roles from this application.']);
        }

        foreach ($roles as $role) {
            $allowed = $role->isReserved()
                ? $allowOwner && $this->isOwner($actor)
                : $this->roleWithinActor($actor, $role);

            if (! $allowed) {
                throw ValidationException::withMessages(['roles' => 'You cannot assign this role or grant greater access than your own.']);
            }
        }

        return $roles;
    }

    public function transaction(Account $actor, Closure $operation): mixed
    {
        $this->assertActorContext($actor);
        $connection = $actor->getConnection();

        return $connection->transaction(function () use ($actor, $connection, $operation): mixed {
            // Account and role mutations lock the same row before changing access.
            $ownerRole = Role::on($connection->getName())->where('guard_name', 'web')
                ->where('name', Role::OWNER_NAME)->lockForUpdate()->first();

            if ($ownerRole === null) {
                throw ValidationException::withMessages(['access' => 'Initialize the application owner before managing access.']);
            }

            $freshActor = $actor->newQuery()->whereKey($actor->getKey())->lockForUpdate()->firstOrFail();
            $this->assertActorContext($freshActor);
            $freshActor->load(['roles.permissions', 'permissions']);

            return $operation($freshActor);
        }, 3);
    }
}
