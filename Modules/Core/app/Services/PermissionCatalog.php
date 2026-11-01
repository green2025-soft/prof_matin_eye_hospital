<?php

namespace Modules\Core\Services;

use Illuminate\Support\Str;
use Modules\Core\Enums\AccessPermission;
use Modules\Core\Enums\PlatformPermission;
use Modules\Core\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionCatalog
{
    public static function tenantPermissions(): array
    {
        return array_values(array_unique(array_merge(
            array_values(config('permissions.tenant_routes', [])),
            config('permissions.tenant_permissions', []),
        )));
    }

    public function areas(array $permissions): array
    {
        $groups = config('permissions.groups', []);
        $actions = config('permissions.actions', []);
        $areas = [];

        foreach ($permissions as $id => $name) {
            $resource = Str::beforeLast($name, '.');
            $action = Str::afterLast($name, '.');
            $group = $groups[$resource] ?? [];
            $areaLabel = $group['area'] ?? 'General';
            $areaKey = Str::slug($areaLabel);
            $areas[$areaKey]['key'] = $areaKey;
            $areas[$areaKey]['label'] = $areaLabel;
            $areas[$areaKey]['groups'][$resource]['key'] = $resource;
            $areas[$areaKey]['groups'][$resource]['label'] = $group['label'] ?? Str::headline($resource);
            $areas[$areaKey]['groups'][$resource]['permissions'][] = [
                'id' => $id,
                'name' => $name,
                'label' => $actions[$action] ?? Str::headline($action),
            ];
        }

        return array_values($areas);
    }

    public function sync(bool $platform = false): void
    {
        $catalog = array_column(AccessPermission::cases(), 'value');

        if ($platform) {
            $catalog = array_merge($catalog, array_column(PlatformPermission::cases(), 'value'));
        } else {
            $catalog = array_merge($catalog, static::tenantPermissions());
        }

        $permissions = [];

        foreach (array_unique($catalog) as $permission) {
            $permissions[] = Permission::findOrCreate($permission, 'web');
        }

        Role::findOrCreate(Role::OWNER_NAME, 'web')->givePermissionTo($permissions);
    }
}
