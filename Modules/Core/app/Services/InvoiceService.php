<?php

namespace Modules\Core\Services;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Modules\Core\Helpers\Money;
use Modules\Core\Models\Account;
use Modules\Core\Models\Admin;
use Modules\Core\Models\CheckoutAttempt;
use Modules\Core\Models\Invoice;
use Modules\Core\Models\InvoiceSequence;
use Modules\Core\Models\Payment;
use Modules\Core\Models\Setting;
use Modules\Core\Models\Subscription;
use Modules\Core\Models\Tenant;
use Modules\Core\Policies\BillingPolicy;

class InvoiceService
{
    public function save(Account $actor, ?Model $record, array $values): Invoice
    {
        return (new Invoice)->getConnection()->transaction(function () use ($actor, $record, $values): Invoice {
            $invoice = $record?->exists
                ? Invoice::whereKey($record->getKey())
                    ->lockForUpdate()
                    ->firstOrFail()
                : new Invoice;

            Gate::forUser($actor)->authorize(
                $invoice->exists ? 'update' : 'create',
                $invoice->exists ? $invoice : Invoice::class,
            );

            if (! $invoice->exists) {
                $invoice->tenant_id = $values['tenant_id'];
                $invoice->currency = $this->billing()['currency'] ?? 'BDT';
            }

            $invoice->due_at = $values['due_at'] ?? null;
            $invoice->notes = $values['notes'] ?? null;
            $invoice->subtotal_minor = $invoice->total_minor = $values['total_minor'];
            $invoice->save();

            // Only draft-owned children are replaced; issued history is never deleted.
            $invoice->items()->delete();
            $invoice->items()->createMany($values['items']);

            if (($values['status'] ?? Invoice::STATUS_DRAFT) === Invoice::STATUS_ISSUED) {
                Gate::forUser($actor)->authorize('issue', $invoice);

                return $this->issueLocked($invoice);
            }

            return $invoice->load('items');
        }, 3);
    }

    public function forSubscription(Subscription $subscription): Invoice
    {
        return $subscription->getConnection()->transaction(function () use ($subscription): Invoice {
            $current = Subscription::whereKey($subscription->getKey())
                ->lockForUpdate()
                ->firstOrFail();
            $start = $current->current_period_starts_at;
            $end = $current->current_period_ends_at;

            if ($start === null || $end === null || ! $end->greaterThan($start)
                || $current->price_minor < 1 || $current->price_minor > Invoice::MAX_MINOR) {
                throw ValidationException::withMessages(['subscription' => 'Stage a valid paid subscription period before invoicing.']);
            }

            $existing = Invoice::where('subscription_id', $current->getKey())
                ->where('period_start', $start)
                ->where('period_end', $end)
                ->lockForUpdate()
                ->first();

            if ($existing !== null) {
                return $existing;
            }

            if (! in_array($current->status, ['pending', 'past_due'], true)) {
                throw ValidationException::withMessages(['subscription' => 'Only a staged unpaid period can create a subscription invoice.']);
            }

            $current->loadMissing('plan');

            $invoice = new Invoice;
            $invoice->tenant_id = $current->tenant_id;
            $invoice->subscription_id = $current->getKey();
            $invoice->currency = $current->currency;
            $invoice->period_start = $start;
            $invoice->period_end = $end;
            $invoice->due_at = $start->toDateString();
            $invoice->subtotal_minor = $invoice->total_minor = $current->price_minor;
            $invoice->save();
            $invoice->items()->create([
                'description' => ($current->plan?->name ?? 'Subscription').' — '.$current->billing_interval.' subscription',
                'quantity' => 1,
                'unit_amount_minor' => $current->price_minor,
                'line_total_minor' => $current->price_minor,
                'position' => 0,
            ]);

            return $this->issueLocked($invoice);
        }, 3);
    }

    public function issue(Account $actor, Invoice $invoice): Invoice
    {
        Gate::forUser($actor)->authorize('issue', $invoice);

        return $invoice->locked(function (Invoice $current) use ($actor): Invoice {
            Gate::forUser($actor)->authorize('issue', $current);

            return $this->issueLocked($current);
        });
    }

