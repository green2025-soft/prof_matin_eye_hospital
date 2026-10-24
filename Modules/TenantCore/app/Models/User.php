<?php

namespace Modules\TenantCore\Models;

use Modules\Core\Models\Account;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class User extends Account
{
    use UsesTenantConnection;
}
