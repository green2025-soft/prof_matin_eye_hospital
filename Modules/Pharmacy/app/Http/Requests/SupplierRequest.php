<?php

namespace Modules\Pharmacy\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Core\Http\Requests\BaseRequest;
use Modules\Pharmacy\Models\Product;

class SupplierRequest extends BaseRequest
{
    public function rules(): array
    {
        $record = $this->record();
        $type = $record?->type ?? $this->query('type', 'medicine');
        if (! is_string($type) || ! array_key_exists($type, Product::types())) {
            $type = 'medicine';
        }

        return [
            'type' => ['bail', 'required', 'string', Rule::in([$type])],
            'name' => ['required', 'string', 'max:255'],
            'contact_no' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:2000'],
            'active' => ['required', 'boolean'],
        ];
    }
}
