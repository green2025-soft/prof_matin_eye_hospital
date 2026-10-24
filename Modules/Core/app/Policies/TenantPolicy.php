<?php

namespace Modules\Core\Policies;

use Modules\Core\Enums\PlatformPermission;
use Modules\Core\Models\Account;
use Modules\Core\Models\Admin;
use Modules\Core\Models\Tenant;
use Modules\Core\Services\AccessManager;

class TenantPolicy
{
    public function __construct(private AccessManager $access) {}

    public function before(Account $account): ?bool
    {
        return $account instanceof Admin && $this->access->actorMatchesContext($account) ? null : false;
    }

    public function viewAny(Account $account): bool
    {
        return $this->permitted($account, PlatformPermission::ViewTenants);
    }

    public function create(Account $account): bool
    {
        return $this->permitted($account, PlatformPermission::CreateTenants);
    }

    public function update(Account $account, Tenant $tenant): bool
    {
        return $this->permitted($account, PlatformPermission::UpdateTenants);
    }

    private function permitted(Account $account, PlatformPermission $permission): bool
    {
        return $account->hasOwnerRole() || $account->can($permission->value);
    }
}
