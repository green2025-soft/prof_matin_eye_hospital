<?php

namespace Modules\Accounting\Http\Requests;

use Illuminate\Validation\Rule;

class LedgerReportRequest extends DateRangeReportRequest
{
    public function rules(): array
    {
        return parent::rules() + [
            'account_head_id' => ['nullable', 'integer', Rule::exists('tenant.acc_account_heads', 'id')->where('depth', 5)],
        ];
    }
}
