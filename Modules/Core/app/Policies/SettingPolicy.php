<?php

namespace Modules\Core\Policies;

use Modules\Core\Enums\AccessPermission;
use Modules\Core\Models\Account;
use Modules\Core\Models\Setting;
use Modules\Core\Services\AccessManager;

class SettingPolicy
{
    public function __construct(private AccessManager $access) {}

    public function before(Account $actor): ?bool
    {
        return $this->access->actorMatchesContext($actor) ? null : false;
    }

    public function viewAny(Account $actor): bool
    {
        return $this->access->isOwner($actor) || $actor->can(AccessPermission::ViewSettings->value);
    }

    public function update(Account $actor, Setting $setting): bool
    {
        return $setting->getConnection()->getName() === $actor->getConnection()->getName()
            && in_array($setting->group, Setting::allowedGroups($actor->getConnection()->getName()), true)
            && ($this->access->isOwner($actor) || $actor->can(AccessPermission::UpdateSettings->value));
    }
}
