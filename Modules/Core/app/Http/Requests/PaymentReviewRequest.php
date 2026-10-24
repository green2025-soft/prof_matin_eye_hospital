<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Modules\Core\Models\Payment;

class PaymentReviewRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return Gate::allows('create', Payment::class);
    }

    public function rules(): array
    {
        return ['reason' => ['nullable', 'string', 'max:500']];
    }
}
