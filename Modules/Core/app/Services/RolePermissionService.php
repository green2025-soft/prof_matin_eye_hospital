<?php

namespace Modules\Core\Services;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Modules\Core\Models\Account;
use Modules\Core\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionService
{
    public function __construct(private AccessManager $access) {}

    public function pageData(Account $actor, ?int $roleId = null, string $roleSearch = ''): array
    {
        $this->access->assertActorContext($actor);
        Gate::forUser($actor)->authorize('viewPermissions', Role::class);
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
            'areas' => $this->areas($permissions),
            'selectedPermissions' => $selectedRole?->permissions->modelKeys() ?? [],
            'permissionCount' => $permissions->count(),
            'canManagePermissions' => $selectedRole !== null && Gate::forUser($actor)->allows('managePermissions', $selectedRole),
            'pageTitle' => 'Permissions',
        ];
    }

    public function sync(Account $actor, Role $role, array $permissionIds): Role
    {
        $this->access->assertActorContext($actor);
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

    private function areas(iterable $permissions): array
    {
        $groups = config('permissions.groups', []);
        $actions = config('permissions.actions', []);
        $areas = [];

        foreach ($permissions as $permission) {
            $resource = Str::beforeLast($permission->name, '.');
            $action = Str::afterLast($permission->name, '.');
            $group = $groups[$resource] ?? [];
            $areaLabel = $group['area'] ?? 'General';
            $areaKey = Str::slug($areaLabel);
            $areas[$areaKey]['key'] = $areaKey;
            $areas[$areaKey]['label'] = $areaLabel;
            $areas[$areaKey]['groups'][$resource]['key'] = $resource;
            $areas[$areaKey]['groups'][$resource]['label'] = $group['label'] ?? Str::headline($resource);
            $areas[$areaKey]['groups'][$resource]['permissions'][] = [
                'id' => $permission->getKey(),
                'name' => $permission->name,
                'label' => $actions[$action] ?? Str::headline($action),
            ];
        }

        foreach ($areas as $key => $area) {
            $areas[$key]['groups'] = array_values($area['groups']);
        }

        return array_values($areas);
    }
}
