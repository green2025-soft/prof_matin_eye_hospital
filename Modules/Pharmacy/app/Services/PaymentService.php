<?php

namespace Modules\Pharmacy\Services;

use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Modules\Accounting\Contracts\JournalPosting;
use Modules\Core\Helpers\Money;
use Modules\Core\Models\Account;
use Modules\Core\Models\Setting;
use Modules\Core\Services\SmsService;
use Modules\Hospital\Contracts\PharmacyCustomers;
use Modules\Pharmacy\Models\Payment;
use Modules\Pharmacy\Models\PharmacySequence;
use Modules\Pharmacy\Models\Purchase;
use Modules\Pharmacy\Models\Sale;
use Modules\Pharmacy\Models\StockEntry;
use Modules\Pharmacy\Models\Supplier;
use Modules\TenantCore\Models\PaymentType;

class PaymentService
{
    public function __construct(
        private PharmacyAccess $pharmacy,
        private PharmacyCustomers $customers,
        private JournalPosting $journals,
    ) {}

    public function save(Account $actor, ?Payment $record, array $values): Payment
    {
        abort_if($record !== null, 405, 'Posted payments cannot be edited.');

        try {
            return $actor->withAccessLock(function (Account $freshActor) use ($values): Payment {
                $this->pharmacy->authorize($freshActor, 'pharmacy-payments.create');
                if (Carbon::parse($values['payment_date'])->startOfDay()->gt(now()->startOfDay())) {
                    throw ValidationException::withMessages(['payment_date' => 'The payment date cannot be in the future.']);
                }
                $payment = new Payment;
                $created = ! $payment->exists;
                $direction = $values['direction'];
                $customerId = $direction === 'customer' ? (int) $values['customer_id'] : null;
                $supplierId = $direction === 'supplier' ? (int) $values['supplier_id'] : null;
                $customer = $customerId === null
                    ? null
                    : ($this->customers->findMany($freshActor, [$customerId])[$customerId] ?? null);
                $supplier = $supplierId === null ? null : Supplier::find($supplierId, ['id', 'name', 'contact_no']);

                if ($customerId !== null && $customer === null) {
                    throw ValidationException::withMessages(['customer_id' => 'Select a valid customer.']);
                }
                if ($supplierId !== null && $supplier === null) {
                    throw ValidationException::withMessages(['supplier_id' => 'Select a valid supplier.']);
                }

                if (! empty($values['payment_type_id']) && ! PaymentType::whereKey($values['payment_type_id'])->exists()) {
                    throw ValidationException::withMessages(['payment_type_id' => 'Choose a valid payment type.']);
                }
                $settings = Setting::values('pharmacy', $payment->getConnectionName());
                $number = $this->nextNumber(
                    (int) request()->attributes->get('branch_id'),
                    $values['product_type'],
                    $values['payment_date'],
                    $settings,
                );

                $payment->forceFill([
                    'number' => $number,
                    'direction' => $direction,
                    'product_type' => $values['product_type'],
                    'payment_date' => $values['payment_date'],
                    'customer_id' => $customerId,
                    'supplier_id' => $supplierId,
                    'payment_type_id' => $values['payment_type_id'] ?? null,
                    'notes' => $values['notes'] ?? null,
                ]);
                if ($direction === 'customer') {
                    $this->collectCustomerPayment($freshActor, $payment, $values, $customer, $settings, $created);
                } else {
                    $this->paySupplier($freshActor, $payment, $values, $supplier, $settings, $created);
                }

                return $payment;
            });
        } catch (UniqueConstraintViolationException $exception) {
            if (str_contains($exception->errorInfo[2] ?? '', 'phr_payments_number_unique')) {
                throw ValidationException::withMessages(['record' => 'The generated payment number already exists. Choose a distinct Payment invoice pattern in Pharmacy settings.']);
            }

            throw $exception;
        }
    }

    private function collectCustomerPayment(Account $actor, Payment $payment, array $values, array $customer, array $settings, bool $created): void
    {
        $ids = array_column($values['allocations'], 'sale_id');
        $sales = Sale::where('customer_id', $payment->customer_id)
            ->where('product_type', $payment->product_type)
            ->whereIn('id', $ids)
            ->orderBy('id')
            ->lockForUpdate()
            ->get()
            ->keyBy('id');
        $allocations = [];
        foreach (collect($values['allocations'])->sortBy('sale_id') as $index => $allocation) {
            $allocations[] = ['sale_id' => (int) $allocation['sale_id']] + $this->applyAllocation(
                $actor,
                $sales->get((int) $allocation['sale_id']),
                Money::minor($allocation['amount'] ?? '0'),
                Money::minor($allocation['discount'] ?? '0'),
                $values['payment_date'],
                $index,
            );
        }
        $this->saveTotals($payment, $allocations);
        $payment->saleAllocations()->forceCreateMany($allocations);

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
            created: $created,
            paymentTypeId: $payment->payment_type_id,
            actor: $actor,
            reference: $payment->number,
        );

