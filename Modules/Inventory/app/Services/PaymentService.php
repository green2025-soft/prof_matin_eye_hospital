<?php

namespace Modules\Inventory\Services;

use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Modules\Core\Helpers\Money;
use Modules\Core\Models\Account;
use Modules\Core\Models\Setting;
use Modules\Core\Services\AccessManager;
use Modules\Hospital\Contracts\InventoryCustomers;
use Modules\Inventory\Models\Document;
use Modules\Inventory\Models\Payment;
use Modules\Inventory\Models\Supplier;

class PaymentService
{
    public function __construct(private AccessManager $access, private InventoryAccess $inventory, private InventoryCustomers $customers) {}

    public function save(Account $actor, ?Payment $record, array $values): Payment
    {
        abort_if($record !== null, 405, 'Posted payments cannot be edited.');

        try {
            return $this->access->transaction($actor, function (Account $freshActor) use ($values): Payment {
                $this->inventory->authorize($freshActor, 'inventory-payments.create');
                if (Carbon::parse($values['payment_date'])->startOfDay()->gt(now()->startOfDay())) {
                    throw ValidationException::withMessages(['payment_date' => 'The payment date cannot be in the future.']);
                }
                $payment = new Payment;
                $connection = $payment->getConnection();
                $direction = $values['direction'];
                $customerId = $direction === 'customer' ? (int) $values['customer_id'] : null;
                $supplierId = $direction === 'supplier' ? (int) $values['supplier_id'] : null;

                if ($customerId !== null && ! isset($this->customers->findMany($freshActor, [$customerId])[$customerId])) {
                    throw ValidationException::withMessages(['customer_id' => 'Select a valid customer.']);
                }
                if ($supplierId !== null && ! Supplier::query()->whereKey($supplierId)->exists()) {
                    throw ValidationException::withMessages(['supplier_id' => 'Select a valid supplier.']);
                }

                $allocations = collect($values['allocations'])->sortBy('document_id');
                $ids = $allocations->pluck('document_id')->map(fn ($id): int => (int) $id)->all();
                $documents = Document::query()->whereIn('id', $ids)->orderBy('id')->lockForUpdate()->get()->keyBy('id');
                $amount = 0;
                $discount = 0;
                $prepared = [];

                foreach ($allocations as $index => $allocation) {
                    $document = $documents->get((int) $allocation['document_id']);
                    $allowedTypes = $direction === 'customer' ? ['sale', 'customer_opening'] : ['purchase', 'supplier_opening'];
                    if ($document === null || ! in_array($document->type, $allowedTypes, true) || $document->voided_at !== null
                        || $document->document_date->toDateString() > $values['payment_date']
                        || $document->product_type !== $values['product_type']
                        || ($customerId !== null && (int) $document->customer_id !== $customerId)
                        || ($supplierId !== null && (int) $document->supplier_id !== $supplierId)) {
                        throw ValidationException::withMessages(['allocations' => 'Choose outstanding invoices for this branch and party.']);
                    }

                    $lineAmount = Money::minor($allocation['amount'] ?? '0');
                    $lineDiscount = Money::minor($allocation['discount'] ?? '0');
                    if ($lineDiscount > 0) {
                        $this->inventory->authorize($freshActor, 'inventory-payments.discount');
                    }
                    $due = $document->outstandingMinor();
                    if ($lineAmount + $lineDiscount < 1 || $lineAmount + $lineDiscount > $due) {
                        throw ValidationException::withMessages(["allocations.$index.amount" => 'Cash and discount must be positive and cannot exceed the outstanding amount.']);
                    }
                    $document->paid_minor += $lineAmount;
                    $document->settlement_discount_minor += $lineDiscount;
                    $document->save();
                    $amount += $lineAmount;
                    $discount += $lineDiscount;
                    $prepared[] = ['document_id' => $document->id, 'amount_minor' => $lineAmount, 'discount_minor' => $lineDiscount];
                }

                if ($amount > 0 && empty($values['payment_type_id'])) {
                    throw ValidationException::withMessages(['payment_type_id' => 'Choose a payment type for this payment.']);
                }
                if (! empty($values['payment_type_id']) && ! $connection->table('payment_types')->where('id', $values['payment_type_id'])->exists()) {
                    throw ValidationException::withMessages(['payment_type_id' => 'Choose a valid payment type.']);
                }
                $payment->forceFill([
                    'number' => $this->nextNumber((int) request()->attributes->get('branch_id'), $values['product_type'], $values['payment_date']),
                    'direction' => $direction, 'product_type' => $values['product_type'], 'payment_date' => $values['payment_date'],
                    'customer_id' => $customerId, 'supplier_id' => $supplierId, 'payment_type_id' => $values['payment_type_id'] ?? null,
                    'amount_minor' => $amount, 'discount_minor' => $discount, 'notes' => $values['notes'] ?? null,
                ])->save();
                foreach ($prepared as $allocation) {
                    $connection->table('inventory_payment_allocations')->insert(['payment_id' => $payment->id] + $allocation);
                }

                return $payment;
            });
        } catch (UniqueConstraintViolationException $exception) {
            if (str_contains($exception->errorInfo[2] ?? '', 'inventory_payments_number_unique')) {
                throw ValidationException::withMessages(['record' => 'The generated payment number already exists. Choose a distinct Payment invoice pattern in Inventory settings.']);
            }

            throw $exception;
        }
    }

