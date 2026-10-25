<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Modules\Core\Models\Plan;

class PlanPermissionPageRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return Gate::allows('viewAny', Plan::class);
    }

    public function rules(): array
    {
        return [
            'plan' => ['bail', 'nullable', 'integer', 'min:1', $this->existsIn('plans')],
            'plan_search' => ['nullable', 'string', 'max:80'],
        ];
    }
}
