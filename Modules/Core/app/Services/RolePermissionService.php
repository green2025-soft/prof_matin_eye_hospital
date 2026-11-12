<?php

namespace Modules\Core\Services;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Modules\Core\Models\Account;
use Modules\Core\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionService
{
    public function __construct(private PermissionCatalog $catalog) {}

    public function pageData(Account $actor, array $filters): array
    {
        $actor->assertApplicationContext();
        Gate::forUser($actor)
            ->authorize('viewPermissions', Role::class);

        $roleId = $filters['role'] ?? null;
        $roleSearch = $filters['role_search'] ?? '';
        $query = Role::on($actor->getConnectionName())
            ->assignableBy($actor)
            ->select(['id', 'name', 'guard_name']);

        if ($roleSearch !== '') {
            $prefix = addcslashes($roleSearch, '\\%_');
            $query->whereLike('name', $prefix.'%');
        }

        $matches = $query->orderBy('name')
            ->orderBy('id')
            ->limit(101)
            ->get();
        $roles = $matches->take(100)->values();
        $selectedRole = null;

        if ($roleId !== null) {
            $selectedRole = $matches->firstWhere('id', $roleId)
                ?? Role::on($actor->getConnectionName())
                    ->assignableBy($actor)
                    ->findOrFail($roleId, ['id', 'name', 'guard_name']);
            $actorRoles = $actor->relationLoaded('roles') ? $actor->getRelation('roles') : new Collection;
            $actorRole = $actorRoles->find($selectedRole->getKey());

            if ($actorRole?->relationLoaded('permissions')) {
                $selectedRole->setRelation('permissions', $actorRole->getRelation('permissions'));
            }

            (new Collection([$selectedRole, ...$actorRoles->all()]))->loadMissing('permissions:id,name,guard_name');
        }

        if ($selectedRole && ! $roles->contains('id', $selectedRole->getKey())) {
            $roles->push($selectedRole);
        }

        $permissions = Permission::on($actor->getConnection()->getName())
            ->where('guard_name', 'web')
            ->whereIn('name', $actor->permissionNames())
            ->orderBy('name')
            ->limit(501)
            ->get(['id', 'name', 'guard_name']);

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
        $actor->assertApplicationContext();

        if (! $record instanceof Role || ! $record->exists) {
            throw new AuthorizationException;
        }

        $role = $record;
        $permissionIds = $values['permissions'];
        Gate::forUser($actor)
            ->authorize('managePermissions', $role);

        return $actor->withAccessLock(function (Account $freshActor) use ($role, $permissionIds): Role {
            $connection = $freshActor->getConnection()->getName();
            $record = Role::on($connection)
                ->where('guard_name', 'web')
                ->whereKey($role->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            Gate::forUser($freshActor)
                ->authorize('managePermissions', $record);

            $permissions = Permission::on($connection)
                ->where('guard_name', 'web')
                ->whereIn('id', $permissionIds)
                ->get(['id', 'name', 'guard_name']);

            if ($permissions->count() !== count($permissionIds)) {
                throw ValidationException::withMessages(['permissions' => 'Select permissions from the current application.']);
            }

            if (! $freshActor->canGrantPermissions($permissions)) {
                throw ValidationException::withMessages(['permissions' => 'You may grant only permissions you hold yourself.']);
            }

            // Reuse policy-loaded permissions; owner authorization may not have loaded them.
            $record->loadMissing('permissions:id,name,guard_name');
            $currentPermissions = $record->permissions;
            $removedPermissions = $currentPermissions->diff($permissions);
            $addedPermissions = $permissions->diff($currentPermissions);

            if ($removedPermissions->isNotEmpty()) {
                $record->revokePermissionTo($removedPermissions);
            }
            if ($addedPermissions->isNotEmpty()) {
                $record->givePermissionTo($addedPermissions);
            }
            $record->getConnection()->afterCommit(fn () => app(PermissionRegistrar::class)->forgetCachedPermissions());

            return $record->load('permissions:id,name,guard_name');
        });
    }
}
