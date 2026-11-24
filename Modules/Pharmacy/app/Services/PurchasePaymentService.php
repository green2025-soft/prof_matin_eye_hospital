<?php

namespace Modules\Pharmacy\Services;

use Illuminate\Validation\ValidationException;
use Modules\Accounting\Contracts\JournalPosting;
use Modules\Core\Helpers\Money;
use Modules\Core\Models\Account;
use Modules\Core\Services\SmsService;
use Modules\Pharmacy\Models\Payment;
use Modules\Pharmacy\Models\Purchase;
use Modules\Pharmacy\Models\PurchasePayment;
use Modules\Pharmacy\Models\Supplier;

class PurchasePaymentService extends PaymentService
{
    public function __construct(
        PharmacyAccess $pharmacy,
        JournalPosting $journals,
    ) {
        parent::__construct($pharmacy, $journals);
    }

    protected function savePayment(Account $freshActor, array $values): PurchasePayment
    {
        $supplierId = (int) $values['supplier_id'];
        $supplier = Supplier::find($supplierId, ['id', 'name', 'contact_no']);
        if ($supplier === null) {
            throw ValidationException::withMessages([
                'supplier_id' => 'Select a valid supplier.',
            ]);
        }

        $payment = new PurchasePayment;
        $settings = $this->preparePayment($payment, $values);
        $payment->forceFill(['supplier_id' => $supplierId]);

        $ids = array_column($values['allocations'], 'purchase_id');
        $purchases = Purchase::where('supplier_id', $supplierId)
            ->where('product_type', $payment->product_type)
            ->whereIn('id', $ids)
            ->orderBy('id')
            ->lockForUpdate()
            ->get()
            ->keyBy('id');
        $allocations = [];
        foreach (collect($values['allocations'])->sortBy('purchase_id') as $index => $allocation) {
            $allocations[] = ['purchase_id' => (int) $allocation['purchase_id']] + $this->applyAllocation(
                $freshActor,
                $purchases->get((int) $allocation['purchase_id']),
                Money::minor($allocation['amount'] ?? '0'),
                Money::minor($allocation['discount'] ?? '0'),
                $values['payment_date'],
                $index,
            );
        }

        $this->savePaymentItems($payment, $allocations);
        $this->journals->sync(
            source: $payment,
            feature: 'pharmacy.'.$payment->product_type.'.supplier-payment',
            entryDate: $payment->payment_date->toDateString(),
            branchId: $payment->branch_id,
            amounts: [
                'payable' => $payment->amount + $payment->discount,
                'settlement' => $payment->amount,
                'discount' => $payment->discount,
            ],
            created: true,
            paymentTypeId: $payment->payment_type_id,
            actor: $freshActor,
            reference: $payment->number,
        );

        $prefix = 'sms_'.$payment->product_type.'_supplier_payment';
        $enabled = (bool) ($settings[$prefix.'_enabled'] ?? false);
        if ($enabled && filled($supplier->contact_no)) {
            $due = Purchase::outstanding()
                ->where('supplier_id', $supplierId)
                ->where('product_type', $payment->product_type)
                ->sum('outstanding');

            app(SmsService::class)->queue(
                $payment,
                'pharmacy.'.$payment->product_type.'.payment',
                'pharmacy.purchase-payment.created:'.$payment->getKey(),
                $supplier->contact_no,
                $enabled,
                $settings[$prefix.'_template'] ?? '',
                [
                    'name' => $supplier->name,
                    ...$this->smsPaymentValues($payment, $due),
                    'direction' => 'Supplier payment',
                ],
            );
        }

        return $payment;
    }

    protected function lockPayment(Payment $record): Payment
    {
        return PurchasePayment::whereKey($record->getKey())
            ->lockForUpdate()
            ->firstOrFail();
    }

    protected function reverseInvoices(Payment $payment): void
    {
        $items = $payment->items()
            ->orderBy('purchase_id')
            ->get(['id', 'payment_id', 'purchase_id', 'amount', 'discount']);
        $purchases = Purchase::whereIn('id', $items->pluck('purchase_id'))
            ->orderBy('id')
            ->lockForUpdate()
            ->get()
            ->keyBy('id');

        foreach ($items as $item) {
            $this->reverseAllocation(
                $purchases->get($item->purchase_id),
                $item->amount,
                $item->discount,
            );
        }
    }

    protected function paymentNumberIndex(): string
    {
        return 'phr_purchase_phr_payments_number_unique';
    }
}
