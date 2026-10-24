<?php

namespace Modules\Core\Models;

use Modules\Core\Models\Concerns\HasCrudContract;
use Spatie\Permission\Models\Role as BaseRole;

class Role extends BaseRole
{
    use HasCrudContract;

    public const OWNER_NAME = 'super-admin';

    protected array $queryConstraints = ['guard_name' => 'web'];

    protected array $exposed = ['id', 'name', 'guard_name', 'created_at'];

    protected array $listWith = ['permissions:id,name,guard_name'];

    protected array $exposedRelations = ['permissions' => ['id', 'name', 'guard_name']];

    public function isReserved(): bool
    {
        return strcasecmp($this->name ?? '', self::OWNER_NAME) === 0;
    }
}
