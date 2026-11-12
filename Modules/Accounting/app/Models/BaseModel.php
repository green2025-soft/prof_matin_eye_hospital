<?php

namespace Modules\Accounting\Models;

use Closure;
use Modules\Core\Models\Account;
use Modules\Core\Models\BaseModel as CoreBaseModel;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

abstract class BaseModel extends CoreBaseModel
{
    use UsesTenantConnection;

    public function writeTransaction(Account $actor, Closure $operation): mixed
    {
        return $actor->withAccessLock($operation);
    }
}
