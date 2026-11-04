<?php

namespace Modules\Prescription\Policies;

use Modules\Core\Models\Account;
use Modules\Core\Services\AccessManager;
use Modules\Hospital\Models\Doctor;
use Modules\Prescription\Models\GlassPrescription;
use Modules\Prescription\Models\Prescription;

class PrescriptionPolicy
{
    public function __construct(private AccessManager $access) {}

    public function update(Account $actor, Prescription|GlassPrescription $prescription): bool
    {
        return $this->access->allowsCrud($actor, $prescription, 'update')
            && (! $actor instanceof Doctor || (int) $prescription->doctor_id === (int) $actor->getKey());
    }
}
