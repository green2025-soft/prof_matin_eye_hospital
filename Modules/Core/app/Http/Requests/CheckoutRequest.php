<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Core\Models\Invoice;
use Modules\Core\Policies\BillingPolicy;

class CheckoutRequest extends BaseRequest
{
    public function authorize(): bool
    {
        $invoice = $this->route('invoice');

        return $invoice instanceof Invoice && app(BillingPolicy::class)->pay($this->user('web'), $invoice);
    }

    public function rules(): array
    {
        return [
            'provider' => ['required', Rule::in(['manual', ...array_keys(config('billing.drivers'))])],
            'submitted_reference' => ['required_if:provider,manual', 'nullable', 'string', 'max:190'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function payload(): array
    {
        $invoice = $this->route('invoice');

        return parent::payload() + [
            'invoice_id' => $invoice->getKey(),
            'return_url' => route('tenantcore.billing.show', $invoice->getKey()),
        ];
    }
}
