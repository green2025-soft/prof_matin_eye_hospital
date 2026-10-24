<?php

namespace Modules\Core\Services;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Modules\Core\Models\Account;
use Modules\Core\Models\Admin;
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

    public function permissionNames(Account $account): Collection
    {
        $this->assertActorContext($account);
        $connection = $account->getConnection()->getName();

        return $account->getAllPermissions()
            ->filter(fn (Permission $permission): bool => $permission->guard_name === 'web' && $permission->getConnection()->getName() === $connection)
            ->pluck('name')->unique()->values();
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

            if (! $permission instanceof Permission || $permission->guard_name !== 'web'
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
