@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle, 'description' => 'Assign a plan and control renewal. Paid access starts after invoice settlement.'])
    @if (! $hasEligibleTenants)
        <section class="app-card p-3 p-md-4">
            <h2 class="h5">No workspace needs a new subscription</h2>
            <p class="text-body-secondary">
                New workspaces receive a default subscription during setup. Change their plan by editing the existing subscription.
            </p>
            @if ($subscriptionsUrl)
                <a class="btn btn-primary" href="{{ $subscriptionsUrl }}">Manage existing subscriptions</a>
            @endif
        </section>
    @elseif (! $hasPlans)
        <section class="app-card p-3 p-md-4">
            <h2 class="h5">An active plan is required</h2>
            <p class="text-body-secondary">Create or activate a plan before assigning a subscription.</p>
            @if ($createPlanUrl)
                <a class="btn btn-primary" href="{{ $createPlanUrl }}">Create a plan</a>
            @endif
        </section>
    @else
        <form class="app-card p-3 p-md-4" action="{{ $formAction }}" method="POST">
            @csrf
            @if ($formMethod !== 'POST') @method($formMethod) @endif
            <section class="app-form-section" aria-labelledby="subscription-details-heading">
                <h2 class="app-form-section-title" id="subscription-details-heading">Subscription details</h2>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="tenant_id">Tenant <span class="text-danger" aria-hidden="true">*</span></label>
                        @if ($subscription->exists)
                            <input class="form-control" id="tenant_id" type="text" value="{{ $subscription->tenant->name }}" readonly>
                        @else
                            <input class="{{ inputClass('tenant_id') }}" id="tenant_id" name="tenant_id" type="number" value="{{ getValue('tenant_id') }}" list="subscription-tenants" min="1" step="1" required {{ inputAttrs('tenant_id', null, 'tenant-help') }}>
                            <datalist id="subscription-tenants">@foreach ($tenants as $tenant)<option value="{{ $tenant->id }}">{{ $tenant->name }}</option>@endforeach</datalist>
                            <p class="form-text" id="tenant-help">Choose a workspace or enter its numeric tenant ID.</p>
                            @error('tenant_id')<div class="invalid-feedback d-block" id="tenant_id-error">{{ $message }}</div>@enderror
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="plan_id">Plan <span class="text-danger" aria-hidden="true">*</span></label>
                        <input
                            class="{{ inputClass('plan_id') }}"
                            id="plan_id"
                            name="plan_id"
                            type="number"
                            value="{{ getValue('plan_id', $subscription) }}"
                            list="subscription-plans"
                            min="1"
                            step="1"
                            required
                            {{ inputAttrs('plan_id', null, 'plan-help') }}
                        >
                        <datalist id="subscription-plans">
                            @foreach ($plans as $plan)
                                <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                            @endforeach
                        </datalist>
                        <p class="form-text" id="plan-help">
                            Choose a suggestion or enter a plan ID from Plans. Change plans after the current access period ends; resolve existing unpaid invoices first.
                        </p>
                        @error('plan_id')<div class="invalid-feedback d-block" id="plan_id-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="renewal">Renewal <span class="text-danger" aria-hidden="true">*</span></label>
                        <select class="{{ inputClass('renewal', 'form-select') }}" id="renewal" name="renewal" required {{ inputAttrs('renewal', null, 'renewal-help') }}>
                            <option value="renew" {{ getSelected('renew', 'renewal', null, $renewal) }}>Renew automatically</option>
                            @if ($subscription->exists)
                                <option value="period_end" {{ getSelected('period_end', 'renewal', null, $renewal) }}>Cancel at the end of the current period</option>
                                <option value="now" {{ getSelected('now', 'renewal', null, $renewal) }}>Cancel access now</option>
                            @endif
                        </select>
                        <p class="form-text" id="renewal-help">Free plans renew without payment. Paid renewals create an invoice; payment is never charged automatically.</p>
                        @error('renewal')<div class="invalid-feedback d-block" id="renewal-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </section>
            @if ($subscription->exists)
                <section class="app-form-section" aria-labelledby="subscription-period-heading">
                    <h2 class="app-form-section-title" id="subscription-period-heading">Current billing period</h2>
                    <dl class="row small mb-0">
                        <dt class="col-sm-4">Status</dt><dd class="col-sm-8">{{ $statuses[$subscription->status] }}</dd>
                        <dt class="col-sm-4">Price</dt><dd class="col-sm-8">{{ $subscription->currency }} {{ $subscription->price }} / {{ \Modules\Core\Models\Plan::INTERVALS[$subscription->billing_interval] }}</dd>
                        <dt class="col-sm-4">Started</dt><dd class="col-sm-8">{{ $subscription->starts_at?->format('d M Y H:i') ?? '—' }}</dd>
                        <dt class="col-sm-4">Period</dt><dd class="col-sm-8">{{ $subscription->current_period_starts_at?->format('d M Y H:i') ?? '—' }} — {{ $subscription->current_period_ends_at?->format('d M Y H:i') ?? '—' }}</dd>
                        <dt class="col-sm-4">Trial ends</dt><dd class="col-sm-8">{{ $subscription->trial_ends_at?->format('d M Y H:i') ?? '—' }}</dd>
                    </dl>
                </section>
            @endif
            @include('core::partials.form-actions', ['model' => $subscription, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel])
        </form>
    @endif
@endsection
