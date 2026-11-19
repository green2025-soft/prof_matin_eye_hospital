<?php

namespace Modules\Prescription\Policies;

use Modules\Core\Models\Account;
use Modules\Hospital\Models\Doctor;
use Modules\Prescription\Models\GlassPrescription;
use Modules\Prescription\Models\Prescription;

class PrescriptionPolicy
{
    public function view(Account $actor, Prescription|GlassPrescription $prescription): bool
    {
        return $prescription->allowsCrud($actor, 'view')
            && $this->belongsToDoctor($actor, $prescription);
    }

    public function update(Account $actor, Prescription|GlassPrescription $prescription): bool
    {
        return $prescription->allowsCrud($actor, 'update')
            && $this->belongsToDoctor($actor, $prescription);
    }

    public function delete(Account $actor, Prescription|GlassPrescription $prescription): bool
    {
        return $prescription->allowsCrud($actor, 'delete')
            && $this->belongsToDoctor($actor, $prescription);
    }

    private function belongsToDoctor(Account $actor, Prescription|GlassPrescription $prescription): bool
    {
        return ! $actor instanceof Doctor
            || (int) $prescription->doctor_id === (int) $actor->getKey();
    }
}
