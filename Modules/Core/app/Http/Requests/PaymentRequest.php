<?php

namespace Modules\Core\Http\Requests;

class PaymentRequest extends BaseRequest
{
    protected bool $partialUpdates = false;

    public function rules(): array
    {
        return [
            'invoice_id' => ['required', 'integer', 'min:1', $this->existsIn('invoices')],
            'amount' => ['required', 'string', 'decimal:0,2', 'between:0.01,9999999999.99'],
            'idempotency_key' => ['required', 'uuid'],
        ];
    }
}
