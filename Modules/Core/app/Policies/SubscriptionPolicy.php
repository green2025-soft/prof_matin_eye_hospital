<?php

namespace Modules\Core\Policies;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\Account;

class SubscriptionPolicy extends PlatformPolicy
{
    protected string $permission = 'subscriptions';

    public function delete(Account $actor, Model $record): bool
    {
        return false;
    }
}
