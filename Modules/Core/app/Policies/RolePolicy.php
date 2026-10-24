<?php

namespace Modules\Core\Policies;

use Modules\Core\Enums\AccessPermission;
use Modules\Core\Models\Account;
use Modules\Core\Models\Role;
use Modules\Core\Services\AccessManager;

class RolePolicy
{
    public function __construct(private readonly AccessManager $access) {}

    public function before(Account $actor): ?bool
    {
        return $this->access->actorMatchesContext($actor) ? null : false;
    }

    public function viewAny(Account $actor): bool
    {
        return $this->access->isOwner($actor) || $actor->can(AccessPermission::ViewRoles->value);
    }

    public function create(Account $actor): bool
    {
        return $this->access->isOwner($actor) || $actor->can(AccessPermission::CreateRoles->value);
    }

    public function update(Account $actor, Role $role): bool
    {
        return ($this->access->isOwner($actor) || $actor->can(AccessPermission::UpdateRoles->value))
            && $this->access->roleWithinActor($actor, $role);
    }

    public function delete(Account $actor, Role $role): bool
    {
        return ($this->access->isOwner($actor) || $actor->can(AccessPermission::DeleteRoles->value))
            && $this->access->roleWithinActor($actor, $role);
    }

    public function viewPermissions(Account $actor): bool
    {
        return $this->access->isOwner($actor) || $actor->can(AccessPermission::ViewPermissions->value);
    }

    public function managePermissions(Account $actor, Role $role): bool
    {
        return $this->viewPermissions($actor)
            && ($this->access->isOwner($actor) || $actor->can(AccessPermission::ManagePermissions->value))
            && $this->access->roleWithinActor($actor, $role);
    }
}
