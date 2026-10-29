<?php

namespace Modules\Hospital\Policies;

use Modules\Core\Models\Account;
use Modules\Core\Services\AccessManager;
use Modules\Hospital\Models\Doctor;

class DoctorPolicy
{
    public function __construct(private AccessManager $access) {}

    public function viewAny(Account $actor): bool
    {
        return $this->permitted($actor, 'view');
    }

    public function view(Account $actor, Doctor $doctor): bool
    {
        return $this->permitted($actor, 'view') && $this->matchesContext($actor, $doctor);
    }

    public function create(Account $actor, ?Doctor $doctor = null): bool
    {
        if (! $this->permitted($actor, 'create')) {
            return false;
        }
        if ($doctor === null) {
            return true;
        }

        return (int) $doctor->branch_id === (int) request()->attributes->get('branch_id', 0)
            && $doctor->getConnection()->getName() === $actor->getConnection()->getName()
            && $doctor->getConnection()->getDatabaseName() === $actor->getConnection()->getDatabaseName();
    }

    public function update(Account $actor, Doctor $doctor): bool
    {
        return $this->permitted($actor, 'update') && $this->canManageTarget($actor, $doctor);
    }

    public function delete(Account $actor, Doctor $doctor): bool
    {
        return ! $actor->is($doctor) && $this->permitted($actor, 'delete') && $this->canManageTarget($actor, $doctor);
    }

    public function viewPermissions(Account $actor): bool
    {
        if (! $this->access->actorMatchesContext($actor) || (int) request()->attributes->get('branch_id', 0) < 1) {
            return false;
        }
        if ($this->access->isOwner($actor)) {
            return true;
        }

        return $actor->can('doctor-permissions.view')
            && $this->access->permissionsWithinActor($actor, (new Doctor)->getAllPermissions());
    }

    public function managePermissions(Account $actor): bool
    {
        return $this->viewPermissions($actor)
            && ($this->access->isOwner($actor) || $actor->can('doctor-permissions.manage'));
    }

    private function canManageTarget(Account $actor, Doctor $doctor): bool
    {
        if (! $this->matchesContext($actor, $doctor) || $doctor->isDirty('branch_id')) {
            return false;
        }

        return $this->access->isOwner($actor) || $this->access->permissionsWithinActor($actor, $doctor->getAllPermissions());
    }

    private function matchesContext(Account $actor, Doctor $doctor): bool
    {
        return $doctor->exists && (int) $doctor->branch_id === (int) request()->attributes->get('branch_id', 0)
            && $doctor->getConnection()->getName() === $actor->getConnection()->getName()
            && $doctor->getConnection()->getDatabaseName() === $actor->getConnection()->getDatabaseName();
    }

    private function permitted(Account $actor, string $action): bool
    {
        if (! $this->access->actorMatchesContext($actor) || (int) request()->attributes->get('branch_id', 0) < 1) {
            return false;
        }
        if ($this->access->isOwner($actor)) {
            return true;
        }

        return $actor->can('hospital.view') && $actor->can('doctors.'.$action);
    }
}
