<?php

namespace Modules\Hospital\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Modules\Core\Models\Account;
use Modules\Hospital\Models\Reference;
use Modules\Hospital\Services\ClinicalEntryService;

class CommissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $actor = $this->user('web');

        return $actor instanceof Account && app(ClinicalEntryService::class)->allowsCommissions(
            $actor, $this->routeIs('hospital.commissions.update') ? 'update' : 'view',
            (int) $this->attributes->get('branch_id'),
        );
    }

    public function rules(): array
    {
        $branchId = max(0, (int) $this->attributes->get('branch_id'));
        $rules = [
            'date_from' => ['bail', 'nullable', 'date_format:Y-m-d'],
            'date_to' => ['bail', 'nullable', 'date_format:Y-m-d', Rule::when(filled($this->query('date_from')), ['after_or_equal:date_from'])],
            'filter_reference_id' => ['bail', 'nullable', 'integer', 'min:1', Rule::exists(Reference::class, 'id')->where('branch_id', $branchId)],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', Rule::in(config('pagination.options'))],
        ];

        if ($this->routeIs('hospital.commissions.update')) {
            $rules['target_status'] = ['required', 'string', Rule::in(['paid', 'unpaid'])];
        }

        return $rules;
    }

    public function validationData(): array
    {
        return $this->routeIs('hospital.commissions.update')
            ? [...$this->query(), 'target_status' => $this->post('target_status')]
            : $this->query();
    }

    public function filters(): array
    {
        return $this->safe()->except('target_status');
    }

    protected function prepareForValidation(): void
    {
        if ($this->routeIs('hospital.commissions.update')) {
            $values = Arr::only($this->request->all(), ['_token', '_method', 'target_status']);
            $this->replace($values);
            // Laravel flashes the original HTTP request when validation or a later operation fails.
            request()->replace($values);
        }
    }

    protected function getRedirectUrl(): string
    {
        $filters = Arr::only($this->query(), ['date_from', 'date_to', 'filter_reference_id', 'page', 'per_page']);
        $filters = Arr::except($filters, array_keys($this->validator->failed()));

        return route('hospital.commissions.index', array_filter($filters, fn (mixed $value): bool => is_scalar($value) && $value !== ''));
    }
}
