<?php

namespace Modules\Pharmacy\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Core\Http\Requests\BaseRequest;

abstract class PaymentRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function listingRules(string $partyField): array
    {
        return [
            'search' => ['nullable', 'string', 'max:80'],
            'from_date' => ['nullable', 'date_format:Y-m-d'],
            'to_date' => ['nullable', 'date_format:Y-m-d', Rule::when($this->filled('from_date'), ['after_or_equal:from_date'])],
            'product_type' => ['nullable', Rule::in(['medicine', 'optic'])],
            $partyField => ['nullable', 'integer', 'min:1'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', Rule::in(config('pagination.options'))],
        ];
    }

    protected function paymentRules(string $partyField, string $invoiceField): array
    {
        $productType = $this->query('product_type', 'medicine');
        $productType = in_array($productType, ['medicine', 'optic'], true) ? $productType : 'medicine';

        return [
            'payment_date' => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
            'product_type' => ['required', Rule::in([$productType])],
            $partyField => ['required', 'integer', 'min:1'],
            'payment_type_id' => ['nullable', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'direction' => ['prohibited'],
            'allocations' => ['required', 'array', 'min:1', 'max:200'],
            'allocations.*' => ['required', "array:{$invoiceField},amount,discount"],
            "allocations.*.{$invoiceField}" => ['required', 'integer', 'min:1', 'distinct'],
            'allocations.*.amount' => ['required', 'regex:/^\d{1,10}(?:\.\d{1,2})?$/D'],
            'allocations.*.discount' => ['nullable', 'regex:/^\d{1,10}(?:\.\d{1,2})?$/D'],
        ];
    }
}
