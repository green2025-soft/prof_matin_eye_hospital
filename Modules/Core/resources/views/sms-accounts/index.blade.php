@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    <header class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
        <h1 class="h4 mb-0">{{ $pageTitle }}</h1>
        @can('manageAccounts', $accountModel)
            <a class="btn btn-primary" href="{{ route('core.sms-accounts.create') }}"><i class="fa-solid fa-plus me-2" aria-hidden="true"></i>New account</a>
        @endcan
    </header>
    <section class="app-card overflow-hidden">
        <form class="row g-2 align-items-end p-3 border-bottom" method="GET" action="{{ route('core.sms-accounts.index') }}">
            <div class="col-md-8">
                <label class="form-label" for="search">Tenant</label>
                <input class="form-control" id="search" name="search" value="{{ $filters['search'] ?? '' }}" maxlength="80">
            </div>
            <div class="col-md-4">@include('core::partials.filter-actions')</div>
        </form>
        <div class="table-responsive overflow-y-hidden">
            <table class="table app-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Tenant</th>
                        <th>Status</th>
                        <th class="text-end">Balance</th>
                        <th class="text-end">Rate / part</th>
                        <th class="text-end">Monthly parts</th>
                        <th class="text-end">Allowed features</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $tenant)
                        @php($account = $tenant->smsAccount)
                        <tr>
                            <th scope="row">{{ $tenant->name }}<div class="small fw-normal text-body-secondary">{{ $tenant->slug }}</div></th>
                            <td>
                                @if ($account)
                                    <span class="badge {{ $account->enabled ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $account->enabled ? 'Enabled' : 'Disabled' }}</span>
                                @else
                                    <span class="badge text-bg-secondary">Not configured</span>
                                @endif
                            </td>
                            <td class="text-end text-nowrap">BDT {{ moneyDecimal($account?->balance ?? 0) }}</td>
                            <td class="text-end text-nowrap">{{ $account ? moneyDecimal($account->rate) : '—' }}</td>
                            <td class="text-end">{{ $account ? ($account->monthly_limit ?? 'Unlimited') : '—' }}</td>
                            <td class="text-end">{{ count($account?->allowed_features ?? []) }}</td>
                            <td class="text-end">
                                @can('manageAccounts', $account ?? $accountModel)
                                    @if ($account)
                                        <a class="btn btn-outline-primary btn-sm" href="{{ route('core.sms-accounts.edit', $account) }}">Edit</a>
                                    @else
                                        <a class="btn btn-outline-primary btn-sm" href="{{ route('core.sms-accounts.create', ['tenant_id' => $tenant->id]) }}">Configure</a>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-body-secondary py-4">No tenants found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @include('core::partials.pagination', ['paginator' => $records])
    </section>
@endsection
