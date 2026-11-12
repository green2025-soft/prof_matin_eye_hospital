<?php

namespace Modules\Accounting\Http\Requests;

use Modules\Core\Http\Requests\BaseRequest;

class AccountModuleRequest extends BaseRequest
{
    protected bool $partialUpdates = false;

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'voucher_type_id' => ['required', 'integer', 'min:1'],
            'active' => ['required', 'boolean'],
            'components' => ['required', 'array', 'min:1', 'max:30'],
            'components.*.id' => ['required', 'integer', 'distinct'],
            'components.*.account_head_id' => ['required', 'integer', 'min:1'],
        ];
    }
}
