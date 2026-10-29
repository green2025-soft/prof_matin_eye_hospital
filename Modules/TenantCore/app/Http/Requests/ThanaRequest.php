<?php

namespace Modules\TenantCore\Http\Requests;

use Modules\Core\Http\Requests\BaseRequest;

class ThanaRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'district_id' => ['required', 'integer', 'min:1', $this->existsIn('districts')],
            'name' => [
                'required', 'string', 'max:255',
                $this->unique('name')->where('district_id', $this->integer('district_id', $this->record()?->district_id ?? 0)),
            ],
            'bn_name' => ['required', 'string', 'max:255'],
        ];
    }
}