    public function void(Account $actor, Invoice $invoice, string $reason): Invoice
    {
        Gate::forUser($actor)->authorize('void', $invoice);

        return $invoice->locked(function (Invoice $current) use ($actor, $reason): Invoice {
            Gate::forUser($actor)->authorize('void', $current);

            $activeCheckout = CheckoutAttempt::where('active_invoice_id', $current->getKey())
                ->lockForUpdate()
                ->first();

            if ($activeCheckout !== null) {
                throw ValidationException::withMessages(['invoice' => 'Resolve the active checkout before voiding this invoice.']);
            }

            if ($current->paid_minor !== 0 || $current->payments()->exists()) {
                throw ValidationException::withMessages(['invoice' => 'An invoice with recorded payments cannot be voided.']);
            }

            $current->status = Invoice::STATUS_VOID;
            $current->void_reason = $reason;
            $current->voided_at = now();
            $current->save();

            return $current;
        });
    }

    public function formData(Account $actor, Model $record): array
    {
        if (! $record instanceof Invoice) {
            throw new AuthorizationException;
        }

        Gate::forUser($actor)->authorize($record->exists ? 'update' : 'create', $record->exists ? $record : Invoice::class);

        return [
            'invoice' => $record,
            'tenantSuggestions' => Tenant::orderBy('name')
                ->orderBy('id')
                ->limit(100)
                ->get(['id', 'name']),
            'currency' => $record->currency ?? ($this->billing()['currency'] ?? 'BDT'),
            'items' => $record->items,
        ];
    }

    public function showData(Account $actor, Invoice $invoice): array
    {
        Gate::forUser($actor)->authorize('view', $invoice);
        $invoice->load(['items', 'tenant:id,name,owner_email']);
        $payments = $invoice->payments()
            ->limit(20)
            ->get();

        return [
            'invoice' => $invoice,
            'pageTitle' => $invoice->number ?? 'Invoice #'.$invoice->getKey(),
            'statusLabel' => Invoice::STATUSES[$invoice->status],
            'merchant' => $invoice->merchant_snapshot ?? $this->merchant(),
            'customer' => $invoice->customer_snapshot ?? [
                'name' => $invoice->tenant->name,
                'email' => $invoice->tenant->owner_email,
            ],
            'items' => $invoice->items,
            'subtotal' => Money::decimal($invoice->subtotal_minor),
            'total' => Money::decimal($invoice->total_minor),
            'paid' => Money::decimal($invoice->paid_minor),
            'balance' => Money::decimal($invoice->outstandingMinor()),
            'issuedDate' => $invoice->issued_at?->format('d M Y'),
            'dueDate' => $invoice->due_at?->format('d M Y'),
            'periodLabel' => $invoice->period_start && $invoice->period_end
                ? $invoice->period_start->format('d M Y').' – '.$invoice->period_end->format('d M Y')
                : null,
            'payments' => $payments,
            'canRecordPayment' => $invoice->isPayable() && Gate::forUser($actor)->allows('create', Payment::class),
            'checkout' => app(CheckoutService::class)->options($actor, $invoice),
        ];
    }

    public function indexData(Account $actor, array $filters): array
    {
        Gate::forUser($actor)->authorize('viewAny', Invoice::class);

        return $this->overviewData($actor, $filters);
    }

    public function billingData(Account $actor, array $filters): array
    {
        $tenant = Tenant::current();

        if ($actor instanceof Admin || $tenant === null || ! app(BillingPolicy::class)->view($actor)) {
            throw new AuthorizationException;
        }

        $subscription = Subscription::forTenant($tenant);

        return $this->overviewData($actor, $filters, $tenant, $subscription) + ['subscription' => $subscription];
    }

