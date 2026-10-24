<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Support\Facades\Gate;

class ListRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return Gate::allows('viewAny', $this->controller()->modelClass());
    }

    public function rules(): array
    {
        return $this->controller()->queryRules();
    }
}
