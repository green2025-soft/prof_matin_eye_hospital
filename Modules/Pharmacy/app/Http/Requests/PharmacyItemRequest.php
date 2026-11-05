<?php

namespace Modules\Pharmacy\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Pharmacy\Services\PharmacyAccess;

class PharmacyItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return app(PharmacyAccess::class)->allows($this->user('web'), 'pharmacy.view');
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:100'],
            'product_type' => ['required', Rule::in(['medicine', 'optic'])],
            'purpose' => ['nullable', Rule::in(['purchase', 'sale', 'stock'])],
            'page' => ['sometimes', 'integer', 'min:1'],
        ];
    }
}