    private function overviewData(Account $actor, array $filters, ?Tenant $tenant = null, ?Subscription $subscription = null): array
    {
        $isPlatform = $tenant === null;
        $model = new Invoice;
        $query = $model->listQuery();

        if ($tenant !== null) {
            $query->where('tenant_id', $tenant->getKey())
                ->whereNotNull('issued_at');
        }

        $summaries = (clone $query)
            ->withoutEagerLoads()
            ->reorder()
            ->select('currency')
            ->selectRaw('COUNT(*) as invoice_count, SUM(total_minor) as total, SUM(paid_minor) as paid, SUM(total_minor - paid_minor) as balance')
            ->whereIn('status', [Invoice::STATUS_ISSUED, Invoice::STATUS_PARTIALLY_PAID, Invoice::STATUS_PAID])
            ->groupBy('currency')
            ->orderBy('currency')
            ->toBase()
            ->get();

        $canReviewPayments = $isPlatform && Gate::forUser($actor)->allows('create', Payment::class);
        $manualSubmissions = collect();

        if (! $isPlatform || $canReviewPayments) {
            $submissions = CheckoutAttempt::where('provider', 'manual')
                ->with('invoice:id,number,tenant_id,issued_at')
                ->orderByDesc('id')
                ->limit(20);

            if ($isPlatform) {
                $submissions->where('status', CheckoutAttempt::STATUS_REVIEW);
            } else {
                $submissions->whereHas('invoice', fn (Builder $invoices) => $invoices
                    ->where('tenant_id', $tenant->getKey())
                    ->whereNotNull('issued_at'));
            }

            $manualSubmissions = $submissions->get(['id', 'invoice_id', 'status', 'submitted_reference', 'amount_minor', 'extra_charge_minor', 'currency', 'created_at', 'notes']);
        }

        return [
            'pageTitle' => $isPlatform ? 'Invoices' : 'Billing',
            'isPlatform' => $isPlatform,
            'records' => $model->paginateRecords($filters, $query),
            'filterOptions' => $model->queryContract()['filterable'],
            'invoiceRoutePrefix' => $isPlatform ? 'core.invoices' : 'tenantcore.billing',
            'summaries' => $summaries,
            'currentSubscription' => $subscription === null ? null : [
                'planName' => $subscription->plan->name,
                'status' => Subscription::STATUSES[$subscription->status],
                'price' => $subscription->price,
                'currency' => $subscription->currency,
                'interval' => $subscription->billing_interval === 'yearly' ? 'Yearly' : 'Monthly',
                'periodStart' => $subscription->current_period_starts_at?->format('d M Y'),
                'periodEnd' => $subscription->current_period_ends_at?->format('d M Y'),
                'trialEnd' => $subscription->trial_ends_at?->format('d M Y'),
                'cancelAtPeriodEnd' => $subscription->cancel_at_period_end,
                'userLimit' => $subscription->plan->user_limit,
                'features' => array_keys(array_filter($subscription->plan->features ?? [])),
            ],
            'canReviewPayments' => $canReviewPayments,
            'manualSubmissions' => $manualSubmissions,
        ];
    }

    private function issueLocked(Invoice $invoice): Invoice
    {
        $billing = $this->billing();
        $year = (int) now()->format('Y');

        InvoiceSequence::firstOrCreate(['year' => $year], ['counter' => 0]);
        $sequence = InvoiceSequence::where('year', $year)
            ->lockForUpdate()
            ->firstOrFail();
        $sequence->counter++;
        $sequence->save();

        $prefix = $billing['invoice_prefix'] ?? 'INV';

        $tenant = Tenant::findOrFail($invoice->tenant_id);
        $invoice->number = $prefix.'-'.$year.'-'.str_pad((string) $sequence->counter, 6, '0', STR_PAD_LEFT);
        $invoice->merchant_snapshot = $this->merchant();
        $invoice->customer_snapshot = ['name' => $tenant->name, 'email' => $tenant->owner_email];
        $invoice->payment_instructions = $billing['payment_instructions'] ?? '';
        $invoice->status = match (true) {
            $invoice->paid_minor >= $invoice->total_minor => Invoice::STATUS_PAID,
            $invoice->paid_minor > 0 => Invoice::STATUS_PARTIALLY_PAID,
            default => Invoice::STATUS_ISSUED,
        };
        $invoice->issued_at = now();
        $invoice->save();

        return $invoice;
    }

    private function billing(): array
    {
        return Setting::values('billing', 'central');
    }

    private function merchant(): array
    {
        $values = Setting::values('company', 'central');

        return [
            'name' => $values['name'] ?? config('app.name'),
            'email' => $values['email'] ?? config('mail.from.address'),
            'phone' => $values['phone'] ?? '',
            'address' => $values['address'] ?? '',
            'tax_id' => $values['tax_id'] ?? '',
        ];
    }
}
