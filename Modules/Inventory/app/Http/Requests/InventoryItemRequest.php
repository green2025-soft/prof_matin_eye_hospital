<?php

namespace Modules\Inventory\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Inventory\Services\InventoryAccess;

class InventoryItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return app(InventoryAccess::class)->allows($this->user('web'), 'inventory.view');
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
