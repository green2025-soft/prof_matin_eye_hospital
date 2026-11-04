<?php

namespace Modules\Prescription\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Core\Http\Requests\BaseRequest;
use Modules\Core\Models\Setting;
use Modules\Hospital\Models\Doctor;
use Modules\Prescription\Models\GlassPrescription;

class GlassPrescriptionRequest extends BaseRequest
{
    protected bool $partialUpdates = false;

    protected function prepareForValidation(): void
    {
        parent::prepareForValidation();

        $actor = $this->user('web');
        if ($actor instanceof Doctor) {
            $this->merge(['doctor_id' => $actor->getKey()]);
        }

        $this->mergeIfMissing(['lens_options' => []]);
    }

    public function rules(): array
    {
        $branchId = max(0, (int) $this->attributes->get('branch_id', 0));
        $settings = Setting::values('prescription');

        return [
            'patient_id' => ['bail', 'required', 'integer', 'min:1', $this->existsIn('patients')->where('branch_id', $branchId)],
            'doctor_id' => ['bail', 'required', 'integer', 'min:1', $this->existsIn('doctors')->where('branch_id', $branchId)],
            'prescription_date' => ['required', 'date_format:Y-m-d'],
            'pd' => [Rule::excludeIf(! $settings['glass_pd_enabled']), 'nullable', 'string', 'max:30'],
            'disclaimer' => [Rule::excludeIf(! $settings['glass_disclaimer_enabled']), 'nullable', 'string', 'max:2000'],
            'lens_options' => [Rule::excludeIf(! $settings['glass_lens_options_enabled']), 'nullable', 'array', 'max:5'],
            'lens_options.*' => [Rule::excludeIf(! $settings['glass_lens_options_enabled']), 'string', 'distinct', Rule::in(array_keys(GlassPrescription::lensOptions()))],
            'r_sphere_dv' => ['nullable', 'string', 'max:30'],
            'r_cylinder_dv' => ['nullable', 'string', 'max:30'],
            'r_axis_dv' => ['nullable', 'string', 'max:30'],
            'r_acuity_dv' => ['nullable', 'string', 'max:30'],
            'r_sphere_nv' => ['nullable', 'string', 'max:30'],
            'r_cylinder_nv' => ['nullable', 'string', 'max:30'],
            'r_axis_nv' => ['nullable', 'string', 'max:30'],
            'r_acuity_nv' => ['nullable', 'string', 'max:30'],
            'l_sphere_dv' => ['nullable', 'string', 'max:30'],
            'l_cylinder_dv' => ['nullable', 'string', 'max:30'],
            'l_axis_dv' => ['nullable', 'string', 'max:30'],
            'l_acuity_dv' => ['nullable', 'string', 'max:30'],
            'l_sphere_nv' => ['nullable', 'string', 'max:30'],
            'l_cylinder_nv' => ['nullable', 'string', 'max:30'],
            'l_axis_nv' => ['nullable', 'string', 'max:30'],
            'l_acuity_nv' => ['nullable', 'string', 'max:30'],
        ];
    }
}
