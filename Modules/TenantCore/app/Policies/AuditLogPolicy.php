<?php

namespace Modules\TenantCore\Policies;

use Modules\Core\Models\Account;
use Modules\Core\Services\AccessManager;
use Modules\TenantCore\Models\AuditLog;

class AuditLogPolicy
{
    public function __construct(private AccessManager $access) {}

    public function viewAny(Account $actor): bool
    {
        return $actor->getConnectionName() === 'tenant' && $this->access->actorMatchesContext($actor)
            && ($this->access->isOwner($actor) || $actor->can('audit-logs.view'));
    }

    public function view(Account $actor, AuditLog $auditLog): bool
    {
        return $this->viewAny($actor) && $auditLog->exists
            && $auditLog->getConnection()->getName() === $actor->getConnection()->getName()
            && $auditLog->getConnection()->getDatabaseName() === $actor->getConnection()->getDatabaseName();
    }

    public function create(Account $actor): bool
    {
        return false;
    }

    public function update(Account $actor, AuditLog $auditLog): bool
    {
        return false;
    }

    public function delete(Account $actor, AuditLog $auditLog): bool
    {
        return false;
    }
}
