<?php

namespace Modules\TenantCore\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Modules\Core\Http\Requests\BaseRequest;
use Modules\Core\Models\SmsRecharge;

class SmsRechargeRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return Gate::allows('requestRecharge', SmsRecharge::class);
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'string', 'regex:/\A\d{1,10}(?:\.\d{1,2})?\z/', 'numeric', 'min:0.01'],
            'payment_method' => ['required', 'string', 'max:100'],
            'payment_reference' => ['required', 'string', 'max:150'],
            'proof' => ['nullable', 'file', 'mimes:png,jpg,jpeg,webp,pdf', 'extensions:png,jpg,jpeg,webp,pdf', 'max:5120'],
        ];
    }
}
