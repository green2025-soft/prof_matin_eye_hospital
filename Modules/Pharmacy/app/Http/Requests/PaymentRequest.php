<?php

namespace Modules\Pharmacy\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Core\Http\Requests\BaseRequest;

class PaymentRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if ($this->isMethod('GET')) {
            return [
                'search' => ['nullable', 'string', 'max:80'],
                'from_date' => ['nullable', 'date_format:Y-m-d'],
                'to_date' => ['nullable', 'date_format:Y-m-d', Rule::when($this->filled('from_date'), ['after_or_equal:from_date'])],
                'direction' => ['nullable', Rule::in(['customer', 'supplier'])],
                'product_type' => ['nullable', Rule::in(['medicine', 'optic'])],
                'customer_id' => ['nullable', 'integer', 'min:1'],
                'supplier_id' => ['nullable', 'integer', 'min:1'],
                'page' => ['nullable', 'integer', 'min:1'],
                'per_page' => ['nullable', Rule::in(config('pagination.options'))],
            ];
        }

        $productType = $this->query('product_type', 'medicine');
        $productType = in_array($productType, ['medicine', 'optic'], true) ? $productType : 'medicine';
        $direction = $this->query('direction', 'customer');
        $direction = in_array($direction, ['customer', 'supplier'], true) ? $direction : 'customer';

        $rules = [
            'payment_date' => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
            'direction' => ['required', Rule::in([$direction])],
            'product_type' => ['required', Rule::in([$productType])],
            'customer_id' => $direction === 'customer' ? ['required', 'integer', 'min:1'] : ['prohibited'],
            'supplier_id' => $direction === 'supplier' ? ['required', 'integer', 'min:1'] : ['prohibited'],
            'payment_type_id' => ['nullable', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'allocations' => ['required', 'array', 'min:1', 'max:200'],
            'allocations.*.amount' => ['required', 'regex:/^\d{1,10}(?:\.\d{1,2})?$/D'],
            'allocations.*.discount' => ['nullable', 'regex:/^\d{1,10}(?:\.\d{1,2})?$/D'],
        ];
        if ($direction === 'customer') {
            return $rules + [
                'allocations.*' => ['required', 'array:sale_id,amount,discount'],
                'allocations.*.sale_id' => ['required', 'integer', 'min:1', 'distinct'],
            ];
        }

        return $rules + [
            'allocations.*' => ['required', 'array:purchase_id,amount,discount'],
            'allocations.*.purchase_id' => ['required', 'integer', 'min:1', 'distinct'],
        ];
    }
}
