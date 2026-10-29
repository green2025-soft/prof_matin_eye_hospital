<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Core\Models\Plan;

class PlanRequest extends BaseRequest
{
    public function payload(): array
    {
        $values = parent::payload();
        unset($values['price_minor']);

        if (array_key_exists('features', $values)) {
            $keys = preg_split('/[\s,]+/', trim($values['features'] ?? ''), -1, PREG_SPLIT_NO_EMPTY);
            $values['features'] = array_fill_keys(array_unique($keys), true);
        }

        return $values;
    }

    public function rules(): array
    {
        $defaultSlug = config('billing.default_plan', 'starter');
        $wasDefault = $this->record()?->isDefault() ?? false;
        $isDefault = $wasDefault || $this->input('slug') === $defaultSlug;

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:80', 'regex:/\A[a-z0-9]+(?:-[a-z0-9]+)*\z/', $this->unique('slug'), Rule::when($wasDefault, Rule::in([$defaultSlug]))],
            'description' => ['nullable', 'string', 'max:5000'],
            'price' => ['required', 'string', 'regex:/\A\d{1,10}(?:\.\d{1,2})?\z/', Rule::when($isDefault, ['numeric', 'max:0'])],
            'currency' => ['required', 'string', 'regex:/\A[A-Z]{3}\z/'],
            'billing_interval' => ['required', Rule::in(array_keys(Plan::INTERVALS))],
            'trial_days' => ['required', 'integer', 'min:0', 'max:365'],
            'features' => ['nullable', 'string', 'max:5000', 'regex:/\A[a-z][a-z0-9_.-]*(?:[\s,]+[a-z][a-z0-9_.-]*)*\s*\z/'],
            'user_limit' => ['nullable', 'integer', 'min:1', 'max:1000000'],
            'is_active' => ['required', 'boolean', Rule::when($isDefault, 'accepted')],
            'price_minor' => ['prohibited'],
        ];
    }

    protected function prepareForValidation(): void
    {
        parent::prepareForValidation();

        if (is_string($this->input('currency'))) {
            $this->merge(['currency' => strtoupper($this->input('currency'))]);
        }

        if (is_string($this->input('slug'))) {
            $this->merge(['slug' => strtolower($this->input('slug'))]);
        }
    }
}
