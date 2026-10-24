<?php

namespace Modules\Core\Services;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use LogicException;
use Modules\Core\Models\Account;
use Modules\Core\Models\Invoice;
use Modules\Core\Models\Plan;
use Modules\Core\Models\Subscription;
use Modules\Core\Models\Tenant;
use Throwable;

class SubscriptionService
{
    public function createUser(Tenant $tenant, Account $actor, Closure $operation): mixed
    {
        app(AccessManager::class)->assertActorContext($actor);

        if (! Tenant::current()?->is($tenant)) {
            throw new AuthorizationException;
        }

        return (new Subscription)->getConnection()->transaction(function () use ($tenant, $actor, $operation): mixed {
            $subscription = Subscription::query()->with('plan')->where('tenant_id', $tenant->getKey())->lockForUpdate()->first();

            if (! $subscription?->isUsable()) {
                throw new AuthorizationException('An active subscription is required to add an account.');
            }

            if (! $subscription->canAddUser($actor->newQuery()->count())) {
                throw ValidationException::withMessages(['account' => 'The plan account limit has been reached. Contact the platform administrator to change your plan.']);
            }

            // Keep the quota lock until the tenant account transaction finishes.
            return $operation();
        }, 1);
    }

    public function save(Account $actor, ?Model $record, array $values): Model
    {
        if ($record !== null && ! $record instanceof Subscription) {
            throw new AuthorizationException;
        }

        Gate::forUser($actor)->authorize($record?->exists ? 'update' : 'create', $record?->exists ? $record : Subscription::class);

        try {
            return (new Subscription)->getConnection()->transaction(function () use ($actor, $record, $values): Subscription {
                $tenantId = $record?->exists ? $record->tenant_id : $values['tenant_id'];
                $tenant = Tenant::query()->whereKey($tenantId)->lockForUpdate()->firstOrFail();
                $subscription = Subscription::query()->where('tenant_id', $tenant->getKey())->lockForUpdate()->first();

                if (! $record?->exists && $subscription !== null) {
                    throw ValidationException::withMessages(['tenant_id' => 'This tenant already has a subscription. Edit its existing subscription.']);
                }

                if ($record?->exists && ($subscription === null || ! $subscription->is($record))) {
                    throw (new ModelNotFoundException)->setModel(Subscription::class, [$record->getKey()]);
                }

                $subscription ??= new Subscription(['tenant_id' => $tenant->getKey()]);
                Gate::forUser($actor)->authorize($subscription->exists ? 'update' : 'create', $subscription->exists ? $subscription : Subscription::class);
                $planId = $values['plan_id'] ?? $subscription->plan_id;
                $plan = Plan::query()->whereKey($planId)->lockForUpdate()->firstOrFail();
                $changedPlan = $subscription->exists && (int) $subscription->plan_id !== (int) $plan->getKey();
                $renewal = $values['renewal'] ?? null;
                $restart = $subscription->exists && in_array($subscription->status, [Subscription::STATUS_CANCELLED, Subscription::STATUS_EXPIRED], true) && $renewal === 'renew';

                if ($changedPlan && $subscription->isUsable() && $subscription->price_minor > 0) {
                    throw ValidationException::withMessages(['plan_id' => 'Change the plan after the current access period ends. Mid-period proration is not applied.']);
                }

                if ($changedPlan && $renewal === 'now') {
                    throw ValidationException::withMessages(['renewal' => 'Enable renewal when selecting a different plan.']);
                }

                if (! $subscription->exists || $changedPlan || $restart) {
                    if (! $plan->is_active) {
                        throw ValidationException::withMessages(['plan_id' => 'Select an active plan.']);
                    }

                    if ($subscription->exists && $subscription->invoices()->whereIn('status', [Invoice::STATUS_ISSUED, Invoice::STATUS_PARTIALLY_PAID])->exists()) {
                        throw ValidationException::withMessages(['plan_id' => 'Resolve or void outstanding invoices before restarting or changing the plan.']);
                    }

                    if (! $subscription->exists && $renewal !== 'renew') {
                        throw ValidationException::withMessages(['renewal' => 'A new subscription must start with renewal enabled.']);
                    }

                    $this->start($subscription, $plan, $subscription->trial_ends_at === null);
                }

                if ($renewal === 'now') {
                    if ($subscription->status !== Subscription::STATUS_CANCELLED) {
                        $subscription->ends_at = now();
                    }

                    $subscription->status = Subscription::STATUS_CANCELLED;
                    $subscription->cancel_at_period_end = false;
                } elseif ($renewal !== null) {
                    if ($subscription->status === Subscription::STATUS_CANCELLED) {
                        throw ValidationException::withMessages(['renewal' => 'Choose automatic renewal to restart a cancelled subscription.']);
                    }

                    $subscription->cancel_at_period_end = $renewal === 'period_end';
                }

                $subscription->save();

                return $subscription->load(['tenant:id,name', 'plan:id,name']);
            }, 3);
        } catch (UniqueConstraintViolationException) {
            throw ValidationException::withMessages(['tenant_id' => 'This tenant already has a subscription.']);
        }
    }

