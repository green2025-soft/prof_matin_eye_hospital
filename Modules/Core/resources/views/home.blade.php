@extends('core::layouts.app')

@section('title', 'Dashboard')

@section('content')
    @php
        $recentTitle = $central ? 'Recent tenants' : 'Recently added users';
        $recentDescription = $central ? 'The newest workspaces on your platform.' : 'The newest accounts in your workspace.';
        $recentRoutePrefix = $central ? 'core.tenants' : $accountRoutePrefix;
    @endphp
    <header class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
        <div>
            {{-- <p class="small fw-semibold text-primary mb-1">{{ $contextName }}</p> --}}
            <h1 class="h4 fw-bold mb-1">Dashboard</h1>
            {{-- <p class="small text-body-secondary mb-0">Welcome back, {{ $actor->name }}. Your workspace at a glance.</p> --}}
        </div>
        <div class="d-flex flex-wrap align-items-center gap-3">
            <time class="small text-body-secondary" datetime="{{ $today->toDateString() }}">
                <i class="fa-solid fa-calendar-days me-2" aria-hidden="true"></i>{{ $today->format('l, d F Y') }}
            </time>
            @unless ($central)
                <a class="btn btn-primary btn-sm" href="{{ route('tenantcore.module-dashboard.index') }}">
                    <i class="fa-solid fa-layer-group me-2" aria-hidden="true"></i>Modules
                </a>
            @endunless
        </div>
    </header>

    <section class="mb-3" aria-labelledby="quick-actions-heading">
        @php
            $quickActions = [
                'tenants.create' => [
                    'label' => 'Create tenant',
                    'icon' => 'building',
                    'url' => route('core.tenants.create'),
                    'tone' => 'primary',
                    'available' => $central,
                ],
                'accounts.create' => [
                    'label' => $central ? 'Add administrator' : 'Add user',
                    'icon' => 'user-plus',
                    'url' => route($accountRoutePrefix.'.create'),
                    'tone' => 'success',
                    'available' => true,
                ],
                'roles.create' => [
                    'label' => 'Create role',
                    'icon' => 'shield-halved',
                    'url' => route('core.roles.create'),
                    'tone' => 'warning',
                    'available' => true,
                ],
                'tenants.view' => [
                    'label' => 'Tenants',
                    'icon' => 'building',
                    'url' => route('core.tenants.index'),
                    'tone' => 'danger',
                    'available' => $central,
                ],
                'accounts.view' => [
                    'label' => $central ? 'Administrators' : 'Users',
                    'icon' => 'users',
                    'url' => route($accountRoutePrefix.'.index'),
                    'tone' => 'primary',
                    'available' => true,
                ],
                'roles.view' => [
                    'label' => 'Roles',
                    'icon' => 'shield-halved',
                    'url' => route('core.roles.index'),
                    'tone' => 'warning',
                    'available' => true,
                ],
                'permissions.view' => [
                    'label' => 'Permissions',
                    'icon' => 'key',
                    'url' => route('core.permissions.index'),
                    'tone' => 'info',
                    'available' => true,
                ],
                'audit-logs.view' => [
                    'label' => 'Audit logs',
                    'icon' => 'clock-rotate-left',
                    'url' => route('tenantcore.audit-logs.index'),
                    'tone' => 'primary',
                    'available' => ! $central,
                ],
                'branches.view' => [
                    'label' => 'Branches',
                    'icon' => 'building',
                    'url' => route('tenantcore.branches.index'),
                    'tone' => 'success',
                    'available' => ! $central && $hasBranches,
                ],
                'billing.view' => [
                    'label' => 'Billing',
                    'icon' => 'file-invoice-dollar',
                    'url' => route('tenantcore.billing.index'),
                    'tone' => 'danger',
                    'available' => ! $central,
                ],
                'settings.view' => [
                    'label' => 'Settings',
                    'icon' => 'gear',
                    'url' => route($central ? 'core.settings.index' : 'tenantcore.settings.index'),
                    'tone' => 'secondary',
                    'available' => true,
                ],
            ];
            $quickActions = array_filter(
                array_intersect_key($quickActions, $permissions),
                static fn (array $action): bool => $action['available'],
            );
        @endphp
        <h2 class="h6 fw-semibold mb-2" id="quick-actions-heading">Quick actions</h2>
        <div class="row row-cols-2 row-cols-sm-3 row-cols-lg-4 row-cols-xl-6 justify-content-center g-3">
            @forelse ($quickActions as $action)
                <div class="col">
                    <a class="btn btn-{{ $action['tone'] }} bg-gradient d-flex flex-column align-items-center justify-content-center gap-2 py-3 h-100"
                        href="{{ $action['url'] }}">
                        <i class="fa-solid fa-{{ $action['icon'] }} fs-4" aria-hidden="true"></i>
                        <span class="fw-semibold">{{ $action['label'] }}</span>
                    </a>
                </div>
            @empty
                <div class="col-12 w-100">
                    <p class="app-card small text-body-secondary p-3 mb-0">No quick actions are available for your account.</p>
                </div>
            @endforelse
        </div>
    </section>

    <section class="row row-cols-1 row-cols-sm-2 row-cols-xl-4 g-3 mb-3" aria-label="Workspace overview">
        @php
            $metrics = [
                [
                    'label' => 'Tenants',
                    'value' => $tenantCount,
                    'caption' => 'Registered workspaces',
                    'icon' => 'building',
                    'tone' => 'primary',
                    'url' => route('core.tenants.index'),
                ],
                [
                    'label' => $central ? 'Administrators' : 'Users',
                    'value' => $accountCount,
                    'caption' => $central ? 'Platform accounts' : 'Workspace accounts',
                    'icon' => 'users',
                    'tone' => 'success',
                    'url' => route($accountRoutePrefix.'.index'),
                ],
                [
                    'label' => 'Roles',
                    'value' => $roleCount,
                    'caption' => 'Access roles in this workspace',
                    'icon' => 'shield-halved',
                    'tone' => 'warning',
                    'url' => route('core.roles.index'),
                ],
                [
                    'label' => 'Your permissions',
                    'value' => $permissionCount,
                    'caption' => 'Assigned access permissions',
                    'icon' => 'key',
                    'tone' => 'info',
                    'url' => null,
                ],
            ];
            $metrics = array_filter($metrics, static fn (array $metric): bool => $metric['value'] !== null);
        @endphp
        @foreach ($metrics as $metric)
            <div class="col flex-grow-1">
                <article class="card d-flex flex-row align-items-center gap-3 bg-{{ $metric['tone'] }}-subtle text-{{ $metric['tone'] }}-emphasis border-0 rounded-3 h-100 p-3">
                    <span class="d-inline-flex flex-shrink-0 align-items-center justify-content-center text-bg-{{ $metric['tone'] }} rounded-circle p-3">
                        <i class="fa-solid fa-{{ $metric['icon'] }} fs-5" aria-hidden="true"></i>
                    </span>
                    <div class="flex-grow-1">
                        <p class="small fw-semibold mb-1">{{ $metric['label'] }}</p>
                        <p class="h3 fw-bold mb-1">{{ number_format($metric['value']) }}</p>
                        <p class="small text-body-secondary mb-0">{{ $metric['caption'] }}</p>
                    </div>
                    @if ($metric['url'])
                        <a class="text-reset stretched-link align-self-start" href="{{ $metric['url'] }}" aria-label="View {{ strtolower($metric['label']) }}">
                            <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i>
                        </a>
                    @endif
                </article>
            </div>
        @endforeach
    </section>

    <div class="row g-3 align-items-start">
        <div class="col-lg-8">
            <section class="app-card overflow-hidden" aria-labelledby="recent-heading">
                <header class="d-flex flex-wrap align-items-center justify-content-between gap-3 p-3 border-bottom bg-primary-subtle">
                    <div>
                        <h2 class="h6 fw-semibold mb-1" id="recent-heading">{{ $recentTitle }}</h2>
                        <p class="small text-body-secondary mb-0">{{ $recentDescription }}</p>
                    </div>
                    @if ($recentRecords !== null)
                        <a class="btn btn-primary btn-sm" href="{{ route($recentRoutePrefix.'.index') }}">
                            View all <i class="fa-solid fa-arrow-right ms-1" aria-hidden="true"></i>
                        </a>
                    @endif
                </header>

                @if ($recentRecords === null)
                    <div class="text-center p-4">
                        <i class="fa-solid fa-lock fs-3 text-body-secondary mb-3" aria-hidden="true"></i>
                        <h3 class="h6">This list is restricted</h3>
                        <p class="small text-body-secondary mb-0">Your account does not have access to this list.</p>
                    </div>
                @elseif ($recentRecords->isEmpty())
                    <div class="text-center p-4">
                        <i class="fa-solid fa-{{ $central ? 'building' : 'users' }} fs-3 text-body-secondary mb-3" aria-hidden="true"></i>
                        <h3 class="h6">{{ $central ? 'No tenants yet' : 'No users yet' }}</h3>
                        <p class="small text-body-secondary mb-0">New records will appear here when they are added.</p>
                    </div>
                @else
                    <div class="table-responsive overflow-y-hidden">
                        <table class="table table-hover align-middle mb-0">
                            <caption class="visually-hidden">{{ $recentTitle }}: five most recently added records</caption>
                            <thead class="table-light">
                                <tr>
                                    <th class="px-3 py-2" scope="col">{{ $central ? 'Workspace' : 'User' }}</th>
                                    @if ($central)<th class="px-3 py-2" scope="col">Status</th>@endif
                                    <th class="px-3 py-2" scope="col">Added</th>
                                    <th class="text-end px-3 py-2" scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentRecords as $record)
                                    <tr>
                                        <th class="px-3 py-3" scope="row">
                                            <div class="fw-semibold text-break">{{ $record->name }}</div>
                                            <div class="small text-body-secondary fw-normal text-break">{{ $central ? $record->slug : $record->email }}</div>
                                        </th>
                                        @if ($central)
                                            <td class="px-3 py-3">
                                                <span class="app-status app-status-{{ $record->status }}">{{ $recentStatusOptions[$record->status] ?? $record->status }}</span>
                                            </td>
                                        @endif
                                        <td class="small text-nowrap px-3 py-3">
                                            @if ($record->created_at)
                                                <time datetime="{{ $record->created_at->toDateString() }}">{{ $record->created_at->format('d M Y') }}</time>
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td class="text-end px-3 py-3">
                                            @can('update', $record)
                                                <a class="btn btn-outline-primary btn-sm" href="{{ route($recentRoutePrefix.'.edit', $record) }}" aria-label="Edit {{ $record->name }}">
                                                    <i class="fa-solid fa-pen" aria-hidden="true"></i>
                                                </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>
        </div>

        <aside class="col-lg-4">
            <section class="app-card overflow-hidden" aria-labelledby="your-account-heading">
                <header class="p-3 border-bottom bg-body-tertiary">
                    <h2 class="h6 fw-semibold mb-0" id="your-account-heading">
                        <i class="fa-solid fa-user text-primary me-2" aria-hidden="true"></i>Your account
                    </h2>
                </header>
                <div class="p-3">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="flex-shrink-0 bg-primary-subtle text-primary-emphasis rounded-3 px-3 py-2 fs-5 fw-bold" aria-hidden="true">{{ $accountInitials }}</span>
                        <div class="overflow-hidden">
                            <p class="fw-semibold mb-1 text-break">{{ $actor->name }}</p>
                            <p class="small text-body-secondary mb-0 text-break">{{ $actor->email }}</p>
                        </div>
                    </div>
                    <dl class="row gy-2 small mb-0">
                        <dt class="col-4 fw-normal text-body-secondary">Workspace</dt>
                        <dd class="col-8 text-break mb-0">{{ $contextName }}</dd>
                        <dt class="col-4 fw-normal text-body-secondary">Role</dt>
                        <dd class="col-8 mb-0">@include('core::partials.tags', ['labels' => $accountRoles, 'empty' => 'No roles assigned'])</dd>
                    </dl>
                    @if ($accountEditUrl)
                        <a class="btn btn-outline-primary btn-sm w-100 mt-3" href="{{ $accountEditUrl }}">
                            <i class="fa-solid fa-user-pen me-2" aria-hidden="true"></i>Manage your account
                        </a>
                    @endif
                </div>
            </section>
        </aside>
    </div>
@endsection
