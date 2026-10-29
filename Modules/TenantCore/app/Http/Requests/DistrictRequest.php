<?php

namespace Modules\TenantCore\Http\Requests;

use Modules\Core\Http\Requests\BaseRequest;

class DistrictRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'division_id' => ['required', 'integer', 'min:1', $this->existsIn('divisions')],
            'name' => [
                'required', 'string', 'max:255',
                $this->unique('name')->where('division_id', $this->integer('division_id', $this->record()?->division_id ?? 0)),
            ],
            'bn_name' => ['required', 'string', 'max:255'],
        ];
    }
}
