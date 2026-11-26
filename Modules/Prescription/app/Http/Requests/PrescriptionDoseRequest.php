<?php

namespace Modules\Prescription\Http\Requests;

use Modules\Core\Http\Requests\BaseRequest;

class PrescriptionDoseRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => ['bail', 'required', 'string', 'max:255', $this->unique('name')],
        ];
    }
}
