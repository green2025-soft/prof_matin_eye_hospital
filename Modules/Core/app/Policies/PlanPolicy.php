<?php

namespace Modules\Core\Policies;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\Account;
use Modules\Core\Models\Plan;

class PlanPolicy extends PlatformPolicy
{
    protected string $permission = 'plans';

    public function delete(Account $actor, Model $record): bool
    {
        return $record instanceof Plan && $this->allowed($actor, 'delete')
            && ! $record->isDefault() && ! $record->hasSubscriptions();
    }
}
