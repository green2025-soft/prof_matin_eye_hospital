@extends('core::layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="app-page-heading">
        <div>
            <p class="app-page-kicker">{{ $contextName }}</p>
            <h1>Dashboard</h1>
            <p class="text-body-secondary mb-0">Welcome back, {{ $accountName }}. Your workspace at a glance.</p>
        </div>
        <time class="small text-body-secondary" datetime="{{ $dateIso }}"><i class="fa-solid fa-calendar-days me-2" aria-hidden="true"></i>{{ $dateLabel }}</time>
    </div>

    <section class="app-stat-grid" aria-label="Workspace overview">
        @foreach ($metrics as $metric)
            <article class="app-stat-card">
                <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
                    <span class="app-stat-label">{{ $metric['label'] }}</span>
                    <span class="app-stat-icon app-tone-{{ $metric['tone'] }}"><i class="fa-solid fa-{{ $metric['icon'] }}" aria-hidden="true"></i></span>
                </div>
                <p class="app-stat-value">{{ number_format($metric['value']) }}</p>
                <div class="d-flex align-items-center justify-content-between gap-3">
                    <span class="app-stat-caption">{{ $metric['caption'] }}</span>
                    @if ($metric['url'])
                        <a class="small fw-semibold text-decoration-none" href="{{ $metric['url'] }}" aria-label="View {{ strtolower($metric['label']) }}"><i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i></a>
                    @endif
                </div>
            </article>
        @endforeach
    </section>

    <div class="app-dashboard-grid">
        <section class="app-card overflow-hidden" aria-labelledby="recent-heading">
            <div class="app-section-head">
                <div>
                    <h2 class="app-section-title" id="recent-heading">{{ $recentTitle }}</h2>
                    <p class="app-section-note">{{ $recentDescription }}</p>
                </div>
                @if ($recentListUrl)
                    <a class="btn btn-outline-secondary btn-sm" href="{{ $recentListUrl }}">View all <i class="fa-solid fa-arrow-right ms-1" aria-hidden="true"></i></a>
                @endif
            </div>
            @if (! $canViewRecent)
                <div class="app-dashboard-empty">
                    <i class="fa-solid fa-lock" aria-hidden="true"></i>
                    <h3 class="h6">This list is restricted</h3>
                    <p class="small text-body-secondary mb-0">Your account does not have access to this list.</p>
                </div>
            @elseif ($recentRecords->isEmpty())
                <div class="app-dashboard-empty">
                    <i class="fa-solid fa-{{ $central ? 'building' : 'users' }}" aria-hidden="true"></i>
                    <h3 class="h6">{{ $central ? 'No tenants yet' : 'No users yet' }}</h3>
                    <p class="small text-body-secondary mb-0">New records will appear here when they are added.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table app-dashboard-table align-middle mb-0">
                        <caption class="visually-hidden">{{ $recentTitle }}: five most recently added records</caption>
                        <thead>
                            <tr>
                                <th scope="col">{{ $central ? 'Workspace' : 'User' }}</th>
                                @if ($central)<th scope="col">Status</th>@endif
                                <th scope="col">Added</th>
                                <th class="text-end" scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentRecords as $record)
                                <tr>
                                    <th scope="row">
                                        <div class="fw-semibold text-break">{{ $record['name'] }}</div>
                                        <div class="small text-body-secondary fw-normal text-break">{{ $record['detail'] }}</div>
                                    </th>
                                    @if ($central)
                                        <td><span class="app-status app-status-{{ $record['status'] }}">{{ $record['statusLabel'] }}</span></td>
                                    @endif
                                    <td class="small text-nowrap">
                                        @if ($record['createdIso'])
                                            <time datetime="{{ $record['createdIso'] }}">{{ $record['created'] }}</time>
                                        @else
                                            {{ $record['created'] }}
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if ($record['url'])
                                            <a class="btn btn-outline-secondary btn-sm" href="{{ $record['url'] }}" aria-label="Edit {{ $record['name'] }}"><i class="fa-solid fa-pen" aria-hidden="true"></i></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        <aside class="d-flex flex-column gap-4">
            <section class="app-card" aria-labelledby="quick-actions-heading">
                <div class="app-section-head">
                    <div>
                        <h2 class="app-section-title" id="quick-actions-heading">Quick actions</h2>
                        <p class="app-section-note">Your everyday tools, one click away.</p>
                    </div>
                </div>
                <div class="p-3">
                    @forelse ($quickActions as $action)
                        <a class="app-quick-link" href="{{ $action['url'] }}">
                            <span class="app-quick-icon app-tone-{{ $action['tone'] }}"><i class="fa-solid fa-{{ $action['icon'] }}" aria-hidden="true"></i></span>
                            <span class="flex-grow-1"><span class="d-block fw-semibold">{{ $action['label'] }}</span><span class="small text-body-secondary">{{ $action['description'] }}</span></span>
                            <i class="fa-solid fa-chevron-right small text-body-secondary" aria-hidden="true"></i>
                        </a>
                    @empty
                        <p class="small text-body-secondary mb-0 p-2">No quick actions are available for your account.</p>
                    @endforelse
                </div>
            </section>

            <section class="app-card" aria-labelledby="your-account-heading">
                <div class="app-section-head"><h2 class="app-section-title" id="your-account-heading">Your account</h2></div>
                <div class="p-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <span class="app-initials app-tone-primary" aria-hidden="true">{{ $accountInitials }}</span>
                        <div class="overflow-hidden"><p class="fw-semibold mb-1 text-break">{{ $accountName }}</p><p class="small text-body-secondary mb-0 text-break">{{ $accountEmail }}</p></div>
                    </div>
                    <dl class="mb-0">
                        <div class="app-account-detail"><dt>Workspace</dt><dd class="text-break">{{ $contextName }}</dd></div>
                        <div class="app-account-detail"><dt>Role</dt><dd>@include('core::partials.tags', ['labels' => $accountRoles, 'empty' => 'No roles assigned'])</dd></div>
                    </dl>
                    @if ($accountEditUrl)
                        <a class="btn btn-outline-secondary btn-sm w-100 mt-4" href="{{ $accountEditUrl }}"><i class="fa-solid fa-user-pen me-2" aria-hidden="true"></i>Manage your account</a>
                    @endif
                </div>
            </section>
        </aside>
    </div>
@endsection
