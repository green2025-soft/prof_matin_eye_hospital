<?php

namespace Modules\Hospital\Http\Requests;

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
        $record = $this->record();
        $divisionId = $this->integer('division_id', $record?->division_id ?? 0);
        $districtId = $this->integer('district_id', $record?->district_id ?? 0);

        return [
            'name' => ['required', 'string', 'max:255'],
            'father_husband_name' => ['nullable', 'string', 'max:255'],
            'age' => ['required', 'integer', 'between:0,150'],
            'gender' => ['required', 'string', Rule::in(array_keys(genders()))],
            'blood_group' => ['nullable', 'string', Rule::in(array_keys(bloodGroups()))],
            'mobile_no' => ['required', 'string', 'max:30', 'regex:/\A(?=.*[0-9])[0-9+() .-]+\z/'],
            'division_id' => ['bail', 'nullable', 'required_with:district_id', 'integer', 'min:1', $this->existsIn('divisions')],
            'district_id' => ['bail', 'nullable', 'required_with:thana_id', 'integer', 'min:1', $this->existsIn('districts')->where('division_id', $divisionId)],
            'thana_id' => ['bail', 'nullable', 'integer', 'min:1', $this->existsIn('thanas')->where('district_id', $districtId)],
            'address' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
