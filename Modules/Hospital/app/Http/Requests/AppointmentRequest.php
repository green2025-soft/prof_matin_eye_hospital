<?php

namespace Modules\Hospital\Http\Requests;

use Modules\Core\Http\Requests\BaseRequest;

class AppointmentRequest extends BaseRequest
{
    protected bool $partialUpdates = false;

    public function rules(): array
    {
        $branchId = max(0, (int) $this->attributes->get('branch_id', 0));

        return [
            'doctor_id' => ['bail', 'required', 'integer', 'min:1', $this->existsIn('doctors')->where('branch_id', $branchId)],
            'patient_id' => ['bail', 'required', 'integer', 'min:1', $this->existsIn('patients')->where('branch_id', $branchId)],
            'appointment_date' => ['required', 'date_format:Y-m-d'],
            'appointment_time' => ['nullable', 'date_format:H:i'],
        ];
    }
}