    public function formData(Account $actor, Model $record): array
    {
        if (! $record instanceof Subscription) {
            throw new AuthorizationException;
        }

        Gate::forUser($actor)->authorize($record->exists ? 'update' : 'create', $record->exists ? $record : Subscription::class);
        $plans = Plan::query()->where('is_active', true)->orderBy('name')->orderBy('id')->limit(100)->get(['id', 'name']);

        if ($record->exists && ! $plans->contains('id', $record->plan_id)) {
            $plans->push($record->plan);
        }

        $tenants = $record->exists ? collect() : Tenant::query()
            ->whereNotIn('id', Subscription::query()->select('tenant_id'))
            ->orderBy('name')->orderBy('id')->limit(100)->get(['id', 'name']);

        return [
            'subscription' => $record,
            'plans' => $plans,
            'tenants' => $tenants,
            'hasEligibleTenants' => $record->exists || $tenants->isNotEmpty(),
            'hasPlans' => $plans->isNotEmpty(),
            'subscriptionsUrl' => Gate::forUser($actor)->allows('viewAny', Subscription::class)
                ? route('core.subscriptions.index') : null,
            'createPlanUrl' => Gate::forUser($actor)->allows('create', Plan::class)
                ? route('core.plans.create') : null,
            'renewal' => $record->status === Subscription::STATUS_CANCELLED ? 'now' : ($record->cancel_at_period_end ? 'period_end' : 'renew'),
            'statuses' => Subscription::STATUSES,
        ];
    }

    public function enrollDefault(Tenant $tenant): Subscription
    {
        return $tenant->getConnection()->transaction(function () use ($tenant): Subscription {
            Tenant::query()->whereKey($tenant->getKey())->lockForUpdate()->firstOrFail();
            $subscription = Subscription::query()->where('tenant_id', $tenant->getKey())->lockForUpdate()->first();

            if ($subscription !== null) {
                return $subscription;
            }

            $plan = Plan::query()->where('slug', config('billing.default_plan', 'starter'))->lockForUpdate()->first();

            if ($plan === null || ! $plan->is_active || $plan->price_minor !== 0) {
                throw ValidationException::withMessages(['subscription' => 'Configure an active free onboarding plan before enrolling tenants.']);
            }

            $subscription = new Subscription(['tenant_id' => $tenant->getKey()]);
            $this->start($subscription, $plan, false);

            return $subscription;
        }, 3);
    }

    public function activateFromInvoice(Subscription $subscription, Invoice $invoice): Subscription
    {
        return $subscription->getConnection()->transaction(function () use ($subscription, $invoice): Subscription {
            $record = Subscription::query()->whereKey($subscription->getKey())->lockForUpdate()->firstOrFail();
            $paid = Invoice::query()->whereKey($invoice->getKey())->lockForUpdate()->firstOrFail();

            if ((int) $paid->subscription_id !== (int) $record->getKey() || $paid->status !== Invoice::STATUS_PAID
                || (int) $paid->paid_minor < (int) $paid->total_minor) {
                throw new LogicException('Subscription activation requires its settled invoice.');
            }

            if ($record->status === Subscription::STATUS_CANCELLED || $record->current_period_starts_at === null || $record->current_period_ends_at === null
                || ! $paid->period_start->equalTo($record->current_period_starts_at) || ! $paid->period_end->equalTo($record->current_period_ends_at)
                || ! $record->current_period_ends_at->isFuture()) {
                return $record;
            }

            if ($paid->currency !== $record->currency || (int) $paid->total_minor !== $record->price_minor) {
                throw new LogicException('The settled invoice must match the current subscription price and currency.');
            }

            $record->status = Subscription::STATUS_ACTIVE;
            $record->ends_at = null;
            $record->save();

            return $record;
        }, 3);
    }

