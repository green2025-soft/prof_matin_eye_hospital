<?php

namespace Modules\Core\Policies;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\Account;
use Modules\Core\Models\Admin;
use Modules\Core\Services\AccessManager;

abstract class PlatformPolicy
{
    protected string $permission;

    public function __construct(protected AccessManager $access) {}

    public function before(Account $actor): ?bool
    {
        return $this->access->actorMatchesContext($actor) ? null : false;
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
        return $actor instanceof Admin && $this->access->actorMatchesContext($actor)
            && ($this->access->isOwner($actor) || $actor->can($this->permission.'.'.$action));
    }
}
