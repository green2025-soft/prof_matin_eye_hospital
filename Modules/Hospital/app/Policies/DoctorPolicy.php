<?php

namespace Modules\Hospital\Policies;

use Modules\Core\Models\Account;
use Modules\Hospital\Models\Doctor;

class DoctorPolicy
{
    public function viewAny(Account $actor): bool
    {
        return $actor->matchesApplication();
    }

    public function view(Account $actor, Doctor $doctor): bool
    {
        return $this->matchesContext($actor, $doctor);
    }

    public function create(Account $actor, ?Doctor $doctor = null): bool
    {
        if (! $actor->matchesApplication()) {
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
        return $this->canManageTarget($actor, $doctor);
    }

    public function delete(Account $actor, Doctor $doctor): bool
    {
        return ! $actor->is($doctor) && $this->canManageTarget($actor, $doctor);
    }

    private function canManageTarget(Account $actor, Doctor $doctor): bool
    {
        if (! $this->matchesContext($actor, $doctor) || $doctor->isDirty('branch_id')) {
            return false;
        }

        return $actor->isOwner() || $actor->canGrantPermissions($doctor->getAllPermissions());
    }

    private function matchesContext(Account $actor, Doctor $doctor): bool
    {
        return $actor->matchesApplication() && $doctor->exists
            && (int) $doctor->branch_id === (int) request()->attributes->get('branch_id', 0)
            && $doctor->getConnection()->getName() === $actor->getConnection()->getName()
            && $doctor->getConnection()->getDatabaseName() === $actor->getConnection()->getDatabaseName();
    }
}
