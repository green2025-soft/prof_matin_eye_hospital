@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => 'Automatic posting mappings'])
    <section class="app-card overflow-hidden">
        <form class="p-3 border-bottom" action="{{ route($routePrefix.'.index') }}" method="GET">
            <div class="row g-2 align-items-end">
                <div class="col-sm"><label class="form-label small" for="search">Feature name</label><input class="form-control" id="search" name="search" value="{{ $filters['search'] ?? '' }}" maxlength="80"></div>
                <div class="col-sm-4 col-lg-3">
                    <label class="form-label small" for="module">Module</label>
                    <select class="form-select" id="module" name="module">
                        <option value="">All modules</option>
                        @foreach ($filterOptions['module'] as $module)
                            <option value="{{ $module }}" @selected(($filters['module'] ?? '') === $module)>{{ ucfirst($module) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-auto">@include('core::partials.filter-actions', ['routePrefix' => $routePrefix])</div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table app-table align-middle mb-0">
                <thead><tr><th>Feature</th><th>Module</th><th>Voucher type</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                    @forelse ($records as $mapping)
                        <tr>
                            <th scope="row">{{ $mapping->name }}<div class="small fw-normal text-body-secondary">{{ $mapping->key }}</div></th>
                            <td>{{ ucfirst($mapping->module) }}</td><td>{{ $mapping->voucherType->name }}</td>
                            <td><span class="badge {{ $mapping->active ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $mapping->active ? 'Enabled' : 'Disabled' }}</span></td>
                            <td>@include('core::partials.row-actions', ['model' => $mapping, 'routePrefix' => $routePrefix])</td>
                        </tr>
                    @empty
                        <tr><td class="text-center text-body-secondary py-4" colspan="5">No posting mappings found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters])
    </section>
@endsection
