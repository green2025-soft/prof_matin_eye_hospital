<?php

namespace Modules\Hospital\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Core\Http\Requests\BaseRequest;
use Modules\Hospital\Models\Patient;

class PatientDueReportRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $branchId = (int) $this->attributes->get('branch_id', 0);
        $patient = new Patient;
        $patientTable = $patient->getConnection()->getName().'.'.$patient->getTable();

        return [
            'from_date' => ['nullable', 'date_format:Y-m-d'],
            'to_date' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:from_date'],
            'patient_id' => [
                'nullable',
                'integer',
                'min:1',
                Rule::exists($patientTable, 'id')->where('branch_id', $branchId),
            ],
        ];
    }
}
