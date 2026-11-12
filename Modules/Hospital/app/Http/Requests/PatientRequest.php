<?php

namespace Modules\Hospital\Http\Requests;

use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Modules\Core\Http\Requests\BaseRequest;

class PatientRequest extends BaseRequest
{
    protected function prepareForValidation(): void
    {
        parent::prepareForValidation();

        // Validate the complete geography chain when a partial update changes a parent.
        if ($record = $this->record()) {
            $this->mergeIfMissing($record->only(['division_id', 'district_id', 'thana_id']));
        }
    }

    public function rules(): array
    {
        $divisionId = $this->integer('division_id');
        $districtId = $this->integer('district_id');
        $ageInput = $this->input('age_input');
        $ageRequired = (! $this->isUpdate() || $ageInput === 'age')
            && ! $this->filled('age_months') && ! $this->filled('age_days');

        return [
            'name' => ['required', 'string', 'max:255'],
            'father_husband_name' => ['nullable', 'string', 'max:255'],
            'age_input' => ['nullable', Rule::in(['age', 'date_of_birth'])],
            'age' => [
                'exclude_if:age_input,date_of_birth',
                Rule::requiredIf($ageRequired),
                'nullable', 'integer', 'between:0,150',
            ],
            'age_months' => ['exclude_if:age_input,date_of_birth', 'nullable', 'integer', 'between:0,11'],
            'age_days' => ['exclude_if:age_input,date_of_birth', 'nullable', 'integer', 'between:0,30'],
            'date_of_birth' => [
                'exclude_if:age_input,age', 'nullable', 'required_if:age_input,date_of_birth',
                'date_format:Y-m-d', 'before_or_equal:today',
                'after_or_equal:'.Carbon::today()->subYears(150)->toDateString(),
            ],
            'gender' => ['required', 'string', Rule::in(array_keys(genders()))],
            'blood_group' => ['nullable', 'string', Rule::in(array_keys(bloodGroups()))],
            'mobile_no' => ['nullable', 'string', 'max:30'],
            'division_id' => ['bail', 'nullable', 'required_with:district_id', 'integer', 'min:1', $this->existsIn('divisions')],
            'district_id' => ['bail', 'nullable', 'required_with:thana_id', 'integer', 'min:1', $this->existsIn('districts')->where('division_id', $divisionId)],
            'thana_id' => ['bail', 'nullable', 'integer', 'min:1', $this->existsIn('thanas')->where('district_id', $districtId)],
            'address' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function payload(): array
    {
        $values = $this->validated();
        $ageInput = $values['age_input'] ?? null;
        unset($values['age_input']);

        if (filled($values['date_of_birth'] ?? null)) {
            $age = Carbon::parse($values['date_of_birth'])->diff(Carbon::today());
            $values['age'] = $age->y;
            $values['age_months'] = $age->m;
            $values['age_days'] = $age->d;
        } elseif ($ageInput === 'age') {
            $values['age'] = (int) ($values['age'] ?? 0);
            $values['age_months'] = (int) ($values['age_months'] ?? 0);
            $values['age_days'] = (int) ($values['age_days'] ?? 0);
            $values['date_of_birth'] = null;
        }

        return $values;
    }
}
