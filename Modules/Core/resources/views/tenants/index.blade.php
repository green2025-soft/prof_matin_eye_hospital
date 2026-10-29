@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle, 'description' => 'Manage workspaces and their domains.', 'create' => ['model' => \Modules\Core\Models\Tenant::class, 'url' => route('core.tenants.create'), 'label' => 'New tenant']])
    <section class="app-card overflow-hidden">
        <div class="app-card-heading d-flex align-items-center gap-2 px-3 px-md-4 py-3 border-bottom"><i class="fa-solid fa-building text-body-secondary" aria-hidden="true"></i><h2 class="h6 mb-0">Workspace directory</h2></div>
        <form class="px-3 px-md-4 py-3 border-bottom" action="{{ route('core.tenants.index') }}" method="GET" aria-label="Filter tenants">
            <div class="row g-2 align-items-end">
                <div class="col-lg-6">
                    <label class="form-label small" for="search">Search workspaces</label>
                    <div class="app-input-wrap app-search-field">
                        <i class="fa-solid fa-magnifying-glass app-search-icon" aria-hidden="true"></i>
                        <input class="{{ inputClass('search') }}" id="search" name="search" type="search" value="{{ getValue('search', null, $filters['search'] ?? '') }}" placeholder="Workspace name starts with…" maxlength="80" {{ inputAttrs('search') }}>
                    </div>
                    @error('search')<div class="invalid-feedback d-block" id="search-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-lg-3">
                    <label class="form-label" for="filter-status">Status</label>
                    <select class="{{ inputClass('status', 'form-select') }}" id="filter-status" name="status" {{ inputAttrs('status') }}>
                        @foreach (['' => 'All statuses'] + $filterOptions['status'] as $value => $label)
                            <option value="{{ $value }}" {{ getSelected($value, 'status', null, $filters['status'] ?? '') }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status')<div class="invalid-feedback d-block" id="status-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-lg-3">@include('core::partials.filter-actions', ['routePrefix' => 'core.tenants'])</div>
            </div>
        </form>
        @if ($records->isEmpty())
            @include('core::partials.empty', ['title' => 'No tenants found', 'description' => 'Try different filters.', 'icon' => 'building'])
        @else
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <caption class="visually-hidden">Registered tenant workspaces</caption>
                    <thead><tr><th scope="col">Workspace</th><th scope="col">Domains</th><th scope="col">Administrator</th><th scope="col">Status</th><th class="text-end" scope="col">Actions</th></tr></thead>
                    <tbody>
                        @foreach ($records as $tenant)
                            <tr>
                                <th scope="row">
                                    <div class="app-table-person d-flex align-items-center gap-3">
                                        @if ($tenant->logo_url)
                                            <img class="app-table-avatar rounded-circle object-fit-cover" src="{{ $tenant->logo_url }}" width="40" height="40" alt="">
                                        @else
                                            <span class="app-table-avatar" aria-hidden="true"><i class="fa-solid fa-building"></i></span>
                                        @endif
                                        <div>
                                            <div class="fw-semibold">{{ $tenant->name }}</div>
                                            <div class="small text-body-secondary fw-normal">{{ $tenant->slug }}</div>
                                            @if ($tenant->has_branches)
                                                <span class="app-tag">Has branches</span>
                                            @endif
                                        </div>
                                    </div>
                                </th>
                                <td>@include('core::partials.tags', ['labels' => $tenant->domains->pluck('domain'), 'empty' => ''])</td>
                                <td class="small">
                                    <div>{{ $tenant->owner_email }}</div>
                                    @if ($tenant->phone)
                                        <div class="text-body-secondary">{{ $tenant->phone }}</div>
                                    @endif
                                </td>
                                <td><span class="app-status app-status-{{ $tenant->status }}">{{ $filterOptions['status'][$tenant->status] ?? $tenant->status }}</span></td>
                                <td>
                                    <div class="d-flex justify-content-end align-items-center gap-2 text-nowrap">
                                        @can('enter', $tenant)
                                            @if ($tenant->domains->isNotEmpty())
                                                <form action="{{ route('core.tenants.enter', $tenant) }}" method="POST">
                                                    @csrf
                                                    <button class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-2" type="submit" aria-label="Open {{ $tenant->name }} dashboard" title="Open dashboard"><i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i><span>Dashboard</span></button>
                                                </form>
                                            @endif
                                        @endcan
                                        @include('core::partials.row-actions', ['model' => $tenant, 'routePrefix' => 'core.tenants', 'deleteConfirmation' => 'Permanently delete '.$tenant->name.'? The tenant database, all uploads, subscriptions, invoices and payments will be permanently deleted. This cannot be undone.'])
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters])
    </section>
@endsection
