<?php

namespace Modules\Core\Policies;

use Modules\Core\Enums\PlatformPermission;
use Modules\Core\Models\Account;
use Modules\Core\Models\Admin;
use Modules\Core\Models\Tenant;

class TenantPolicy
{
    public function before(Account $account): ?bool
    {
        return $account instanceof Admin && $account->matchesApplication() ? null : false;
    }

    public function viewAny(Account $account): bool
    {
        return $this->permitted(PlatformPermission::ViewTenants);
    }

    public function create(Account $account): bool
    {
        return $this->permitted(PlatformPermission::CreateTenants);
    }

    public function update(Account $account, Tenant $tenant): bool
    {
        return $tenant->status !== Tenant::STATUS_DELETING && $this->permitted(PlatformPermission::UpdateTenants);
    }

    public function enter(Account $account, Tenant $tenant): bool
    {
        return $tenant->status === Tenant::STATUS_ACTIVE && $this->permitted(PlatformPermission::EnterTenants);
    }

    public function delete(Account $account, Tenant $tenant): bool
    {
        return $this->permitted(PlatformPermission::DeleteTenants);
    }

    private function permitted(PlatformPermission $permission): bool
    {
        return request()->attributes->get('navigation_permissions', [])[$permission->value] ?? false;
    }
}
