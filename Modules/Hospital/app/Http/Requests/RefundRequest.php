<?php

namespace Modules\Hospital\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Modules\Core\Http\Requests\BaseRequest;
use Modules\Hospital\Models\Refund;

class RefundRequest extends BaseRequest
{
    protected bool $partialUpdates = false;

    public function authorize(): bool
    {
        return $this->routeIs('hospital.refunds.invoices', 'hospital.refunds.invoice')
            ? Gate::allows('create', Refund::class)
            : parent::authorize();
    }

    public function rules(): array
    {
        $services = array_keys(clinicalServiceOptions());
        $invoiceRules = ['required', 'string', 'max:40'];
        if ($this->routeIs('hospital.refunds.invoice')) {
            return ['invoice' => $invoiceRules];
        }

        if ($this->routeIs('hospital.refunds.invoices')) {
            return [
                'service' => ['nullable', 'string', Rule::in($services)],
                'search' => ['nullable', 'string', 'max:80'],
                'page' => ['nullable', 'integer', 'min:1'],
            ];
        }

        $investigation = is_string($this->input('invoice')) && str_starts_with($this->input('invoice'), 'investigation:');

        return [
            'service' => $this->onCreate(['required', 'string', Rule::in($services)], 'missing'),
            'invoice' => $this->onCreate($invoiceRules, 'missing'),
            'item_ids' => $this->onCreate($investigation ? ['required', 'array', 'min:1', 'max:100'] : ['prohibited'], 'missing'),
            'item_ids.*' => ['required', 'integer', 'min:1', 'distinct'],
            'cause_id' => ['bail', 'required', 'integer', 'min:1', $this->existsIn('refund_causes')],
            'remarks' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
