<?php

namespace Modules\Core\Policies;

use Modules\Core\Enums\AccessPermission;
use Modules\Core\Models\Account;
use Modules\Core\Models\Admin;
use Modules\Core\Services\AccessManager;

class AccountPolicy
{
    public function __construct(private AccessManager $access) {}

    public function viewAny(Account $actor): bool
    {
        return $this->permitted($actor, AccessPermission::ViewAccounts);
    }

    public function create(Account $actor): bool
    {
        return $this->permitted($actor, AccessPermission::CreateAccounts);
    }

    public function update(Account $actor, Account $account): bool
    {
        return $this->permitted($actor, AccessPermission::UpdateAccounts)
            && $this->canManageTarget($actor, $account);
    }

    public function delete(Account $actor, Account $account): bool
    {
        return ! $actor->is($account)
            && $this->permitted($actor, AccessPermission::DeleteAccounts)
            && $this->canManageTarget($actor, $account);
    }

    public function assignRoles(Account $actor, ?Account $account = null): bool
    {
        return $this->permitted($actor, AccessPermission::AssignRoles)
            && ($account === null || $this->canManageTarget($actor, $account));
    }

    private function canManageTarget(Account $actor, Account $account): bool
    {
        $model = Account::modelForProvider($actor instanceof Admin ? 'admins' : 'users');
        if (get_class($account) !== $model || ! $this->access->actorMatchesContext($account)) {
            return false;
        }

        return $this->access->isOwner($actor)
            || (! $this->access->isOwner($account)
                && $this->access->permissionsWithinActor($actor, $account->getAllPermissions()));
    }

    private function permitted(Account $actor, AccessPermission $permission): bool
    {
        return $this->access->actorMatchesContext($actor)
            && ($this->access->isOwner($actor)
                || $this->access->permissionNames($actor)->contains($permission->value));
    }
}
