<?php

namespace Modules\Core\Services;

use Modules\Core\Enums\AccessPermission;
use Modules\Core\Enums\PlatformPermission;
use Modules\Core\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionCatalog
{
    public function sync(bool $platform = false): void
    {
        $catalog = AccessPermission::cases();

        if ($platform) {
            $catalog = array_merge($catalog, PlatformPermission::cases());
        }

        $permissions = [];

        foreach ($catalog as $permission) {
            $permissions[] = Permission::findOrCreate($permission->value, 'web');
        }

        Role::findOrCreate(Role::OWNER_NAME, 'web')->givePermissionTo($permissions);
    }
}
