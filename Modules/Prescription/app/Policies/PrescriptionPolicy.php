<?php

namespace Modules\Prescription\Policies;

use Modules\Core\Models\Account;
use Modules\Hospital\Models\Doctor;
use Modules\Prescription\Models\GlassPrescription;
use Modules\Prescription\Models\Prescription;

class PrescriptionPolicy
{
    public function update(Account $actor, Prescription|GlassPrescription $prescription): bool
    {
        return $prescription->allowsCrud($actor, 'update')
            && (! $actor instanceof Doctor || (int) $prescription->doctor_id === (int) $actor->getKey());
    }
}
