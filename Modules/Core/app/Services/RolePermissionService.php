<?php

namespace Modules\Core\Services;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Modules\Core\Models\Account;
use Modules\Core\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionService
{
    public function __construct(private AccessManager $access, private PermissionCatalog $catalog) {}

    public function pageData(Account $actor, array $filters): array
    {
        $this->access->assertActorContext($actor);
        Gate::forUser($actor)->authorize('viewPermissions', Role::class);
        $roleId = $filters['role'] ?? null;
        $roleSearch = $filters['role_search'] ?? '';
        $query = $this->access->rolesQuery($actor)->select(['id', 'name', 'guard_name']);

        if ($roleSearch !== '') {
            $prefix = str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $roleSearch);
            $query->whereRaw("name LIKE ? ESCAPE '!'", [$prefix.'%']);
        }

        $matches = $query->orderBy('name')->orderBy('id')->limit(101)->get();
        $roles = $matches->take(100)->values();
        $selectedRole = $roleId === null ? null : $this->access->rolesQuery($actor)
            ->with('permissions:id,name,guard_name')->findOrFail($roleId, ['id', 'name', 'guard_name']);

        if ($selectedRole && ! $roles->contains('id', $selectedRole->getKey())) {
            $roles->push($selectedRole);
        }

        $permissions = Permission::on($actor->getConnection()->getName())->where('guard_name', 'web')
            ->whereIn('name', $this->access->permissionNames($actor))->orderBy('name')->limit(501)->get(['id', 'name', 'guard_name']);

        if ($permissions->count() > 500) {
            throw ValidationException::withMessages(['permissions' => 'There are too many available permissions for this page. Contact the application administrator.']);
        }

        return [
            'roles' => $roles,
            'selectedRole' => $selectedRole,
            'roleSearch' => $roleSearch,
            'hasMoreRoles' => $matches->count() > 100,
            'areas' => $this->catalog->areas($permissions->pluck('name', 'id')->all()),
            'selectedPermissions' => $selectedRole?->permissions->modelKeys() ?? [],
            'permissionCount' => $permissions->count(),
            'canManagePermissions' => $selectedRole !== null && Gate::forUser($actor)->allows('managePermissions', $selectedRole),
            'pageTitle' => 'Permissions',
        ];
    }

    public function save(Account $actor, ?Model $record, array $values): Role
    {
        $this->access->assertActorContext($actor);

        if (! $record instanceof Role || ! $record->exists) {
            throw new AuthorizationException;
        }

        $role = $record;
        $permissionIds = $values['permissions'];
        Gate::forUser($actor)->authorize('managePermissions', $role);

        return $this->access->transaction($actor, function (Account $freshActor) use ($role, $permissionIds): Role {
            $connection = $freshActor->getConnection()->getName();
            $record = Role::on($connection)->where('guard_name', 'web')->whereKey($role->getKey())->lockForUpdate()->firstOrFail();
            Gate::forUser($freshActor)->authorize('managePermissions', $record);

            if (count($permissionIds) > 500) {
                throw ValidationException::withMessages(['permissions' => 'Select no more than 500 permissions.']);
            }

            $permissions = Permission::on($connection)->where('guard_name', 'web')->whereIn('id', $permissionIds)->get(['id', 'name', 'guard_name']);

            if ($permissions->count() !== count(array_unique($permissionIds))) {
                throw ValidationException::withMessages(['permissions' => 'Select permissions from the current application.']);
            }

            if (! $this->access->permissionsWithinActor($freshActor, $permissions)) {
                throw ValidationException::withMessages(['permissions' => 'You may grant only permissions you hold yourself.']);
            }

            $record->syncPermissions($permissions);
            $record->getConnection()->afterCommit(fn () => app(PermissionRegistrar::class)->forgetCachedPermissions());

            return $record->load('permissions:id,name,guard_name');
        });
    }
}
