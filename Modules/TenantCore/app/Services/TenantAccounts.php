<?php

namespace Modules\TenantCore\Services;

use Illuminate\Contracts\Auth\Authenticatable;
use Modules\Core\Contracts\TenantAccounts as TenantAccountsContract;
use Modules\Core\Models\Role;
use Modules\Core\Models\Setting;
use Modules\Core\Services\PermissionCatalog;
use Modules\TenantCore\Models\Branch;
use Modules\TenantCore\Models\PaymentType;
use Modules\TenantCore\Models\User;

class TenantAccounts implements TenantAccountsContract
{
    public function __construct(private PermissionCatalog $permissions) {}

    public function ownerForLogin(): Authenticatable
    {
        return User::role(Role::OWNER_NAME, 'web')
            ->orderBy('id')
            ->firstOrFail();
    }

    public function ownerExists(string $email): bool
    {
        $hasUsersTable = (new User)->getConnection()
            ->getSchemaBuilder()
            ->hasTable('users');

        return $hasUsersTable && User::where('email', normalizeEmail($email))
            ->exists();
    }

    public function createOwner(string $name, string $email, string $password): void
    {
        $this->permissions->sync();
        Setting::initialize('tenant');
        PaymentType::firstOrCreate(['name' => 'Cash'], ['ordering' => 0]);
        User::createOwner($name, $email, $password);
    }

    public function adoptOwner(string $email): void
    {
        $this->permissions->sync();
        Setting::initialize('tenant');
        User::where('email', normalizeEmail($email))
            ->firstOrFail()
            ->assignOwnerRole();
    }

    public function ensureDefaultBranch(string $name, string $address = ''): void
    {
        if (Branch::where('is_default', true)
            ->exists()) {
            return;
        }

        Branch::forceCreate([
            'name' => $name,
            'address' => $address,
            'is_default' => true,
        ]);
    }
}
