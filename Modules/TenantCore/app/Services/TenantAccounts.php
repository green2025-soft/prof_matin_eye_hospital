<?php

namespace Modules\TenantCore\Services;

use Illuminate\Contracts\Auth\Authenticatable;
use Modules\Core\Contracts\TenantAccounts as TenantAccountsContract;
use Modules\Core\Models\Role;
use Modules\Core\Models\Setting;
use Modules\Core\Services\PermissionCatalog;
use Modules\TenantCore\Models\User;

class TenantAccounts implements TenantAccountsContract
{
    public function __construct(private PermissionCatalog $permissions) {}

    public function ownerForLogin(): Authenticatable
    {
        return User::role(Role::OWNER_NAME, 'web')->orderBy('id')->firstOrFail();
    }

    public function ownerExists(string $email): bool
    {
        return (new User)->getConnection()->getSchemaBuilder()->hasTable('users')
            && User::query()->where('email', normalizeEmail($email))->exists();
    }

    public function createOwner(string $name, string $email, string $password): void
    {
        $this->permissions->sync();
        Setting::initialize('tenant');
        User::createOwner($name, $email, $password);
    }

    public function adoptOwner(string $email): void
    {
        $this->permissions->sync();
        Setting::initialize('tenant');
        User::query()->where('email', normalizeEmail($email))->firstOrFail()->assignOwnerRole();
    }
}
