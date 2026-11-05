<?php

namespace Modules\Pharmacy\Http\Requests;

use Modules\Core\Http\Requests\BaseRequest;

class DosageRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'ordering' => ['required', 'integer', 'between:0,65535'],
        ];
    }
}
