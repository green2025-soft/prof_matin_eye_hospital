<?php

namespace Modules\Hospital\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Core\Http\Requests\BaseRequest;

class DoctorRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return $this->routeIs('hospital.doctors.permissions', 'hospital.doctors.permissions-update')
            || parent::authorize();
    }

    public function rules(): array
    {
        if ($this->routeIs('hospital.doctors.permissions')) {
            return [];
        }

        if ($this->routeIs('hospital.doctors.permissions-update')) {
            return [
                'permissions_present' => ['required', 'in:1'],
                'permissions' => ['present', 'array', 'max:500'],
                'permissions.*' => ['required', 'integer', 'min:1', 'distinct'],
            ];
        }

        $feeRules = ['required', 'string', 'decimal:0,2', 'between:0,9999999999.99'];

        return [
            'name' => ['required', 'string', 'max:255'],
            'bn_name' => ['nullable', 'string', 'max:255'],
            'mobile_no' => ['required', 'string', 'max:30'],
            'designation' => ['nullable', 'string', 'max:255'],
            'department_id' => ['bail', 'required', 'integer', 'min:1', $this->existsIn('departments')],
            'specialist' => ['nullable', 'string', 'max:255'],
            'bn_specialist' => ['nullable', 'string', 'max:255'],
            'bmdc_reg' => ['nullable', 'string', 'max:100'],
            'bn_bmdc_reg' => ['nullable', 'string', 'max:100'],
            'gender' => ['required', 'string', Rule::in(array_keys(genders()))],
            'blood_group' => ['nullable', 'string', Rule::in(array_keys(bloodGroups()))],
            'address' => ['nullable', 'string', 'max:2000'],
            'education_degree' => ['nullable', 'string', 'max:2000'],
            'bn_education_degree' => ['nullable', 'string', 'max:2000'],
            'current_employment' => ['nullable', 'string', 'max:2000'],
            'bn_current_employment' => ['nullable', 'string', 'max:2000'],
            'consultation_fee' => $feeRules,
            'follow_up_fee' => $feeRules,
            'pad_footer_text' => ['nullable', 'string', 'max:4000'],
            'email' => ['bail', 'required', 'string', 'email:rfc', 'max:255', $this->unique('email')],
            'password' => [$this->onCreate('required', 'nullable'), 'string', 'min:'.config('auth.password_min_length', 5), 'max:4096'],
            'picture' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp,gif', 'extensions:png,jpg,jpeg,webp,gif', 'max:2048'],
        ];
    }

    public function validationData(): array
    {
        $data = parent::validationData();
        if ($this->routeIs('hospital.doctors.permissions-update')) {
            $data += ['permissions' => []];
        }

        return $data;
    }

    protected function prepareForValidation(): void
    {
        parent::prepareForValidation();

        if ($this->exists('email')) {
            $this->merge(['email' => normalizeEmail($this->input('email'))]);
        }
    }
}
