<?php

namespace Modules\Core\Policies;

use Modules\Core\Enums\AccessPermission;
use Modules\Core\Models\Account;
use Modules\Core\Models\Role;

class RolePolicy
{
    public function before(Account $actor): ?bool
    {
        return $actor->matchesApplication() ? null : false;
    }

    public function viewAny(Account $actor): bool
    {
        return request()->attributes->get('navigation_permissions', [])[AccessPermission::ViewRoles->value] ?? false;
    }

    public function create(Account $actor): bool
    {
        return request()->attributes->get('navigation_permissions', [])[AccessPermission::CreateRoles->value] ?? false;
    }

    public function update(Account $actor, Role $role): bool
    {
        return (request()->attributes->get('navigation_permissions', [])[AccessPermission::UpdateRoles->value] ?? false)
            && $role->canBeManagedBy($actor);
    }

    public function delete(Account $actor, Role $role): bool
    {
        return (request()->attributes->get('navigation_permissions', [])[AccessPermission::DeleteRoles->value] ?? false)
            && $role->canBeManagedBy($actor)
            && ! $role->hasAssignedAccounts();
    }

    public function viewPermissions(Account $actor, ?Role $role = null): bool
    {
        $owner = $actor->isOwner();

        if (! (request()->attributes->get('navigation_permissions', [])[AccessPermission::ViewPermissions->value] ?? false)) {
            return false;
        }

        return $role === null || ($role->guard_name === 'web'
            && $role->getConnection()->getName() === $actor->getConnection()->getName()
            && ($owner || $role->canBeManagedBy($actor)));
    }

    public function managePermissions(Account $actor, Role $role): bool
    {
        return $this->viewPermissions($actor)
            && (request()->attributes->get('navigation_permissions', [])[AccessPermission::ManagePermissions->value] ?? false)
            && $role->canBeManagedBy($actor);
    }
}
