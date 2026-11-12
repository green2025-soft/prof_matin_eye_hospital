<?php

namespace Modules\Hospital\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Modules\Core\Http\Requests\BaseRequest;
use Modules\Hospital\Models\DueCollection;

class DueCollectionRequest extends BaseRequest
{
    protected bool $partialUpdates = false;

    public function authorize(): bool
    {
        return $this->routeIs('hospital.due-collections.invoices', 'hospital.due-collections.invoice')
            ? Gate::allows('create', DueCollection::class)
            : parent::authorize();
    }

    public function rules(): array
    {
        $services = array_keys(clinicalServiceOptions());
        $invoiceRules = ['required', 'string', 'max:40'];
        if ($this->routeIs('hospital.due-collections.invoice')) {
            return ['invoice' => $invoiceRules];
        }
        if ($this->routeIs('hospital.due-collections.invoices')) {
            return [
                'service' => ['nullable', 'string', Rule::in($services)],
                'search' => ['nullable', 'string', 'max:80'],
                'page' => ['nullable', 'integer', 'min:1'],
            ];
        }

        return [
            'service' => $this->onCreate(['required', 'string', Rule::in($services)], 'missing'),
            'invoice' => $this->onCreate($invoiceRules, 'missing'),
            'collection_date' => ['required', 'date_format:Y-m-d'],
            'amount' => ['required', 'string', 'decimal:0,2', 'between:0.01,9999999999.99'],
            'payment_type_id' => ['bail', 'required', 'integer', 'min:1', $this->existsIn('payment_types')],
            'remarks' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
