<?php

namespace Modules\Pharmacy\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Core\Http\Requests\BaseRequest;
use Modules\Pharmacy\Services\PharmacyAccess;

class CustomerRequest extends BaseRequest
{
    public function authorize(): bool
    {
        $access = app(PharmacyAccess::class);
        $actor = $this->user('web');

        return $this->isMethod('GET') ? $access->allows($actor, 'pharmacy.view')
            : $access->allows($actor, 'pharmacy-sales.create') && $access->allows($actor, 'pharmacy-customers.create');
    }

    public function rules(): array
    {
        if ($this->isMethod('GET')) {
            return [
                'search' => ['nullable', 'string', 'max:80'],
                'page' => ['sometimes', 'integer', 'min:1'],
                'per_page' => ['nullable', Rule::in(config('pagination.options'))],
                'new_sale' => ['nullable', 'boolean'],
            ];
        }

        return [
            'name' => ['required', 'string', 'max:255'], 'mobile_no' => ['required', 'string', 'max:30'],
            'age' => ['nullable', 'integer', 'between:0,150'], 'gender' => ['nullable', 'in:male,female,other'],
            'address' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
