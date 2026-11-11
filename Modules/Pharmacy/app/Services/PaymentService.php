<?php

namespace Modules\Pharmacy\Services;

use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Modules\Core\Helpers\Money;
use Modules\Core\Models\Account;
use Modules\Core\Models\Setting;
use Modules\Core\Services\AccessManager;
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
    public function __construct(private AccessManager $access, private PharmacyAccess $pharmacy, private PharmacyCustomers $customers) {}

    public function save(Account $actor, ?Payment $record, array $values): Payment
    {
        abort_if($record !== null, 405, 'Posted payments cannot be edited.');

        try {
            return $this->access->transaction($actor, function (Account $freshActor) use ($values): Payment {
                $this->pharmacy->authorize($freshActor, 'pharmacy-payments.create');
                if (Carbon::parse($values['payment_date'])->startOfDay()->gt(now()->startOfDay())) {
                    throw ValidationException::withMessages(['payment_date' => 'The payment date cannot be in the future.']);
                }
                $payment = new Payment;
                $direction = $values['direction'];
                $customerId = $direction === 'customer' ? (int) $values['customer_id'] : null;
                $supplierId = $direction === 'supplier' ? (int) $values['supplier_id'] : null;

                if ($customerId !== null && ! isset($this->customers->findMany($freshActor, [$customerId])[$customerId])) {
                    throw ValidationException::withMessages(['customer_id' => 'Select a valid customer.']);
                }
                if ($supplierId !== null && ! Supplier::whereKey($supplierId)->exists()) {
                    throw ValidationException::withMessages(['supplier_id' => 'Select a valid supplier.']);
                }

                if (! empty($values['payment_type_id']) && ! PaymentType::whereKey($values['payment_type_id'])->exists()) {
                    throw ValidationException::withMessages(['payment_type_id' => 'Choose a valid payment type.']);
                }
                $payment->forceFill([
                    'number' => $this->nextNumber((int) request()->attributes->get('branch_id'), $values['product_type'], $values['payment_date']),
                    'direction' => $direction,
                    'product_type' => $values['product_type'],
                    'payment_date' => $values['payment_date'],
                    'customer_id' => $customerId,
                    'supplier_id' => $supplierId,
                    'payment_type_id' => $values['payment_type_id'] ?? null,
                    'notes' => $values['notes'] ?? null,
                ]);
                if ($direction === 'customer') {
                    $this->collectCustomerPayment($freshActor, $payment, $values);
                } else {
                    $this->paySupplier($freshActor, $payment, $values);
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

    private function collectCustomerPayment(Account $actor, Payment $payment, array $values): void
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
    }

    private function paySupplier(Account $actor, Payment $payment, array $values): void
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

    private function nextNumber(int $branchId, string $productType, string $paymentDate): string
    {
        $connection = (new Payment)->getConnection();
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

        $settings = Setting::values('pharmacy', $connection->getName());
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
        $this->access->transaction($actor, function (Account $freshActor) use ($record): void {
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
