<?php

namespace Modules\Core\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Modules\Core\Helpers\Money;
use Modules\Core\Models\Account;
use Modules\Core\Models\CheckoutAttempt;
use Modules\Core\Models\Invoice;
use Modules\Core\Models\Payment;
use Modules\Core\Models\Subscription;

class PaymentService
{
    public function save(Account $actor, ?Model $record, array $values): Payment
    {
        return app(AccessManager::class)->transaction($actor, function (Account $actor) use ($values): Payment {
            Gate::forUser($actor)->authorize('create', Payment::class);

            return $this->record(Invoice::findOrFail($values['invoice_id']), [
                'idempotency_key' => $values['idempotency_key'],
                'amount_minor' => Money::minor($values['amount']),
            ], $actor);
        });
    }

    /** Receives validated manual input or a verified checkout settlement. */
    public function record(Invoice $invoice, array $values, ?Account $actor = null): Payment
    {
        $values += ['provider' => 'manual', 'provider_reference' => null, 'extra_charge_minor' => 0];

        return $invoice->locked(function (Invoice $invoice, ?Subscription $subscription) use ($values, $actor): Payment {
            $existing = Payment::where('provider', $values['provider'])->where(function ($query) use ($values): void {
                $query->where('idempotency_key', $values['idempotency_key']);

                if ($values['provider_reference'] !== null) {
                    $query->orWhere('provider_reference', $values['provider_reference']);
                }
            })->first();

            if ($existing !== null) {
                if ($existing->invoice_id !== $invoice->getKey() || $existing->amount_minor !== $values['amount_minor']
                    || $existing->extra_charge_minor !== $values['extra_charge_minor'] || $existing->currency !== $invoice->currency
                    || $existing->provider_reference !== $values['provider_reference']) {
                    throw ValidationException::withMessages(['amount' => 'This payment was already recorded with different details.']);
                }

                return $existing;
            }

            $checkoutId = CheckoutAttempt::where('active_invoice_id', $invoice->getKey())->lockForUpdate()->value('id');

            if ($checkoutId !== null && $checkoutId !== ($values['checkout_id'] ?? null)) {
                throw ValidationException::withMessages(['invoice_id' => 'Complete or review the pending checkout first.']);
            }
            // dd(! $invoice->isPayable());
            // if (! $invoice->isPayable() || $values['amount_minor'] > $invoice->outstandingMinor()) {
            //     throw ValidationException::withMessages(['amount' => 'Select an unpaid invoice and enter an amount within its remaining balance.']);
            // }

            $payment = (new Payment)->forceFill([
                'invoice_id' => $invoice->getKey(),
                'tenant_id' => $invoice->tenant_id,
                'provider' => $values['provider'],
                'idempotency_key' => $values['idempotency_key'],
                'provider_reference' => $values['provider_reference'],
                'amount_minor' => $values['amount_minor'],
                'extra_charge_minor' => $values['extra_charge_minor'],
                'currency' => $invoice->currency,
                'method' => $values['provider'],
                'notes' => $values['notes'] ?? null,
                'recorded_by' => $actor?->getKey(),
                'paid_at' => now(),
            ]);
            $payment->save();

            $invoice->paid_minor += $payment->amount_minor;
            $invoice->status = $invoice->paid_minor === $invoice->total_minor ? Invoice::STATUS_PAID : Invoice::STATUS_PARTIALLY_PAID;
            $invoice->save();

            if ($subscription !== null && $invoice->status === Invoice::STATUS_PAID) {
                app(SubscriptionService::class)->activateFromInvoice($subscription, $invoice);
            }

            return $payment;
        });
    }
}
