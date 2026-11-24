<?php

namespace Modules\Pharmacy\Http\Requests;

class PurchasePaymentRequest extends PaymentRequest
{
    public function rules(): array
    {
        if ($this->isMethod('GET')) {
            return $this->listingRules('supplier_id');
        }

        return $this->paymentRules('supplier_id', 'purchase_id') + [
            'customer_id' => ['prohibited'],
        ];
    }
}
