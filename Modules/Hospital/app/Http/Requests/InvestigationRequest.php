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
            'single_rate' => ['required', 'string', 'decimal:0,2', 'between:0,9999999999.99'],
            'both_rate' => ['required', 'string', 'decimal:0,2', 'between:0,9999999999.99'],
            'room_no' => ['nullable', 'string', 'max:50'],
            'ordering' => ['required', 'integer', 'between:0,65535'],
        ];
    }
}
