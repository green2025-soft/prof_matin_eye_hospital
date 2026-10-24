@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle, 'description' => 'Manage tenant access periods and renewal settings.', 'create' => ['model' => \Modules\Core\Models\Subscription::class, 'url' => route('core.subscriptions.create'), 'label' => 'Assign subscription']])
    <section class="app-card overflow-hidden">
        <form class="px-3 px-md-4 py-3 border-bottom" action="{{ route($routePrefix.'.index') }}" method="GET" aria-label="Filter subscriptions">
            <div class="row g-2 align-items-end">
                <div class="col-md-8">
                    <label class="form-label small" for="status">Status</label>
                    <select class="{{ inputClass('status', 'form-select') }}" id="status" name="status" {{ inputAttrs('status') }}>
                        <option value="">All statuses</option>
                        @foreach ($filterOptions['status'] as $value => $label)<option value="{{ $value }}" {{ getSelected($value, 'status', null, $filters['status'] ?? '') }}>{{ $label }}</option>@endforeach
                    </select>
                    @error('status')<div class="invalid-feedback d-block" id="status-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">@include('core::partials.filter-actions', ['routePrefix' => $routePrefix])</div>
            </div>
        </form>
        @if ($records->isEmpty())
            @include('core::partials.empty', ['title' => 'No subscriptions found', 'description' => 'Try another status or assign a subscription to a tenant.', 'icon' => 'calendar-check'])
        @else
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <caption class="visually-hidden">Tenant subscriptions and current access periods</caption>
                    <thead><tr><th scope="col">Tenant</th><th scope="col">Plan</th><th scope="col">Status</th><th scope="col">Price</th><th scope="col">Period ends</th><th class="text-end" scope="col">Actions</th></tr></thead>
                    <tbody>
                        @foreach ($records as $subscription)
                            <tr>
                                <th scope="row"><div class="fw-semibold">{{ $subscription->tenant->name }}</div><div class="small text-body-secondary fw-normal">#{{ $subscription->id }}</div></th>
                                <td>{{ $subscription->plan->name }}</td>
                                <td><span class="app-status app-status-{{ $subscription->status }}">{{ $filterOptions['status'][$subscription->status] }}</span>@if ($subscription->cancel_at_period_end)<div class="small text-body-secondary">Cancels at period end</div>@endif</td>
                                <td>{{ $subscription->currency }} {{ $subscription->price }}<div class="small text-body-secondary">{{ \Modules\Core\Models\Plan::INTERVALS[$subscription->billing_interval] }}</div></td>
                                <td class="small text-nowrap">{{ $subscription->current_period_ends_at?->format('d M Y H:i') ?? '—' }}</td>
                                <td>@include('core::partials.row-actions', ['model' => $subscription, 'routePrefix' => $routePrefix])</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters])
    </section>
@endsection
