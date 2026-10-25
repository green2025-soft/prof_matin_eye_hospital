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
                        <a class="app-nav-link{{ $layout['navigation']['dashboard']['active'] ? ' active' : '' }}" href="{{ $layout['homeUrl'] }}" title="Dashboard" @if ($layout['navigation']['dashboard']['active']) aria-current="page" @endif>
                            <span class="app-nav-icon"><i class="fa-solid fa-table-cells-large" aria-hidden="true"></i></span>
                            <span class="app-nav-copy">Dashboard</span>
                        </a>
                        @if ($layout['showManagement'])
                            <p class="app-nav-label">Management</p>
                        @endif
                        @if ($layout['navigation']['tenants']['allowed'])
                            <a class="app-nav-link{{ $layout['navigation']['tenants']['active'] ? ' active' : '' }}" href="{{ $layout['navigation']['tenants']['url'] }}" title="Tenants" @if ($layout['navigation']['tenants']['active']) aria-current="page" @endif>
                                <span class="app-nav-icon"><i class="fa-solid fa-building" aria-hidden="true"></i></span>
                                <span class="app-nav-copy">Tenants</span>
                            </a>
                        @endif
                        @if ($layout['navigation']['accounts']['allowed'])
                            <a class="app-nav-link{{ $layout['navigation']['accounts']['active'] ? ' active' : '' }}" href="{{ $layout['navigation']['accounts']['url'] }}" title="{{ $layout['navigation']['accounts']['label'] }}" @if ($layout['navigation']['accounts']['active']) aria-current="page" @endif>
                                <span class="app-nav-icon"><i class="fa-solid fa-users" aria-hidden="true"></i></span>
                                <span class="app-nav-copy">{{ $layout['navigation']['accounts']['label'] }}</span>
                            </a>
                        @endif
                        @if ($layout['navigation']['roles']['allowed'])
                            <a class="app-nav-link{{ $layout['navigation']['roles']['active'] ? ' active' : '' }}" href="{{ $layout['navigation']['roles']['url'] }}" title="Roles" @if ($layout['navigation']['roles']['active']) aria-current="page" @endif>
                                <span class="app-nav-icon"><i class="fa-solid fa-shield-halved" aria-hidden="true"></i></span>
                                <span class="app-nav-copy">Roles</span>
                            </a>
                        @endif
                        @if ($layout['navigation']['permissions']['allowed'])
                            <a class="app-nav-link{{ $layout['navigation']['permissions']['active'] ? ' active' : '' }}" href="{{ $layout['navigation']['permissions']['url'] }}" title="Permissions" @if ($layout['navigation']['permissions']['active']) aria-current="page" @endif>
                                <span class="app-nav-icon"><i class="fa-solid fa-key" aria-hidden="true"></i></span>
                                <span class="app-nav-copy">Permissions</span>
                            </a>
                        @endif
                        @if ($layout['navigation']['plans']['allowed'])
                            <a class="app-nav-link{{ $layout['navigation']['plans']['active'] ? ' active' : '' }}" href="{{ $layout['navigation']['plans']['url'] }}" title="Plans" @if ($layout['navigation']['plans']['active']) aria-current="page" @endif>
                                <span class="app-nav-icon"><i class="fa-solid fa-layer-group" aria-hidden="true"></i></span>
                                <span class="app-nav-copy">Plans</span>
                            </a>
                        @endif
                        @if ($layout['navigation']['planPermissions']['allowed'])
                            <a class="app-nav-link{{ $layout['navigation']['planPermissions']['active'] ? ' active' : '' }}" href="{{ $layout['navigation']['planPermissions']['url'] }}" title="Plan Permissions" @if ($layout['navigation']['planPermissions']['active']) aria-current="page" @endif>
                                <span class="app-nav-icon"><i class="fa-solid fa-route" aria-hidden="true"></i></span>
                                <span class="app-nav-copy">Plan Permissions</span>
                            </a>
                        @endif
                        @if ($layout['navigation']['subscriptions']['allowed'])
                            <a class="app-nav-link{{ $layout['navigation']['subscriptions']['active'] ? ' active' : '' }}" href="{{ $layout['navigation']['subscriptions']['url'] }}" title="Subscriptions" @if ($layout['navigation']['subscriptions']['active']) aria-current="page" @endif>
                                <span class="app-nav-icon"><i class="fa-solid fa-calendar-check" aria-hidden="true"></i></span>
                                <span class="app-nav-copy">Subscriptions</span>
                            </a>
                        @endif
                        @if ($layout['navigation']['invoices']['allowed'])
                            <a class="app-nav-link{{ $layout['navigation']['invoices']['active'] ? ' active' : '' }}" href="{{ $layout['navigation']['invoices']['url'] }}" title="Invoices" @if ($layout['navigation']['invoices']['active']) aria-current="page" @endif>
                                <span class="app-nav-icon"><i class="fa-solid fa-file-invoice-dollar" aria-hidden="true"></i></span>
                                <span class="app-nav-copy">Invoices</span>
                            </a>
                        @endif
                        @if ($layout['navigation']['payments']['allowed'])
                            <a class="app-nav-link{{ $layout['navigation']['payments']['active'] ? ' active' : '' }}" href="{{ $layout['navigation']['payments']['url'] }}" title="Payments" @if ($layout['navigation']['payments']['active']) aria-current="page" @endif>
                                <span class="app-nav-icon"><i class="fa-solid fa-credit-card" aria-hidden="true"></i></span>
                                <span class="app-nav-copy">Payments</span>
                            </a>
                        @endif
                        @if ($layout['navigation']['billing']['allowed'])
                            <a class="app-nav-link{{ $layout['navigation']['billing']['active'] ? ' active' : '' }}" href="{{ $layout['navigation']['billing']['url'] }}" title="Billing" @if ($layout['navigation']['billing']['active']) aria-current="page" @endif>
                                <span class="app-nav-icon"><i class="fa-solid fa-wallet" aria-hidden="true"></i></span>
                                <span class="app-nav-copy">Billing</span>
                            </a>
                        @endif
                        @if ($layout['navigation']['settings']['allowed'])
                            <a class="app-nav-link{{ $layout['navigation']['settings']['active'] ? ' active' : '' }}" href="{{ $layout['navigation']['settings']['url'] }}" title="Settings" @if ($layout['navigation']['settings']['active']) aria-current="page" @endif>
                                <span class="app-nav-icon"><i class="fa-solid fa-gear" aria-hidden="true"></i></span>
                                <span class="app-nav-copy">Settings</span>
                            </a>
                        @endif
                    </nav>
                </div>
                <div class="app-sidebar-footer">
                    <div class="app-workspace">
                        <span class="app-workspace-icon"><i class="fa-solid fa-building" aria-hidden="true"></i></span>
                        <div class="app-workspace-copy">
                            <strong>{{ $layout['workspaceName'] }}</strong>
                            <small>{{ $layout['workspaceLabel'] }}</small>
                        </div>
                    </div>
                </div>
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
