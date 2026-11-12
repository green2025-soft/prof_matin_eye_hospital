<?php

namespace Modules\Hospital\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Core\Http\Requests\BaseRequest;
use Modules\Core\Models\Setting;

class PatientInvestigationRequest extends BaseRequest
{
    protected bool $partialUpdates = false;

    public function rules(): array
    {
        $branchId = max(0, (int) $this->attributes->get('branch_id', 0));
        $referenceEnabled = (bool) Setting::values('hospital')['investigation_reference_enabled'];
        $moneyRules = ['required', 'string', 'decimal:0,2', 'between:0,9999999999.99'];
        $percentRules = ['required', 'decimal:0,2', 'between:0,100'];

        return [
            'reference_id' => $referenceEnabled ? ['bail', 'nullable', 'integer', 'min:1', $this->existsIn('references')->where('branch_id', $branchId)] : ['prohibited'],
            'patient_id' => ['bail', 'required', 'integer', 'min:1', $this->existsIn('patients')->where('branch_id', $branchId)],
            'doctor_id' => ['bail', 'required', 'integer', 'min:1', $this->existsIn('doctors')->where('branch_id', $branchId)],
            'entry_date' => ['required', 'date_format:Y-m-d'],
            'payment_type_id' => ['bail', 'required', 'integer', 'min:1', $this->existsIn('payment_types')],
            'eye_type' => ['required', 'string', Rule::in(array_keys(eyeTypes()))],
            'investigation_ids' => ['required', 'array', 'min:1', 'max:100'],
            'investigation_ids.*' => ['bail', 'required', 'integer', 'min:1', 'distinct', $this->existsIn('investigations')],
            'investigation_eyes' => ['required', 'array', 'max:100'],
            'investigation_eyes.*' => ['required', Rule::in(array_keys(eyeTypes()))],
            'discount_mode' => ['required', 'string', Rule::in(['percent', 'amount'])],
            'discount_percent' => $percentRules,
            'discount_amount' => $moneyRules,
            'extra_discount_percent' => $percentRules,
            'paid_amount' => $moneyRules,
            'due_date' => ['required', 'date_format:Y-m-d'],
            'delivery_date' => ['required', 'date_format:Y-m-d'],
        ];
    }
}
