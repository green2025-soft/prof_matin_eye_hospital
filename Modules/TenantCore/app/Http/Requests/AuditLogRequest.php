<?php

namespace Modules\TenantCore\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Core\Http\Requests\ListRequest;

class AuditLogRequest extends ListRequest
{
    public function rules(): array
    {
        return parent::rules() + [
            'branch_id' => ['bail', 'nullable', 'integer', 'min:1'],
            'date_from' => ['bail', 'nullable', 'date_format:Y-m-d'],
            'date_to' => ['bail', 'nullable', 'date_format:Y-m-d', Rule::when($this->filled('date_from'), ['after_or_equal:date_from'])],
        ];
    }
}