    public function syncDue(): array
    {
        $result = ['processed' => 0, 'generated' => 0, 'failed' => []];
        $query = Subscription::query()->whereIn('status', [Subscription::STATUS_TRIALING, Subscription::STATUS_ACTIVE, Subscription::STATUS_PENDING, Subscription::STATUS_PAST_DUE])
            ->where('current_period_ends_at', '<=', now());

        foreach ($query->lazyById(100) as $subscription) {
            try {
                $result['generated'] += $this->sync($subscription);
                $result['processed']++;
            } catch (Throwable $exception) {
                report($exception);
                $result['failed'][] = $subscription->getKey();
            }
        }

        return $result;
    }

    private function start(Subscription $subscription, Plan $plan, bool $allowTrial): void
    {
        $now = now()->toImmutable()->startOfSecond();

        if ($subscription->current_period_starts_at !== null && ! $now->isAfter($subscription->current_period_starts_at)) {
            $now = $subscription->current_period_starts_at->addSecond();
        }

        $subscription->plan_id = $plan->getKey();
        $subscription->price_minor = $plan->price_minor;
        $subscription->currency = $plan->currency;
        $subscription->billing_interval = $plan->billing_interval;
        $subscription->starts_at ??= $now;
        $onTrial = $allowTrial && $plan->trial_days > 0;

        if ($onTrial) {
            $subscription->trial_ends_at = $now->addDays($plan->trial_days);
        }

        $subscription->current_period_starts_at = $now;
        $subscription->current_period_ends_at = $onTrial ? $subscription->trial_ends_at : $subscription->periodEnd($now);
        $subscription->status = $onTrial ? Subscription::STATUS_TRIALING : ($subscription->price_minor === 0 ? Subscription::STATUS_ACTIVE : Subscription::STATUS_PENDING);
        $subscription->ends_at = null;
        $subscription->cancel_at_period_end = false;
        $subscription->save();

        if ($subscription->price_minor > 0 && $subscription->status !== Subscription::STATUS_TRIALING) {
            app(InvoiceService::class)->forSubscription($subscription);
        }
    }

    private function sync(Subscription $subscription): int
    {
        return $subscription->getConnection()->transaction(function () use ($subscription): int {
            $record = Subscription::query()->whereKey($subscription->getKey())->lockForUpdate()->firstOrFail();

            if (! in_array($record->status, [Subscription::STATUS_TRIALING, Subscription::STATUS_ACTIVE, Subscription::STATUS_PENDING, Subscription::STATUS_PAST_DUE], true)
                || $record->current_period_ends_at === null || $record->current_period_ends_at->isFuture()) {
                return 0;
            }

            if ($record->cancel_at_period_end || in_array($record->status, [Subscription::STATUS_PENDING, Subscription::STATUS_PAST_DUE], true)) {
                $record->status = $record->cancel_at_period_end ? Subscription::STATUS_CANCELLED : Subscription::STATUS_EXPIRED;
                $record->ends_at = $record->current_period_ends_at;
                $record->save();

                return 0;
            }

            $wasTrial = $record->status === Subscription::STATUS_TRIALING;
            $record->current_period_starts_at = $record->current_period_ends_at;
            $record->current_period_ends_at = $record->periodEnd($record->current_period_starts_at);

            if ($record->price_minor === 0) {
                while (! $record->current_period_ends_at->isFuture()) {
                    $record->current_period_starts_at = $record->current_period_ends_at;
                    $record->current_period_ends_at = $record->periodEnd($record->current_period_starts_at);
                }

                $record->status = Subscription::STATUS_ACTIVE;
            } else {
                $record->status = $wasTrial ? Subscription::STATUS_PENDING : Subscription::STATUS_PAST_DUE;
            }

            $record->save();

            if ($record->price_minor > 0) {
                return app(InvoiceService::class)->forSubscription($record)->wasRecentlyCreated ? 1 : 0;
            }

            return 0;
        }, 3);
    }
}
