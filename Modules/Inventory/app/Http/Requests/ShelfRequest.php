<?php

namespace Modules\Inventory\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Core\Http\Requests\BaseRequest;
use Modules\Inventory\Models\Product;

class ShelfRequest extends BaseRequest
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
            'number' => ['nullable', 'string', 'max:100'],
        ];
    }
}
