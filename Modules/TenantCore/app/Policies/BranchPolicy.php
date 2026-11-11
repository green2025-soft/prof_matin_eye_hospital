<?php

namespace Modules\TenantCore\Policies;

use Modules\Core\Models\Account;
use Modules\Core\Models\Tenant;
use Modules\Core\Services\AccessManager;
use Modules\TenantCore\Models\Branch;

class BranchPolicy
{
    public function __construct(private readonly AccessManager $access) {}

    public function before(Account $actor): ?bool
    {
        return $this->access->actorMatchesContext($actor) && $actor->getConnectionName() === 'tenant' ? null : false;
    }

    public function selectAny(Account $actor): bool
    {
        return Tenant::current()?->has_branches && $actor->branch_id === null;
    }

    public function select(Account $actor, Branch $branch): bool
    {
        return $this->selectAny($actor)
            && ! $branch->is_default
            && $branch->getConnection()->getName() === $actor->getConnection()->getName();
    }
}
