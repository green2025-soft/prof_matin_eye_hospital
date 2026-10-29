<?php

namespace Modules\TenantCore\Models;

use Modules\Core\Models\BaseModel as CoreBaseModel;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

abstract class BaseModel extends CoreBaseModel
{
    use UsesTenantConnection;
}
