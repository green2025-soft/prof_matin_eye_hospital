<?php

namespace Modules\Hospital\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Core\Http\Requests\BaseRequest;
use Modules\Core\Models\Setting;

class ConsultationRequest extends BaseRequest
{
    protected bool $partialUpdates = false;

    public function rules(): array
    {
        $branchId = max(0, (int) $this->attributes->get('branch_id', 0));
        $referenceEnabled = (bool) Setting::values('hospital')['consultation_reference_enabled'];
        $moneyRules = ['required', 'string', 'regex:/\A\d{1,10}(?:\.\d{1,2})?\z/'];

        return [
            'reference_id' => $referenceEnabled
                ? ['bail', 'nullable', 'integer', 'min:1', $this->existsIn('references')->where('branch_id', $branchId)]
                : ['prohibited'],
            'patient_id' => ['bail', 'required', 'integer', 'min:1', $this->existsIn('patients')->where('branch_id', $branchId)],
            'doctor_id' => ['bail', 'required', 'integer', 'min:1', $this->existsIn('doctors')->where('branch_id', $branchId)],
            'appointment_id' => ['bail', 'nullable', 'integer', 'min:1', $this->existsIn('appointments')->where('branch_id', $branchId), $this->unique('appointment_id')],
            'entry_date' => ['required', 'date_format:Y-m-d'],
            'payment_type_id' => ['bail', 'required', 'integer', 'min:1', $this->existsIn('payment_types')],
            'patient_type' => ['required', 'string', Rule::in(array_keys(patientTypes()))],
            'discount_mode' => ['required', 'string', Rule::in(['percent', 'amount'])],
            'discount_percent' => ['required', 'numeric', 'regex:/\A\d{1,3}(?:\.\d{1,2})?\z/', 'between:0,100'],
            'discount_amount' => $moneyRules,
            'paid_amount' => $moneyRules,
            'due_date' => ['required', 'date_format:Y-m-d'],
        ];
    }
}
