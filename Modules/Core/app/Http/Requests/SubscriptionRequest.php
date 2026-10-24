<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Validation\Rule;

class SubscriptionRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'tenant_id' => $this->onCreate(['bail', 'required', 'integer', 'min:1', $this->existsIn('tenants'), $this->unique('tenant_id')]),
            'plan_id' => ['bail', 'required', 'integer', 'min:1', $this->existsIn('plans')],
            'renewal' => ['required', Rule::in(['renew', 'period_end', 'now'])],
            'status' => ['prohibited'],
            'price_minor' => ['prohibited'],
            'price' => ['prohibited'],
            'currency' => ['prohibited'],
            'billing_interval' => ['prohibited'],
            'starts_at' => ['prohibited'],
            'trial_ends_at' => ['prohibited'],
            'current_period_starts_at' => ['prohibited'],
            'current_period_ends_at' => ['prohibited'],
            'ends_at' => ['prohibited'],
            'cancel_at_period_end' => ['prohibited'],
        ];
    }
}
