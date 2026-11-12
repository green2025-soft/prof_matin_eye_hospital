<?php

namespace Modules\Core\Policies;

use Modules\Core\Enums\AccessPermission;
use Modules\Core\Models\Account;
use Modules\Core\Models\Setting;

class SettingPolicy
{
    public function before(Account $actor): ?bool
    {
        return $actor->matchesApplication() ? null : false;
    }

    public function viewAny(Account $actor): bool
    {
        return request()->attributes->get('navigation_permissions', [])[AccessPermission::ViewSettings->value] ?? false;
    }

    public function update(Account $actor, Setting $setting): bool
    {
        return $setting->getConnection()->getName() === $actor->getConnection()->getName()
            && in_array($setting->group, Setting::allowedGroups($actor->getConnection()->getName()), true)
            && (request()->attributes->get('navigation_permissions', [])[AccessPermission::UpdateSettings->value] ?? false);
    }
}
