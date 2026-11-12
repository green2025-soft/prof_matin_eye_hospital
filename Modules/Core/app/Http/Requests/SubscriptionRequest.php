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
            'renewal' => $this->onCreate(
                ['required', Rule::in(['renew'])],
                ['required', Rule::in(['renew', 'period_end', 'now'])],
            ),
        ];
    }
}
