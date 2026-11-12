<?php

namespace Modules\Accounting\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Accounting\Models\AccountHead;
use Modules\Core\Http\Requests\BaseRequest;

class AccountHeadRequest extends BaseRequest
{
    protected bool $partialUpdates = false;

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => $this->onCreate(['required', 'integer', $this->existsIn('acc_account_heads')]),
            'active' => ['required', 'boolean'],
            'is_transaction' => ['required', 'boolean'],
            'remarks' => ['nullable', 'string', 'max:2000'],
            'system_key' => ['nullable', Rule::in(array_keys(AccountHead::REPORT_ROLES))],
            'cash_flow_activity' => ['nullable', Rule::in(array_keys(AccountHead::CASH_FLOW_ACTIVITIES))],
        ];
    }
}