        $prefix = 'sms_'.$payment->product_type.'_customer_payment';
        $enabled = (bool) ($settings[$prefix.'_enabled'] ?? false);
        if (! $enabled || blank($customer['mobile_no'] ?? null)) {
            return;
        }

        $due = Sale::outstanding()
            ->where('customer_id', $payment->customer_id)
            ->where('product_type', $payment->product_type)
            ->sum('outstanding');

        app(SmsService::class)->queue(
            $payment,
            'pharmacy.'.$payment->product_type.'.payment',
            'pharmacy.payment.created:'.$payment->getKey(),
            $customer['mobile_no'],
            $enabled,
            $settings[$prefix.'_template'] ?? '',
            [
                ...($customer['sms_values'] ?? []),
                'name' => $customer['name'],
                'invoice' => $payment->number,
                'date' => $payment->payment_date->format('d-m-Y'),
                'total' => Money::decimal($payment->amount + $payment->discount),
                'paid' => Money::decimal($payment->amount),
                'due' => Money::decimal($due),
                'amount' => Money::decimal($payment->amount),
                'direction' => 'Customer collection',
            ],
        );
    }

    private function paySupplier(Account $actor, Payment $payment, array $values, Supplier $supplier, array $settings, bool $created): void
    {
        $ids = array_column($values['allocations'], 'purchase_id');
        $purchases = Purchase::where('supplier_id', $payment->supplier_id)
            ->where('product_type', $payment->product_type)
            ->whereIn('id', $ids)
            ->orderBy('id')
            ->lockForUpdate()
            ->get()
            ->keyBy('id');
        $allocations = [];
        foreach (collect($values['allocations'])->sortBy('purchase_id') as $index => $allocation) {
            $allocations[] = ['purchase_id' => (int) $allocation['purchase_id']] + $this->applyAllocation(
                $actor,
                $purchases->get((int) $allocation['purchase_id']),
                Money::minor($allocation['amount'] ?? '0'),
                Money::minor($allocation['discount'] ?? '0'),
                $values['payment_date'],
                $index,
            );
        }
        $this->saveTotals($payment, $allocations);
        $payment->purchaseAllocations()->forceCreateMany($allocations);

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
            created: $created,
            paymentTypeId: $payment->payment_type_id,
            actor: $actor,
            reference: $payment->number,
        );

        $prefix = 'sms_'.$payment->product_type.'_supplier_payment';
        $enabled = (bool) ($settings[$prefix.'_enabled'] ?? false);
        if (! $enabled || blank($supplier->contact_no)) {
            return;
        }

        $due = Purchase::outstanding()
            ->where('supplier_id', $payment->supplier_id)
            ->where('product_type', $payment->product_type)
            ->sum('outstanding');

        app(SmsService::class)->queue(
            $payment,
            'pharmacy.'.$payment->product_type.'.payment',
            'pharmacy.payment.created:'.$payment->getKey(),
            $supplier->contact_no,
            $enabled,
            $settings[$prefix.'_template'] ?? '',
            [
                'name' => $supplier->name,
                'invoice' => $payment->number,
                'date' => $payment->payment_date->format('d-m-Y'),
                'total' => Money::decimal($payment->amount + $payment->discount),
                'paid' => Money::decimal($payment->amount),
                'due' => Money::decimal($due),
                'amount' => Money::decimal($payment->amount),
                'direction' => 'Supplier payment',
            ],
        );
    }

    private function applyAllocation(Account $actor, ?StockEntry $invoice, int $amount, int $discount, string $paymentDate, int|string $index): array
    {
        if ($invoice === null || $invoice->voided_at !== null || $invoice->posted_at === null
            || $invoice->entry_date->toDateString() > $paymentDate) {
            throw ValidationException::withMessages(['allocations' => 'Choose outstanding invoices for this branch and party.']);
        }
        if ($discount > 0) {
            $this->pharmacy->authorize($actor, 'pharmacy-payments.discount');
        }
        if ($amount + $discount < 1 || $amount + $discount > $invoice->outstandingMinor()) {
            throw ValidationException::withMessages(["allocations.$index.amount" => 'Cash and discount must be positive and cannot exceed the outstanding amount.']);
        }
        $invoice->paid += $amount;
        $invoice->settlement_discount += $discount;
        $invoice->save();

        return [
            'amount' => $amount,
            'discount' => $discount,
        ];
    }

    private function saveTotals(Payment $payment, array $allocations): void
    {
        $amount = array_sum(array_column($allocations, 'amount'));
        $discount = array_sum(array_column($allocations, 'discount'));
        if ($amount > 0 && $payment->payment_type_id === null) {
            throw ValidationException::withMessages(['payment_type_id' => 'Choose a payment type for this payment.']);
        }
        $payment->forceFill([
            'amount' => $amount,
            'discount' => $discount,
        ])->save();
    }

    private function nextNumber(int $branchId, string $productType, string $paymentDate, array $settings): string
    {
        $scope = 'payment:'.$branchId;
        PharmacySequence::insertOrIgnore([
            'scope' => $scope,
            'last_number' => 0,
        ]);
        $sequence = PharmacySequence::whereKey($scope)
            ->lockForUpdate()
            ->firstOrFail();
        $number = (int) $sequence->last_number + 1;
        if ($number > 999999999999) {
            throw ValidationException::withMessages(['record' => 'The payment numbering sequence is exhausted.']);
        }
        $sequence->last_number = $number;
        $sequence->save();

        $pattern = $settings['payment_invoice_pattern'] ?? 'PAY-{BRANCH}-{SEQ:6}';
        $date = Carbon::parse($paymentDate, config('app.timezone'));
        $formatted = strtr($pattern, [
            '{YYYY}' => $date->format('Y'),
            '{MM}' => $date->format('m'),
            '{DD}' => $date->format('d'),
            '{BRANCH}' => (string) $branchId,
            '{PRODUCT}' => $productType === 'medicine' ? 'MED' : 'OPT',
        ]);
        $formatted = preg_replace_callback('/\{SEQ(?::([1-9]|1[0-2]))?\}/', fn (array $match): string => str_pad((string) $number, (int) ($match[1] ?? 6), '0', STR_PAD_LEFT), $formatted);
        if (mb_strlen($formatted) > 40) {
            throw ValidationException::withMessages(['record' => 'The generated payment number is too long. Shorten its pattern in Pharmacy settings.']);
        }

        return $formatted;
    }

    public function reverse(Account $actor, Payment $record): void
    {
        $actor->withAccessLock(function (Account $freshActor) use ($record): void {
            $this->pharmacy->authorize($freshActor, 'pharmacy-payments.void');
            $payment = Payment::whereKey($record->id)
                ->lockForUpdate()
                ->firstOrFail();
            if ($payment->voided_at !== null) {
                throw ValidationException::withMessages(['payment' => 'This payment has already been voided.']);
            }
            if ($payment->direction === 'customer') {
                $this->reverseCustomerPayment($payment);
            } else {
                $this->reverseSupplierPayment($payment);
            }
            $this->journals->reverse($payment);
            $payment->voided_at = now();
            $payment->save();
        });
    }

    private function reverseCustomerPayment(Payment $payment): void
    {
        $allocations = $payment->saleAllocations()
            ->orderBy('sale_id')
            ->get();
        $sales = Sale::whereIn('id', $allocations->pluck('sale_id'))
            ->orderBy('id')
            ->lockForUpdate()
            ->get()
            ->keyBy('id');
        foreach ($allocations as $allocation) {
            $this->reverseAllocation($sales->get($allocation->sale_id), $allocation->amount, $allocation->discount);
        }
    }

    private function reverseSupplierPayment(Payment $payment): void
    {
        $allocations = $payment->purchaseAllocations()
            ->orderBy('purchase_id')
            ->get();
        $purchases = Purchase::whereIn('id', $allocations->pluck('purchase_id'))
            ->orderBy('id')
            ->lockForUpdate()
            ->get()
            ->keyBy('id');
        foreach ($allocations as $allocation) {
            $this->reverseAllocation($purchases->get($allocation->purchase_id), $allocation->amount, $allocation->discount);
        }
    }

    private function reverseAllocation(?StockEntry $invoice, int $amount, int $discount): void
    {
        if ($invoice === null || $invoice->voided_at !== null
            || $invoice->paid - $amount < $invoice->refunded
            || $invoice->settlement_discount < $discount) {
            throw ValidationException::withMessages(['payment' => 'A linked invoice has a refund. Reverse that return before voiding this payment.']);
        }
        $invoice->paid -= $amount;
        $invoice->settlement_discount -= $discount;
        $invoice->save();
    }
}
