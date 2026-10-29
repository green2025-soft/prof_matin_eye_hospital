<?php

namespace Modules\Hospital\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
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
        $invoiceRules = ['required', 'string', 'regex:/\A(?:consultation|investigation|operation):[1-9]\d{0,17}\z/'];
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
            'amount' => ['bail', 'required', 'string', 'regex:/\A\d{1,10}(?:\.\d{1,2})?\z/', 'numeric', 'min:0.01'],
            'payment_type_id' => ['bail', 'required', 'integer', 'min:1', $this->existsIn('payment_types')],
            'remarks' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function after(): array
    {
        if ($this->routeIs('hospital.due-collections.invoices', 'hospital.due-collections.invoice') || $this->isUpdate()) {
            return [];
        }

        return [function (Validator $validator): void {
            if ($validator->errors()->hasAny(['service', 'invoice'])) {
                return;
            }
            $service = $this->string('service')->toString();
            if ($service !== 'all' && ! str_starts_with($this->string('invoice')->toString(), $service.':')) {
                $validator->errors()->add('invoice', 'Select an invoice from the selected service.');
            }
        }];
    }
}
