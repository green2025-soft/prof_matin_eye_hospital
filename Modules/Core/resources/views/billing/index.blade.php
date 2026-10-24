@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', [
        'title' => $pageTitle,
        'description' => 'Review your subscription, invoices, and payment submissions.',
    ])

    @if ($currentSubscription)
        <section class="app-card p-3 p-md-4 mb-4" aria-labelledby="subscription-heading">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
                <div>
                    <div class="small text-body-secondary mb-1">Current plan</div>
                    <h2 class="h5 mb-0" id="subscription-heading">{{ $currentSubscription['planName'] }}</h2>
                </div>
                <span class="app-tag">{{ $currentSubscription['status'] }}</span>
            </div>
            <dl class="row gy-2 mb-0">
                <dt class="col-sm-4 col-lg-2">Price</dt>
                <dd class="col-sm-8 col-lg-4">
                    {{ $currentSubscription['currency'] }} {{ $currentSubscription['price'] }}
                    <span class="text-body-secondary">/ {{ $currentSubscription['interval'] }}</span>
                </dd>
                <dt class="col-sm-4 col-lg-2">Account limit</dt>
                <dd class="col-sm-8 col-lg-4">{{ $currentSubscription['userLimit'] ?? 'Unlimited' }}</dd>
                <dt class="col-sm-4 col-lg-2">Current period</dt>
                <dd class="col-sm-8 col-lg-10">
                    {{ $currentSubscription['periodStart'] ?? 'Not started' }}
                    @if ($currentSubscription['periodEnd'])
                        to {{ $currentSubscription['periodEnd'] }}
                    @endif
                </dd>
                @if ($currentSubscription['trialEnd'])
                    <dt class="col-sm-4 col-lg-2">Trial ends</dt>
                    <dd class="col-sm-8 col-lg-10">{{ $currentSubscription['trialEnd'] }}</dd>
                @endif
                @if ($currentSubscription['features'] !== [])
                    <dt class="col-sm-4 col-lg-2">Included features</dt>
                    <dd class="col-sm-8 col-lg-10">
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($currentSubscription['features'] as $feature)
                                <span class="app-tag">{{ $feature }}</span>
                            @endforeach
                        </div>
                    </dd>
                @endif
            </dl>
            @if ($currentSubscription['cancelAtPeriodEnd'])
                <p class="small text-body-secondary border-top mt-3 pt-3 mb-0">
                    This subscription is scheduled to end after the current billing period.
                </p>
            @endif
        </section>
    @else
        <div class="alert alert-secondary" role="status">
            A subscription has not been assigned to this workspace. Contact the platform administrator.
        </div>
    @endif

    @include('core::invoices.summary')

    <section class="app-card overflow-hidden" aria-labelledby="billing-invoices-heading">
        <div class="app-card-heading p-3 border-bottom">
            <h2 class="h6 mb-0" id="billing-invoices-heading">Invoices</h2>
        </div>
        <form class="p-3 border-bottom" action="{{ route($routePrefix.'.index') }}" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label" for="search">Invoice number</label>
                    <input
                        class="{{ inputClass('search') }}"
                        id="search"
                        name="search"
                        type="search"
                        value="{{ getValue('search', null, $filters['search'] ?? '') }}"
                        placeholder="Number starts with..."
                        {{ inputAttrs('search') }}
                    >
                    @error('search')
                        <div class="invalid-feedback d-block" id="search-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="status">Status</label>
                    <select class="{{ inputClass('status', 'form-select') }}" id="status" name="status" {{ inputAttrs('status') }}>
                        <option value="">All statuses</option>
                        @foreach ($filterOptions['status'] as $value => $label)
                            <option value="{{ $value }}" {{ getSelected($value, 'status', null, $filters['status'] ?? '') }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="invalid-feedback d-block" id="status-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    @include('core::partials.filter-actions', ['routePrefix' => $routePrefix])
                </div>
            </div>
        </form>
        @if ($records->isEmpty())
            @include('core::partials.empty', [
                'title' => 'No invoices found',
                'description' => 'Issued invoices appear here. Try changing the filters.',
                'icon' => 'file-invoice',
            ])
        @else
            @include('core::invoices.table', [
                'records' => $records,
                'statusOptions' => $filterOptions['status'],
                'invoiceRoutePrefix' => $invoiceRoutePrefix,
                'showWorkspace' => false,
            ])
        @endif
        @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters])
    </section>

    @include('core::invoices.manual-submissions')
@endsection
