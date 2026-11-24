<?php

namespace Modules\Pharmacy\Services;

use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Modules\Accounting\Contracts\JournalPosting;
use Modules\Core\Helpers\Money;
use Modules\Core\Models\Account;
use Modules\Core\Models\Setting;
use Modules\Pharmacy\Models\Payment;
use Modules\Pharmacy\Models\PharmacySequence;
use Modules\Pharmacy\Models\StockEntry;
use Modules\TenantCore\Models\PaymentType;

abstract class PaymentService
{
    public function __construct(
        protected PharmacyAccess $pharmacy,
        protected JournalPosting $journals,
    ) {}

    public function save(Account $actor, ?Payment $record, array $values): Payment
    {
        abort_if($record !== null, 405, 'Posted payments cannot be edited.');

        try {
            return $actor->withAccessLock(function (Account $freshActor) use ($values): Payment {
                $this->pharmacy->authorize($freshActor, 'pharmacy-payments.create');
                $this->assertPaymentDate($values['payment_date']);
                $this->assertPaymentType($values);

                return $this->savePayment($freshActor, $values);
            });
        } catch (UniqueConstraintViolationException $exception) {
            $this->translateNumberConflict($exception, $this->paymentNumberIndex());

            throw $exception;
        }
    }

    public function reverse(Account $actor, Payment $record): void
    {
        $actor->withAccessLock(function (Account $freshActor) use ($record): void {
            $this->pharmacy->authorize($freshActor, 'pharmacy-payments.void');
            $payment = $this->lockPayment($record);
            if ($payment->voided_at !== null) {
                throw ValidationException::withMessages([
                    'payment' => 'This payment has already been voided.',
                ]);
            }

            $this->reverseInvoices($payment);
            $this->reverseAccounting($payment);
        });
    }

    public function delete(Account $actor, Payment $record): void
    {
        $actor->withAccessLock(function (Account $freshActor) use ($record): void {
            $this->pharmacy->authorize($freshActor, 'pharmacy-payments.delete');
            $payment = $this->lockPayment($record);

            if ($payment->voided_at === null) {
                $this->reverseInvoices($payment);
                $this->reverseAccounting($payment);
            }

            foreach ($payment->items()->get() as $item) {
                $item->delete();
            }

            $payment->delete();
        });
    }

    abstract protected function savePayment(Account $actor, array $values): Payment;

    abstract protected function lockPayment(Payment $record): Payment;

    abstract protected function reverseInvoices(Payment $payment): void;

    abstract protected function paymentNumberIndex(): string;

    protected function assertPaymentDate(string $paymentDate): void
    {
        if (Carbon::parse($paymentDate)->startOfDay()->gt(now()->startOfDay())) {
            throw ValidationException::withMessages([
                'payment_date' => 'The payment date cannot be in the future.',
            ]);
        }
    }

    protected function assertPaymentType(array $values): void
    {
        if (! empty($values['payment_type_id']) && ! PaymentType::whereKey($values['payment_type_id'])->exists()) {
            throw ValidationException::withMessages([
                'payment_type_id' => 'Choose a valid payment type.',
            ]);
        }
    }

    protected function fillPayment(Payment $payment, array $values, string $number): void
    {
        $payment->forceFill([
            'number' => $number,
            'product_type' => $values['product_type'],
            'payment_date' => $values['payment_date'],
            'payment_type_id' => $values['payment_type_id'] ?? null,
            'notes' => $values['notes'] ?? null,
        ]);
    }

    protected function preparePayment(Payment $payment, array $values): array
    {
        $settings = Setting::values('pharmacy', $payment->getConnectionName());
        $number = $this->nextNumber(
            (int) request()->attributes->get('branch_id'),
            $values['product_type'],
            $values['payment_date'],
            $settings,
        );
        $this->fillPayment($payment, $values, $number);

        return $settings;
    }

    protected function applyAllocation(
        Account $actor,
        ?StockEntry $invoice,
        int $amount,
        int $discount,
        string $paymentDate,
        int|string $index,
    ): array {
        if ($invoice === null || $invoice->voided_at !== null || $invoice->posted_at === null
            || $invoice->entry_date->toDateString() > $paymentDate) {
            throw ValidationException::withMessages([
                'allocations' => 'Choose outstanding invoices for this branch and party.',
            ]);
        }
        if ($discount > 0) {
            $this->pharmacy->authorize($actor, 'pharmacy-payments.discount');
        }
        if ($amount + $discount < 1 || $amount + $discount > $invoice->outstandingMinor()) {
            throw ValidationException::withMessages([
                "allocations.$index.amount" => 'Cash and discount must be positive and cannot exceed the outstanding amount.',
            ]);
        }

        $invoice->paid += $amount;
        $invoice->settlement_discount += $discount;
        $invoice->save();

        return [
            'amount' => $amount,
            'discount' => $discount,
        ];
    }

    protected function saveTotals(Payment $payment, array $allocations): void
    {
        $amount = array_sum(array_column($allocations, 'amount'));
        $discount = array_sum(array_column($allocations, 'discount'));
        if ($amount > 0 && $payment->payment_type_id === null) {
            throw ValidationException::withMessages([
                'payment_type_id' => 'Choose a payment type for this payment.',
            ]);
        }

        $payment->forceFill([
            'amount' => $amount,
            'discount' => $discount,
        ])->save();
    }

    protected function savePaymentItems(Payment $payment, array $allocations): void
    {
        $this->saveTotals($payment, $allocations);
        $payment->items()->forceCreateMany($allocations);
    }

    protected function nextNumber(int $branchId, string $productType, string $paymentDate, array $settings): string
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
            throw ValidationException::withMessages([
                'record' => 'The payment numbering sequence is exhausted.',
            ]);
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
        $formatted = preg_replace_callback(
            '/\{SEQ(?::([1-9]|1[0-2]))?\}/',
            fn (array $match): string => str_pad((string) $number, (int) ($match[1] ?? 6), '0', STR_PAD_LEFT),
            $formatted,
        );
        if (mb_strlen($formatted) > 40) {
            throw ValidationException::withMessages([
                'record' => 'The generated payment number is too long. Shorten its pattern in Pharmacy settings.',
            ]);
        }

        return $formatted;
    }

    protected function smsPaymentValues(Payment $payment, int|string $due): array
    {
        return [
            'invoice' => $payment->number,
            'date' => $payment->payment_date->format('d-m-Y'),
            'total' => Money::decimal($payment->amount + $payment->discount),
            'paid' => Money::decimal($payment->amount),
            'due' => Money::decimal($due),
            'amount' => Money::decimal($payment->amount),
        ];
    }

    protected function reverseAccounting(Payment $payment): void
    {
        $this->journals->reverse($payment);
        $payment->voided_at = now();
        $payment->save();
    }

    protected function reverseAllocation(?StockEntry $invoice, int $amount, int $discount): void
    {
        if ($invoice === null || $invoice->voided_at !== null
            || $invoice->paid - $amount < $invoice->refunded
            || $invoice->settlement_discount < $discount) {
            throw ValidationException::withMessages([
                'payment' => 'A linked invoice has a refund. Reverse that return before deleting or voiding this payment.',
            ]);
        }

        $invoice->paid -= $amount;
        $invoice->settlement_discount -= $discount;
        $invoice->save();
    }

    protected function translateNumberConflict(UniqueConstraintViolationException $exception, string $index): void
    {
        if (str_contains($exception->errorInfo[2] ?? '', $index)) {
            throw ValidationException::withMessages([
                'record' => 'The generated payment number already exists. Choose a distinct Payment invoice pattern in Pharmacy settings.',
            ]);
        }
    }
}
