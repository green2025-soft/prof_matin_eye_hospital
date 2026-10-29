@extends('core::layouts.app')

@section('title', $isTenantUser ? 'Users' : $pageTitle)

@section('content')
    @include('core::partials.heading', [
        'title' => $isTenantUser ? 'Users' : $pageTitle,
        'description' => $isTenantUser ? 'Manage users and the access they have.' : 'Manage accounts and the access they have.',
        'create' => ['model' => $accountModel, 'url' => route($routePrefix.'.create'), 'label' => $isTenantUser ? 'New user' : 'New account'],
    ])
    <section class="app-card overflow-hidden">
        <div class="app-card-heading d-flex align-items-center gap-2 px-3 px-md-4 py-3 border-bottom"><i class="fa-solid fa-users text-body-secondary" aria-hidden="true"></i><h2 class="h6 mb-0">{{ $isTenantUser ? 'User directory' : 'Account directory' }}</h2></div>
        <form class="px-3 px-md-4 py-3 border-bottom" action="{{ route($routePrefix.'.index') }}" method="GET" aria-label="{{ $isTenantUser ? 'Filter users' : 'Filter accounts' }}">
            <div class="row g-2 align-items-end">
                <div class="col-md-8 col-xl-7">
                    <label class="form-label small" for="search">{{ $isTenantUser ? 'Search users' : 'Search accounts' }}</label>
                    <div class="app-input-wrap app-search-field">
                        <i class="fa-solid fa-magnifying-glass app-search-icon" aria-hidden="true"></i>
                        <input class="{{ inputClass('search') }}" id="search" name="search" type="search" value="{{ getValue('search', null, $filters['search'] ?? '') }}" placeholder="Name or email starts with…" maxlength="80" {{ inputAttrs('search') }}>
                    </div>
                    @error('search')<div class="invalid-feedback d-block" id="search-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4 col-xl-5">@include('core::partials.filter-actions', ['routePrefix' => $routePrefix])</div>
            </div>
        </form>
        @if ($records->isEmpty())
            @include('core::partials.empty', ['title' => $isTenantUser ? 'No users found' : 'No accounts found', 'description' => 'Try another search or clear the filters.', 'icon' => 'users'])
        @else
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <caption class="visually-hidden">{{ $isTenantUser ? 'Users, assigned roles, and branch access' : 'User accounts and assigned roles' }}</caption>
                    <thead>
                        <tr>
                            <th scope="col">{{ $isTenantUser ? 'User' : 'Account' }}</th>
                            <th scope="col">Roles</th>
                            @if ($isTenantUser)
                                <th scope="col">Branch</th>
                                <th scope="col">Redirect URL</th>
                            @endif
                            <th scope="col">Created</th>
                            <th class="text-end" scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $account)
                            <tr>
                                <th scope="row">
                                    <div class="app-table-person d-flex align-items-center gap-3">
                                        <span class="app-table-avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr($account->name, 0, 1)) }}</span>
                                        <div><div class="fw-semibold">{{ $account->name }}</div><div class="small text-body-secondary fw-normal">{{ $account->email }}</div></div>
                                    </div>
                                </th>
                                <td>@include('core::partials.tags', ['labels' => $account->roles->pluck('name'), 'empty' => 'No roles'])</td>
                                @if ($isTenantUser)
                                    <td class="small text-body-secondary">{{ $account->branch?->name ?? 'None' }}</td>
                                    <td class="small text-body-secondary text-break">{{ $account->redirect_url ?: 'Dashboard' }}</td>
                                @endif
                                <td class="small text-body-secondary text-nowrap">{{ $account->created_at?->format('d M Y') ?? '—' }}</td>
                                <td>@include('core::partials.row-actions', ['model' => $account, 'routePrefix' => $routePrefix])</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters])
    </section>
@endsection
