<!doctype html>
<html lang="{{ $layout['locale'] }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', $pageTitle ?? 'Dashboard') | {{ $layout['appName'] }}</title>
        @if ($layout['faviconUrl'])
            <link rel="icon" href="{{ $layout['faviconUrl'] }}">
        @endif
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="app-shell{{ $layout['user'] ? '' : ' app-auth-shell' }}">
        <a class="app-skip-link visually-hidden-focusable position-fixed p-3" href="#main-content">Skip to content</a>
        @if ($layout['user'])
            <aside class="app-sidebar offcanvas-lg offcanvas-start" id="appSidebar" tabindex="-1" aria-labelledby="sidebar-brand">
                <div class="app-sidebar-head">
                    <a class="app-brand" href="{{ $layout['homeUrl'] }}" id="sidebar-brand">
                        @if ($layout['logoUrl'])
                            <img class="app-brand-logo" src="{{ $layout['logoUrl'] }}" alt="" width="36" height="36">
                        @else
                            <span class="app-brand-icon" aria-hidden="true">{{ $layout['appInitial'] }}</span>
                        @endif
                        <span class="app-brand-copy"><strong>{{ $layout['appName'] }}</strong><small>{{ $layout['tagline'] ?: 'Admin panel' }}</small></span>
                    </a>
                    <button class="btn-close btn-close-white d-lg-none" type="button" data-bs-dismiss="offcanvas" data-bs-target="#appSidebar" aria-label="Close navigation"></button>
                </div>
                <div class="offcanvas-body">
                    <nav class="app-nav" aria-label="Main navigation">
                        <p class="app-nav-label">Overview</p>
                        <a class="app-nav-link{{ ($moduleMenu['homeActive'] ?? $layout['navigation']['dashboard']['active']) ? ' active' : '' }}" href="{{ $moduleMenu['homeUrl'] ?? $layout['navigation']['dashboard']['url'] }}" title="{{ $moduleMenu['homeLabel'] ?? 'Dashboard' }}" @if ($moduleMenu['homeActive'] ?? $layout['navigation']['dashboard']['active']) aria-current="page" @endif>
                            <span class="app-nav-icon"><i class="fa-solid fa-table-cells-large" aria-hidden="true"></i></span>
                            <span class="app-nav-copy">{{ $moduleMenu['homeLabel'] ?? 'Dashboard' }}</span>
                        </a>
                        @if ($layout['navigation']['modules']['allowed'] ?? false)
                            <a class="app-nav-link{{ $layout['navigation']['modules']['active'] ? ' active' : '' }}" href="{{ $layout['navigation']['modules']['url'] }}" title="Modules" @if ($layout['navigation']['modules']['active']) aria-current="page" @endif>
                                <span class="app-nav-icon"><i class="fa-solid fa-layer-group" aria-hidden="true"></i></span>
                                <span class="app-nav-copy">Modules</span>
                            </a>
                        @endif
                        @isset($moduleMenu)
                            @include($moduleMenu['view'])
                        @else
                            @if (($layout['permissions']['accounts.view'] ?? false)
                                || ($layout['permissions']['roles.view'] ?? false)
                                || ($layout['permissions']['permissions.view'] ?? false)
                                || ($layout['permissions']['settings.view'] ?? false)
                                || ($layout['central'] && (($layout['permissions']['tenants.view'] ?? false)
                                    || ($layout['permissions']['plans.view'] ?? false)
                                    || ($layout['permissions']['subscriptions.view'] ?? false)
                                    || ($layout['permissions']['invoices.view'] ?? false)
                                    || ($layout['permissions']['payments.view'] ?? false)))
                                || (! $layout['central'] && (($layout['permissions']['branches.view'] ?? false)
                                    || ($layout['permissions']['payment-types.view'] ?? false)
                                    || ($layout['permissions']['divisions.view'] ?? false)
                                    || ($layout['permissions']['districts.view'] ?? false)
                                    || ($layout['permissions']['thanas.view'] ?? false)
                                    || ($layout['permissions']['audit-logs.view'] ?? false)
                                    || ($layout['permissions']['billing.view'] ?? false))))
                                <p class="app-nav-label">Management</p>
                            @endif
                            @if ($layout['central'] && ($layout['permissions']['tenants.view'] ?? false))
                                <a class="app-nav-link{{ request()->routeIs('core.tenants.*') ? ' active' : '' }}" href="{{ route('core.tenants.index') }}" title="Tenants" @if (request()->routeIs('core.tenants.*')) aria-current="page" @endif>
                                    <span class="app-nav-icon"><i class="fa-solid fa-building" aria-hidden="true"></i></span>
                                    <span class="app-nav-copy">Tenants</span>
                                </a>
                            @endif
                            @if ($layout['permissions']['accounts.view'] ?? false)
                                <a class="app-nav-link{{ request()->routeIs($layout['accountRoutePrefix'].'.*') ? ' active' : '' }}" href="{{ route($layout['accountRoutePrefix'].'.index') }}" title="{{ $layout['accountLabel'] }}" @if (request()->routeIs($layout['accountRoutePrefix'].'.*')) aria-current="page" @endif>
                                    <span class="app-nav-icon"><i class="fa-solid fa-users" aria-hidden="true"></i></span>
                                    <span class="app-nav-copy">{{ $layout['accountLabel'] }}</span>
                                </a>
                            @endif
                            @if (! $layout['central'] && ($layout['permissions']['branches.view'] ?? false))
                                <a class="app-nav-link{{ request()->routeIs('tenantcore.branches.*') ? ' active' : '' }}" href="{{ route('tenantcore.branches.index') }}" title="Branches" @if (request()->routeIs('tenantcore.branches.*')) aria-current="page" @endif>
                                    <span class="app-nav-icon"><i class="fa-solid fa-building" aria-hidden="true"></i></span>
                                    <span class="app-nav-copy">Branches</span>
                                </a>
                            @endif
                            @if (! $layout['central'] && ($layout['permissions']['payment-types.view'] ?? false))
                                <a class="app-nav-link{{ request()->routeIs('tenantcore.payment-types.*') ? ' active' : '' }}" href="{{ route('tenantcore.payment-types.index') }}" title="Payment Types" @if (request()->routeIs('tenantcore.payment-types.*')) aria-current="page" @endif>
                                    <span class="app-nav-icon"><i class="fa-solid fa-money-bill-wave" aria-hidden="true"></i></span>
                                    <span class="app-nav-copy">Payment Types</span>
                                </a>
                            @endif
                            @if (! $layout['central'] && ($layout['permissions']['divisions.view'] ?? false))
                                <a class="app-nav-link{{ request()->routeIs('tenantcore.divisions.*') ? ' active' : '' }}" href="{{ route('tenantcore.divisions.index') }}" title="Divisions" @if (request()->routeIs('tenantcore.divisions.*')) aria-current="page" @endif>
                                    <span class="app-nav-icon"><i class="fa-solid fa-map-location-dot" aria-hidden="true"></i></span>
                                    <span class="app-nav-copy">Divisions</span>
                                </a>
                            @endif
                            @if (! $layout['central'] && ($layout['permissions']['districts.view'] ?? false))
                                <a class="app-nav-link{{ request()->routeIs('tenantcore.districts.*') ? ' active' : '' }}" href="{{ route('tenantcore.districts.index') }}" title="Districts" @if (request()->routeIs('tenantcore.districts.*')) aria-current="page" @endif>
                                    <span class="app-nav-icon"><i class="fa-solid fa-map" aria-hidden="true"></i></span>
                                    <span class="app-nav-copy">Districts</span>
                                </a>
                            @endif
                            @if (! $layout['central'] && ($layout['permissions']['thanas.view'] ?? false))
                                <a class="app-nav-link{{ request()->routeIs('tenantcore.thanas.*') ? ' active' : '' }}" href="{{ route('tenantcore.thanas.index') }}" title="Thanas" @if (request()->routeIs('tenantcore.thanas.*')) aria-current="page" @endif>
                                    <span class="app-nav-icon"><i class="fa-solid fa-location-dot" aria-hidden="true"></i></span>
                                    <span class="app-nav-copy">Thanas</span>
                                </a>
                            @endif
                            @if ($layout['permissions']['roles.view'] ?? false)
                                <a class="app-nav-link{{ request()->routeIs('core.roles.*') ? ' active' : '' }}" href="{{ route('core.roles.index') }}" title="Roles" @if (request()->routeIs('core.roles.*')) aria-current="page" @endif>
                                    <span class="app-nav-icon"><i class="fa-solid fa-shield-halved" aria-hidden="true"></i></span>
                                    <span class="app-nav-copy">Roles</span>
                                </a>
                            @endif
                            @if (! $layout['central'] && ($layout['permissions']['audit-logs.view'] ?? false))
                                <a class="app-nav-link{{ request()->routeIs('tenantcore.audit-logs.*') ? ' active' : '' }}" href="{{ route('tenantcore.audit-logs.index') }}" title="Audit Logs" @if (request()->routeIs('tenantcore.audit-logs.*')) aria-current="page" @endif>
                                    <span class="app-nav-icon"><i class="fa-solid fa-clock-rotate-left" aria-hidden="true"></i></span>
                                    <span class="app-nav-copy">Audit Logs</span>
                                </a>
                            @endif
                            @if ($layout['permissions']['permissions.view'] ?? false)
                                <a class="app-nav-link{{ request()->routeIs('core.permissions.*') ? ' active' : '' }}" href="{{ route('core.permissions.index') }}" title="Permissions" @if (request()->routeIs('core.permissions.*')) aria-current="page" @endif>
                                    <span class="app-nav-icon"><i class="fa-solid fa-key" aria-hidden="true"></i></span>
                                    <span class="app-nav-copy">Permissions</span>
                                </a>
                            @endif
                            @if ($layout['central'] && ($layout['permissions']['plans.view'] ?? false))
                                <a class="app-nav-link{{ request()->routeIs('core.plans.*') ? ' active' : '' }}" href="{{ route('core.plans.index') }}" title="Plans" @if (request()->routeIs('core.plans.*')) aria-current="page" @endif>
                                    <span class="app-nav-icon"><i class="fa-solid fa-layer-group" aria-hidden="true"></i></span>
                                    <span class="app-nav-copy">Plans</span>
                                </a>
                            @endif
                            @if ($layout['central'] && ($layout['permissions']['plans.view'] ?? false))
                                <a class="app-nav-link{{ request()->routeIs('core.plan-permissions.*') ? ' active' : '' }}" href="{{ route('core.plan-permissions.index') }}" title="Plan Permissions" @if (request()->routeIs('core.plan-permissions.*')) aria-current="page" @endif>
                                    <span class="app-nav-icon"><i class="fa-solid fa-route" aria-hidden="true"></i></span>
                                    <span class="app-nav-copy">Plan Permissions</span>
                                </a>
                            @endif
                            @if ($layout['central'] && ($layout['permissions']['subscriptions.view'] ?? false))
                                <a class="app-nav-link{{ request()->routeIs('core.subscriptions.*') ? ' active' : '' }}" href="{{ route('core.subscriptions.index') }}" title="Subscriptions" @if (request()->routeIs('core.subscriptions.*')) aria-current="page" @endif>
                                    <span class="app-nav-icon"><i class="fa-solid fa-calendar-check" aria-hidden="true"></i></span>
                                    <span class="app-nav-copy">Subscriptions</span>
                                </a>
                            @endif
                            @if ($layout['central'] && ($layout['permissions']['invoices.view'] ?? false))
                                <a class="app-nav-link{{ request()->routeIs('core.invoices.*') ? ' active' : '' }}" href="{{ route('core.invoices.index') }}" title="Invoices" @if (request()->routeIs('core.invoices.*')) aria-current="page" @endif>
                                    <span class="app-nav-icon"><i class="fa-solid fa-file-invoice-dollar" aria-hidden="true"></i></span>
                                    <span class="app-nav-copy">Invoices</span>
                                </a>
                            @endif
                            @if ($layout['central'] && ($layout['permissions']['payments.view'] ?? false))
                                <a class="app-nav-link{{ request()->routeIs('core.payments.*') ? ' active' : '' }}" href="{{ route('core.payments.index') }}" title="Payments" @if (request()->routeIs('core.payments.*')) aria-current="page" @endif>
                                    <span class="app-nav-icon"><i class="fa-solid fa-credit-card" aria-hidden="true"></i></span>
                                    <span class="app-nav-copy">Payments</span>
                                </a>
                            @endif
                            @if (! $layout['central'] && ($layout['permissions']['billing.view'] ?? false))
                                <a class="app-nav-link{{ request()->routeIs('tenantcore.billing.*') ? ' active' : '' }}" href="{{ route('tenantcore.billing.index') }}" title="Billing" @if (request()->routeIs('tenantcore.billing.*')) aria-current="page" @endif>
                                    <span class="app-nav-icon"><i class="fa-solid fa-wallet" aria-hidden="true"></i></span>
                                    <span class="app-nav-copy">Billing</span>
                                </a>
                            @endif
                            @if ($layout['permissions']['settings.view'] ?? false)
                                <a class="app-nav-link{{ request()->routeIs($layout['settingsRoutePrefix'].'.*') ? ' active' : '' }}" href="{{ route($layout['settingsRoutePrefix'].'.index') }}" title="Settings" @if (request()->routeIs($layout['settingsRoutePrefix'].'.*')) aria-current="page" @endif>
                                    <span class="app-nav-icon"><i class="fa-solid fa-gear" aria-hidden="true"></i></span>
                                    <span class="app-nav-copy">Settings</span>
                                </a>
                            @endif
                        @endisset
                    </nav>
                </div>
                {{-- <div class="app-sidebar-footer">
                    <div class="app-workspace">
                        <span class="app-workspace-icon"><i class="fa-solid fa-building" aria-hidden="true"></i></span>
                        <div class="app-workspace-copy">
                            <strong>{{ $layout['workspaceName'] }}</strong>
                            <small>{{ $layout['workspaceLabel'] }}</small>
                        </div>
                    </div>
                </div> --}}
            </aside>
            <div class="app-page">
                <header class="app-header">
                    <div class="app-header-inner">
                        <button class="app-toolbar-button" type="button" data-sidebar-toggle aria-controls="appSidebar" aria-expanded="true" aria-label="Toggle navigation" title="Toggle navigation">
                            <i class="fa-solid fa-bars" aria-hidden="true"></i>
                        </button>
                        <nav class="app-breadcrumb" aria-label="Breadcrumb">
                            <a href="{{ $layout['homeUrl'] }}">Home</a>
                            <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                            <span aria-current="page">@yield('title', $pageTitle ?? 'Dashboard')</span>
                        </nav>
                        @if (($layout['branchName'] ?? null) || ($layout['canSwitchBranch'] ?? false))
                            <div class="app-branch-context">
                                @if ($layout['branchName'] ?? null)
                                    <span class="app-branch-badge" title="Active branch: {{ $layout['branchName'] }}">
                                        <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                                        <span class="visually-hidden">Active branch:</span>
                                        <span class="app-branch-context-name">{{ $layout['branchName'] }}</span>
                                    </span>
                                @endif
                                @if ($layout['canSwitchBranch'] ?? false)
                                    <form action="{{ $layout['switchBranchUrl'] }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="app-branch-switch" type="submit" aria-label="Switch branch" title="Choose a different branch">
                                            <i class="fa-solid fa-arrow-right-arrow-left" aria-hidden="true"></i>
                                            <span>Switch branch</span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif
                        <div class="d-flex align-items-center gap-2 ms-auto">
                            <span class="app-tag d-none d-lg-inline-flex">{{ $layout['workspaceLabel'] }}</span>
                            <button class="app-toolbar-button d-none d-md-inline-flex" type="button" data-fullscreen-toggle aria-pressed="false" aria-label="Enter fullscreen" title="Enter fullscreen">
                                <i class="fa-solid fa-expand" aria-hidden="true"></i>
                            </button>
                            <div class="dropdown">
                                <button class="app-user-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Account menu">
                                    <span class="app-avatar">{{ $layout['user']['initial'] }}</span>
                                    <span class="app-user-copy"><strong>{{ $layout['user']['name'] }}</strong><small>{{ $layout['user']['label'] }}</small></span>
                                    <i class="fa-solid fa-chevron-down small" aria-hidden="true"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end app-account-menu">
                                    <div class="px-3 py-2"><strong class="d-block small">{{ $layout['user']['name'] }}</strong><span class="text-body-secondary small text-break">{{ $layout['user']['email'] }}</span></div>
                                    <hr class="dropdown-divider">
                                    @if ($layout['user']['editUrl'])
                                        <a class="dropdown-item" href="{{ $layout['user']['editUrl'] }}"><i class="fa-solid fa-user-gear me-2" aria-hidden="true"></i>My account</a>
                                    @endif
                                    <form action="{{ $layout['logoutUrl'] }}" method="POST">
                                        @csrf
                                        <button class="dropdown-item text-danger" type="submit"><i class="fa-solid fa-arrow-right-from-bracket me-2" aria-hidden="true"></i>Sign out</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
                <main class="app-content" id="main-content">
                    @include('core::partials.feedback')
                    @yield('content')
                </main>
                <footer class="app-page-footer"><span>&copy; {{ $layout['year'] }} {{ $layout['appName'] }}</span><span>Administration workspace</span></footer>
            </div>
        @else
            <main class="app-auth-layout" id="main-content">
                <section class="app-auth-intro" aria-labelledby="auth-intro-title">
                    <a class="app-brand" href="{{ $layout['loginUrl'] }}">
                        @if ($layout['logoUrl'])
                            <img class="app-brand-logo" src="{{ $layout['logoUrl'] }}" alt="" width="36" height="36">
                        @else
                            <span class="app-brand-icon" aria-hidden="true">{{ $layout['appInitial'] }}</span>
                        @endif
                        <span class="app-brand-copy"><strong>{{ $layout['appName'] }}</strong><small>{{ $layout['tagline'] ?: 'Admin panel' }}</small></span>
                    </a>
                    <div>
                        <span class="app-auth-badge"><i class="fa-solid fa-shield-halved" aria-hidden="true"></i>{{ $layout['workspaceLabel'] }}</span>
                        <h1 id="auth-intro-title">One place.<br>Everything in control.</h1>
                        <p>Manage your workspace, people and access from one clear, connected dashboard.</p>
                        <div class="app-auth-list">
                            <div class="app-auth-feature"><span><i class="fa-solid fa-table-cells-large" aria-hidden="true"></i></span><div><strong>A clear overview</strong><small>Your workspace and team, at a glance.</small></div></div>
                            <div class="app-auth-feature"><span><i class="fa-solid fa-users-gear" aria-hidden="true"></i></span><div><strong>People and permissions</strong><small>The right access for every member.</small></div></div>
                            <div class="app-auth-feature"><span><i class="fa-solid fa-lock" aria-hidden="true"></i></span><div><strong>Your own workspace</strong><small>Separate accounts and data for each tenant.</small></div></div>
                        </div>
                    </div>
                    <p class="app-auth-copyright">&copy; {{ $layout['year'] }} {{ $layout['appName'] }}</p>
                </section>
                <section class="app-auth-form" aria-label="Sign in">
                    <div class="app-auth-form-inner">
                        @include('core::partials.feedback')
                        @yield('content')
                    </div>
                </section>
            </main>
        @endif
    </body>
</html>
