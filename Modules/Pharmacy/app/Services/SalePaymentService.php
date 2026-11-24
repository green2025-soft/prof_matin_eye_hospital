<?php

namespace Modules\Pharmacy\Services;

use Illuminate\Validation\ValidationException;
use Modules\Accounting\Contracts\JournalPosting;
use Modules\Core\Helpers\Money;
use Modules\Core\Models\Account;
use Modules\Core\Services\SmsService;
use Modules\Hospital\Contracts\PharmacyCustomers;
use Modules\Pharmacy\Models\Payment;
use Modules\Pharmacy\Models\Sale;
use Modules\Pharmacy\Models\SalePayment;

class SalePaymentService extends PaymentService
{
    public function __construct(
        PharmacyAccess $pharmacy,
        JournalPosting $journals,
        private PharmacyCustomers $customers,
    ) {
        parent::__construct($pharmacy, $journals);
    }

    protected function savePayment(Account $freshActor, array $values): SalePayment
    {
        $customerId = (int) $values['customer_id'];
        $customer = $this->customers->findMany($freshActor, [$customerId])[$customerId] ?? null;
        if ($customer === null) {
            throw ValidationException::withMessages([
                'customer_id' => 'Select a valid customer.',
            ]);
        }

        $payment = new SalePayment;
        $settings = $this->preparePayment($payment, $values);
        $payment->forceFill(['customer_id' => $customerId]);

        $ids = array_column($values['allocations'], 'sale_id');
        $sales = Sale::where('customer_id', $customerId)
            ->where('product_type', $payment->product_type)
            ->whereIn('id', $ids)
            ->orderBy('id')
            ->lockForUpdate()
            ->get()
            ->keyBy('id');
        $allocations = [];
        foreach (collect($values['allocations'])->sortBy('sale_id') as $index => $allocation) {
            $allocations[] = ['sale_id' => (int) $allocation['sale_id']] + $this->applyAllocation(
                $freshActor,
                $sales->get((int) $allocation['sale_id']),
                Money::minor($allocation['amount'] ?? '0'),
                Money::minor($allocation['discount'] ?? '0'),
                $values['payment_date'],
                $index,
            );
        }

        $this->savePaymentItems($payment, $allocations);
        $this->journals->sync(
            source: $payment,
            feature: 'pharmacy.'.$payment->product_type.'.customer-payment',
            entryDate: $payment->payment_date->toDateString(),
            branchId: $payment->branch_id,
            amounts: [
                'settlement' => $payment->amount,
                'discount' => $payment->discount,
                'receivable' => $payment->amount + $payment->discount,
            ],
            created: true,
            paymentTypeId: $payment->payment_type_id,
            actor: $freshActor,
            reference: $payment->number,
        );

        $prefix = 'sms_'.$payment->product_type.'_customer_payment';
        $enabled = (bool) ($settings[$prefix.'_enabled'] ?? false);
        if ($enabled && filled($customer['mobile_no'] ?? null)) {
            $due = Sale::outstanding()
                ->where('customer_id', $customerId)
                ->where('product_type', $payment->product_type)
                ->sum('outstanding');

            app(SmsService::class)->queue(
                $payment,
                'pharmacy.'.$payment->product_type.'.payment',
                'pharmacy.sale-payment.created:'.$payment->getKey(),
                $customer['mobile_no'],
                $enabled,
                $settings[$prefix.'_template'] ?? '',
                [
                    ...($customer['sms_values'] ?? []),
                    'name' => $customer['name'],
                    ...$this->smsPaymentValues($payment, $due),
                    'direction' => 'Customer collection',
                ],
            );
        }

        return $payment;
    }

    protected function lockPayment(Payment $record): Payment
    {
        return SalePayment::whereKey($record->getKey())
            ->lockForUpdate()
            ->firstOrFail();
    }

    protected function reverseInvoices(Payment $payment): void
    {
        $items = $payment->items()
            ->orderBy('sale_id')
            ->get(['id', 'payment_id', 'sale_id', 'amount', 'discount']);
        $sales = Sale::whereIn('id', $items->pluck('sale_id'))
            ->orderBy('id')
            ->lockForUpdate()
            ->get()
            ->keyBy('id');

        foreach ($items as $item) {
            $this->reverseAllocation(
                $sales->get($item->sale_id),
                $item->amount,
                $item->discount,
            );
        }
    }

    protected function paymentNumberIndex(): string
    {
        return 'phr_sale_phr_payments_number_unique';
    }
}
