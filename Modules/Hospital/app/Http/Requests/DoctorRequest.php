<?php

namespace Modules\Hospital\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Modules\Core\Http\Requests\BaseRequest;
use Modules\Hospital\Models\Doctor;

class DoctorRequest extends BaseRequest
{
    public function authorize(): bool
    {
        if ($this->routeIs('hospital.doctors.permissions')) {
            return Gate::allows('viewPermissions', Doctor::class);
        }

        if ($this->routeIs('hospital.doctors.permissions-update')) {
            return Gate::allows('managePermissions', Doctor::class);
        }

        return parent::authorize();
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

        $feeRules = ['required', 'string', 'regex:/\A\d{1,10}(?:\.\d{1,2})?\z/'];

        return [
            'name' => ['required', 'string', 'max:255'],
            'bn_name' => ['nullable', 'string', 'max:255'],
            'mobile_no' => ['required', 'string', 'max:30', 'regex:/\A(?=.*[0-9])[0-9+() .-]+\z/'],
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
            'picture' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp,gif', 'extensions:png,jpg,jpeg,webp,gif', 'mimetypes:image/png,image/jpeg,image/webp,image/gif', 'max:2048'],
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
