<?php

namespace Modules\Hospital\Http\Requests;

use Modules\Core\Http\Requests\BaseRequest;

class InvestigationRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', $this->unique('name')],
            'category_id' => ['required', 'integer', 'min:1', $this->existsIn('investigation_categories')],
            'single_rate' => ['required', 'string', 'regex:/\A\d{1,10}(?:\.\d{1,2})?\z/'],
            'both_rate' => ['required', 'string', 'regex:/\A\d{1,10}(?:\.\d{1,2})?\z/'],
            'room_no' => ['nullable', 'string', 'max:50'],
            'ordering' => ['required', 'integer', 'between:0,65535'],
        ];
    }
}
