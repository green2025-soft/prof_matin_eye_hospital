<?php

namespace Modules\Core\Policies;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\Account;
use Modules\Core\Models\Admin;

abstract class PlatformPolicy
{
    protected string $permission;

    public function before(Account $actor): ?bool
    {
        return $actor->matchesApplication() ? null : false;
    }

    public function viewAny(Account $actor): bool
    {
        return $this->allowed($actor, 'view');
    }

    public function view(Account $actor, Model $record): bool
    {
        return $this->allowed($actor, 'view');
    }

    public function create(Account $actor): bool
    {
        return $this->allowed($actor, 'create');
    }

    public function update(Account $actor, Model $record): bool
    {
        return $this->allowed($actor, 'update');
    }

    public function delete(Account $actor, Model $record): bool
    {
        return $this->allowed($actor, 'delete');
    }

    protected function allowed(Account $actor, string $action): bool
    {
        return $actor instanceof Admin && $actor->matchesApplication()
            && (request()->attributes->get('navigation_permissions', [])[$this->permission.'.'.$action] ?? false);
    }
}
