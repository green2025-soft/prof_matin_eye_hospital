@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle, 'description' => 'Manage subscription pricing, limits, and available features.', 'create' => ['model' => \Modules\Core\Models\Plan::class, 'url' => route('core.plans.create'), 'label' => 'New plan']])
    <section class="app-card overflow-hidden">
        <form class="px-3 px-md-4 py-3 border-bottom" action="{{ route($routePrefix.'.index') }}" method="GET" aria-label="Filter plans">
            <div class="row g-2 align-items-end">
                <div class="col-lg-5">
                    <label class="form-label small" for="search">Search plans</label>
                    <div class="app-input-wrap app-search-field">
                        <i class="fa-solid fa-magnifying-glass app-search-icon" aria-hidden="true"></i>
                        <input class="{{ inputClass('search') }}" id="search" name="search" type="search" value="{{ getValue('search', null, $filters['search'] ?? '') }}" placeholder="Name or slug starts with..." maxlength="80" {{ inputAttrs('search') }}>
                    </div>
                    @error('search')<div class="invalid-feedback d-block" id="search-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-lg-2">
                    <label class="form-label small" for="billing_interval">Interval</label>
                    <select class="{{ inputClass('billing_interval', 'form-select') }}" id="billing_interval" name="billing_interval" {{ inputAttrs('billing_interval') }}>
                        <option value="">All intervals</option>
                        @foreach (\Modules\Core\Models\Plan::INTERVALS as $value => $label)
                            <option value="{{ $value }}" {{ getSelected($value, 'billing_interval', null, $filters['billing_interval'] ?? '') }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('billing_interval')<div class="invalid-feedback d-block" id="billing_interval-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-lg-2">
                    <label class="form-label small" for="is_active">Availability</label>
                    <select class="{{ inputClass('is_active', 'form-select') }}" id="is_active" name="is_active" {{ inputAttrs('is_active') }}>
                        <option value="">All plans</option>
                        <option value="1" {{ getSelected(1, 'is_active', null, $filters['is_active'] ?? '') }}>Active</option>
                        <option value="0" {{ getSelected(0, 'is_active', null, $filters['is_active'] ?? '') }}>Inactive</option>
                    </select>
                    @error('is_active')<div class="invalid-feedback d-block" id="is_active-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-lg-3">@include('core::partials.filter-actions', ['routePrefix' => $routePrefix])</div>
            </div>
        </form>
        @if ($records->isEmpty())
            @include('core::partials.empty', ['title' => 'No plans found', 'description' => 'Try different filters or create a plan.', 'icon' => 'layer-group'])
        @else
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <caption class="visually-hidden">Subscription plan catalog</caption>
                    <thead><tr><th scope="col">Plan</th><th scope="col">Price</th><th scope="col">Trial</th><th scope="col">Users</th><th scope="col">Availability</th><th class="text-end" scope="col">Actions</th></tr></thead>
                    <tbody>
                        @foreach ($records as $plan)
                            <tr>
                                <th scope="row"><div class="fw-semibold">{{ $plan->name }}</div><div class="small text-body-secondary fw-normal">{{ $plan->slug }}</div></th>
                                <td>{{ $plan->currency }} {{ $plan->price }}<div class="small text-body-secondary">{{ \Modules\Core\Models\Plan::INTERVALS[$plan->billing_interval] }}</div></td>
                                <td>{{ $plan->trial_days }} days</td>
                                <td>{{ $plan->user_limit ?? 'Unlimited' }}</td>
                                <td><span class="app-status app-status-{{ $plan->is_active ? 'active' : 'suspended' }}">{{ $plan->is_active ? 'Active' : 'Inactive' }}</span></td>
                                <td>@include('core::partials.row-actions', ['model' => $plan, 'routePrefix' => $routePrefix])</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters])
    </section>
@endsection
