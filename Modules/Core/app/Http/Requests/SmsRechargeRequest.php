<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class SmsRechargeRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return Gate::allows('reviewRecharge', $this->record());
    }

    public function rules(): array
    {
        return [
            'decision' => ['required', Rule::in(['approve', 'reject'])],
            'review_note' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
