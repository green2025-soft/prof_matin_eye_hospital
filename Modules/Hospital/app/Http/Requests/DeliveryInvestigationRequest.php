<?php

namespace Modules\Hospital\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Hospital\Models\PatientInvestigation;
use Modules\Hospital\Services\ClinicalEntryService;

class DeliveryInvestigationRequest extends ListRequest
{
    protected bool $partialUpdates = false;

    public function authorize(): bool
    {
        return app(ClinicalEntryService::class)->allowsInvestigationDelivery(
            $this->user('web'), $this->isUpdate() ? 'update' : 'view',
            (int) $this->attributes->get('branch_id'), $this->isUpdate() ? $this->record() : null,
        );
    }

    public function rules(): array
    {
        $rules = parent::rules() + [
            'date_from' => ['bail', 'nullable', 'date_format:Y-m-d'],
            'date_to' => ['bail', 'nullable', 'date_format:Y-m-d', Rule::when(filled($this->query('date_from')), ['after_or_equal:date_from'])],
        ];

        if ($this->isUpdate()) {
            $rules['target_status'] = ['required', 'string', Rule::in(array_keys(PatientInvestigation::DELIVERY_STATUSES))];
        }

        return $rules;
    }

    public function validationData(): array
    {
        return $this->isUpdate() ? [...$this->query(), 'target_status' => $this->post('target_status')] : $this->query();
    }

    public function filters(): array
    {
        return $this->safe()->except('target_status');
    }
}
