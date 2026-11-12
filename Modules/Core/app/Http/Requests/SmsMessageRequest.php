<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class SmsMessageRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return Gate::allows('resolveMessage', $this->record());
    }

    public function rules(): array
    {
        return ['decision' => ['required', Rule::in(['submitted', 'rejected'])]];
    }
}
