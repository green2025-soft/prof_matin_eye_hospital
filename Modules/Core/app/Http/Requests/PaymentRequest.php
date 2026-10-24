<?php

namespace Modules\Core\Http\Requests;

class PaymentRequest extends BaseRequest
{
    protected bool $partialUpdates = false;

    public function rules(): array
    {
        return [
            'invoice_id' => ['required', 'integer', 'min:1', $this->existsIn('invoices')],
            'amount' => ['required', 'string', 'numeric', 'gt:0', 'regex:/^\d{1,10}(?:\.\d{1,2})?$/D'],
            'idempotency_key' => ['required', 'uuid'],
        ];
    }
}
