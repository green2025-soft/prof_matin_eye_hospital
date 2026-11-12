<?php

namespace Modules\Core\Policies;

use Modules\Core\Enums\AccessPermission;
use Modules\Core\Models\Account;
use Modules\Core\Models\Admin;

class AccountPolicy
{
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
        if (get_class($account) !== $model || ! $account->matchesApplication()) {
            return false;
        }

        return $actor->isOwner()
            || (! $account->isOwner() && $actor->canGrantPermissions($account->getAllPermissions()));
    }

    private function permitted(Account $actor, AccessPermission $permission): bool
    {
        return $actor->matchesApplication()
            && (request()->attributes->get('navigation_permissions', [])[$permission->value] ?? false);
    }
}