    private function nextNumber(int $branchId, string $productType, string $paymentDate): string
    {
        $connection = (new Payment)->getConnection();
        $scope = 'payment:'.$branchId;
        $sequences = $connection->table('inventory_sequences');
        $sequences->insertOrIgnore(['scope' => $scope, 'last_number' => 0]);
        $sequence = (clone $sequences)->where('scope', $scope)->lockForUpdate()->first();
        $number = (int) $sequence->last_number + 1;
        if ($number > 999999999999) {
            throw ValidationException::withMessages(['record' => 'The payment numbering sequence is exhausted.']);
        }
        (clone $sequences)->where('scope', $scope)->update(['last_number' => $number]);

        $settings = Setting::values('inventory', $connection->getName());
        $pattern = $settings['payment_invoice_pattern'] ?? 'PAY-{BRANCH}-{SEQ:6}';
        $date = Carbon::parse($paymentDate, config('app.timezone'));
        $formatted = strtr($pattern, [
            '{YYYY}' => $date->format('Y'), '{MM}' => $date->format('m'), '{DD}' => $date->format('d'),
            '{BRANCH}' => (string) $branchId, '{PRODUCT}' => $productType === 'medicine' ? 'MED' : 'OPT',
        ]);
        $formatted = preg_replace_callback('/\{SEQ(?::([1-9]|1[0-2]))?\}/', fn (array $match): string => str_pad((string) $number, (int) ($match[1] ?? 6), '0', STR_PAD_LEFT), $formatted);
        if (mb_strlen($formatted) > 40) {
            throw ValidationException::withMessages(['record' => 'The generated payment number is too long. Shorten its pattern in Inventory settings.']);
        }

        return $formatted;
    }

    public function reverse(Account $actor, Payment $record): void
    {
        $this->access->transaction($actor, function (Account $freshActor) use ($record): void {
            $this->inventory->authorize($freshActor, 'inventory-payments.void');
            $payment = Payment::query()->whereKey($record->id)->lockForUpdate()->firstOrFail();
            if ($payment->voided_at !== null) {
                throw ValidationException::withMessages(['payment' => 'This payment has already been voided.']);
            }
            $allocations = $payment->allocations()->orderBy('document_id')->get();
            $documents = Document::query()->whereIn('id', $allocations->pluck('document_id'))->orderBy('id')->lockForUpdate()->get()->keyBy('id');
            foreach ($allocations as $allocation) {
                $document = $documents->get($allocation->document_id);
                if ($document === null || $document->voided_at !== null
                    || $document->paid_minor - $allocation->amount_minor < $document->refunded_minor
                    || $document->settlement_discount_minor < $allocation->discount_minor) {
                    throw ValidationException::withMessages(['payment' => 'A linked invoice has a refund. Reverse that return before voiding this payment.']);
                }
                $document->paid_minor -= $allocation->amount_minor;
                $document->settlement_discount_minor -= $allocation->discount_minor;
                $document->save();
            }
            $payment->voided_at = now();
            $payment->save();
        });
    }
}
