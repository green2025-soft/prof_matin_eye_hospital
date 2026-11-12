<?php

namespace Modules\Accounting\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Core\Http\Requests\BaseRequest;

class PaymentAccountRequest extends BaseRequest
{
    protected bool $partialUpdates = false;

    public function rules(): array
    {
        return [
            'payment_type_id' => ['required', 'integer', $this->existsIn('payment_types'), $this->unique('payment_type_id')],
            'account_head_id' => ['required', 'integer', Rule::exists('tenant.acc_account_heads', 'id')
                ->where('active', true)->where('depth', 5)->where('is_transaction', true)->where('type', 'asset')],
        ];
    }
}
