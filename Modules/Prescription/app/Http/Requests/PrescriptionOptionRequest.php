<?php

namespace Modules\Prescription\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Core\Http\Requests\BaseRequest;
use Modules\Prescription\Models\PrescriptionOption;

class PrescriptionOptionRequest extends BaseRequest
{
    public function rules(): array
    {
        $section = $this->input('section', $this->record()?->section);

        return [
            'section' => ['required', 'string', Rule::in(array_keys(PrescriptionOption::sections()))],
            'name' => [
                'bail', 'required', 'string', 'max:255',
                $this->unique('name')->where('section', is_string($section) ? $section : ''),
            ],
            'ordering' => ['sometimes', 'integer', 'between:0,65535'],
        ];
    }
}
