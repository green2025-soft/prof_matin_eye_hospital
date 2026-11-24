<?php

namespace Modules\Pharmacy\Http\Requests;

class SalePaymentRequest extends PaymentRequest
{
    public function rules(): array
    {
        if ($this->isMethod('GET')) {
            return $this->listingRules('customer_id');
        }

        return $this->paymentRules('customer_id', 'sale_id') + [
            'supplier_id' => ['prohibited'],
        ];
    }
}
