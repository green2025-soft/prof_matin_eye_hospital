<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\Concerns\HasCrudContract;

abstract class BaseModel extends Model
{
    use HasCrudContract;

    protected string $crudPermission = '';

    public function crudPermission(): string
    {
        return $this->crudPermission;
    }

    public function matchesBranchContext(): bool
    {
        return true;
    }

    public function hasReferences(): bool
    {
        return false;
    }
}
