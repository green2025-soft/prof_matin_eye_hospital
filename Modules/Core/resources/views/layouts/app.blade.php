<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', $pageTitle ?? 'Dashboard') | {{ config('app.name') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="app-shell @guest app-auth-shell @endguest">
        <a class="app-skip-link visually-hidden-focusable position-fixed p-3" href="#main-content">Skip to content</a>
        @auth
            <aside class="app-sidebar offcanvas-lg offcanvas-start" id="appSidebar" tabindex="-1" aria-labelledby="sidebar-brand">
                <div class="app-sidebar-head">
                    <a class="app-brand" href="{{ route('home') }}" id="sidebar-brand">
                        <span class="app-brand-icon"><i class="fa-solid fa-layer-group" aria-hidden="true"></i></span>
                        <span class="app-brand-copy"><strong>{{ config('app.name') }}</strong><small>Admin panel</small></span>
                    </a>
                    <button class="btn-close btn-close-white d-lg-none" type="button" data-bs-dismiss="offcanvas" data-bs-target="#appSidebar" aria-label="Close navigation"></button>
                </div>
                <div class="offcanvas-body">
                    <nav class="app-nav" aria-label="Main navigation">
                        <p class="app-nav-label">Overview</p>
                        <a class="app-nav-link{{ request()->routeIs('home') ? ' active' : '' }}" href="{{ route('home') }}" title="Dashboard" @if (request()->routeIs('home')) aria-current="page" @endif>
                            <span class="app-nav-icon"><i class="fa-solid fa-table-cells-large" aria-hidden="true"></i></span>
                            <span class="app-nav-copy">Dashboard</span>
                        </a>
                        <p class="app-nav-label">Management</p>
                        @can('viewAny', \Modules\Core\Models\Tenant::class)
                            <a class="app-nav-link{{ request()->routeIs('core.tenants.*') ? ' active' : '' }}" href="{{ route('core.tenants.index') }}" title="Tenants" @if (request()->routeIs('core.tenants.*')) aria-current="page" @endif>
                                <span class="app-nav-icon"><i class="fa-solid fa-building" aria-hidden="true"></i></span>
                                <span class="app-nav-copy">Tenants</span>
                            </a>
                        @endcan
                        @can('viewAny', get_class(auth()->user()))
                            <a class="app-nav-link{{ request()->routeIs($accountRoutePrefix.'.*') ? ' active' : '' }}" href="{{ route($accountRoutePrefix.'.index') }}" title="{{ $accountRoutePrefix === 'core.accounts' ? 'Administrators' : 'Team members' }}" @if (request()->routeIs($accountRoutePrefix.'.*')) aria-current="page" @endif>
                                <span class="app-nav-icon"><i class="fa-solid fa-users" aria-hidden="true"></i></span>
                                <span class="app-nav-copy">{{ $accountRoutePrefix === 'core.accounts' ? 'Administrators' : 'Team members' }}</span>
                            </a>
                        @endcan
                        @can('viewAny', \Modules\Core\Models\Role::class)
                            <a class="app-nav-link{{ request()->routeIs('core.roles.*') ? ' active' : '' }}" href="{{ route('core.roles.index') }}" title="Roles" @if (request()->routeIs('core.roles.*')) aria-current="page" @endif>
                                <span class="app-nav-icon"><i class="fa-solid fa-shield-halved" aria-hidden="true"></i></span>
                                <span class="app-nav-copy">Roles</span>
                            </a>
                        @endcan
                        @can('viewPermissions', \Modules\Core\Models\Role::class)
                            <a class="app-nav-link{{ request()->routeIs('core.permissions.*') ? ' active' : '' }}" href="{{ route('core.permissions.index') }}" title="Permissions" @if (request()->routeIs('core.permissions.*')) aria-current="page" @endif>
                                <span class="app-nav-icon"><i class="fa-solid fa-key" aria-hidden="true"></i></span>
                                <span class="app-nav-copy">Permissions</span>
                            </a>
                        @endcan
                    </nav>
                </div>
                <div class="app-sidebar-footer">
                    <div class="app-workspace">
                        <span class="app-workspace-icon"><i class="fa-solid fa-building" aria-hidden="true"></i></span>
                        <div class="app-workspace-copy">
                            <strong>{{ request()->attributes->get('application_name', 'Workspace') }}</strong>
                            <small>{{ request()->attributes->get('application_scope') === 'central' ? 'Platform administration' : 'Tenant workspace' }}</small>
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
                            <a href="{{ route('home') }}">Home</a>
                            <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                            <span aria-current="page">@yield('title', $pageTitle ?? 'Dashboard')</span>
                        </nav>
                        <div class="d-flex align-items-center gap-2 ms-auto">
                            <button class="app-toolbar-button d-none d-md-inline-flex" type="button" data-fullscreen-toggle aria-pressed="false" aria-label="Enter fullscreen" title="Enter fullscreen">
                                <i class="fa-solid fa-expand" aria-hidden="true"></i>
                            </button>
                            <div class="dropdown">
                                <button class="app-user-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Account menu">
                                    <span class="app-avatar">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr(auth()->user()->name, 0, 1)) }}</span>
                                    <span class="app-user-copy"><strong>{{ auth()->user()->name }}</strong><small>{{ request()->attributes->get('application_scope') === 'central' ? 'Platform account' : 'Workspace account' }}</small></span>
                                    <i class="fa-solid fa-chevron-down small" aria-hidden="true"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end app-account-menu">
                                    <div class="px-3 py-2"><strong class="d-block small">{{ auth()->user()->name }}</strong><span class="text-body-secondary small text-break">{{ auth()->user()->email }}</span></div>
                                    <hr class="dropdown-divider">
                                    @can('update', auth()->user())
                                        <a class="dropdown-item" href="{{ route($accountRoutePrefix.'.edit', auth()->user()) }}"><i class="fa-solid fa-user-gear me-2" aria-hidden="true"></i>My account</a>
                                    @endcan
                                    <form action="{{ route('auth.logout') }}" method="POST">
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
                <footer class="app-page-footer"><span>&copy; {{ now()->year }} {{ config('app.name') }}</span><span>Administration workspace</span></footer>
            </div>
        @else
            <main class="app-auth-layout" id="main-content">
                <section class="app-auth-intro" aria-labelledby="auth-intro-title">
                    <a class="app-brand" href="{{ route('auth.login') }}">
                        <span class="app-brand-icon"><i class="fa-solid fa-layer-group" aria-hidden="true"></i></span>
                        <span class="app-brand-copy"><strong>{{ config('app.name') }}</strong><small>Admin panel</small></span>
                    </a>
                    <div>
                        <span class="app-auth-badge"><i class="fa-solid fa-shield-halved" aria-hidden="true"></i>Administration workspace</span>
                        <h1 id="auth-intro-title">One place.<br>Everything in control.</h1>
                        <p>Manage your workspace, people and access from one clear, connected dashboard.</p>
                        <div class="app-auth-list">
                            <div class="app-auth-feature"><span><i class="fa-solid fa-table-cells-large" aria-hidden="true"></i></span><div><strong>A clear overview</strong><small>Your workspace and team, at a glance.</small></div></div>
                            <div class="app-auth-feature"><span><i class="fa-solid fa-users-gear" aria-hidden="true"></i></span><div><strong>People and permissions</strong><small>The right access for every member.</small></div></div>
                            <div class="app-auth-feature"><span><i class="fa-solid fa-lock" aria-hidden="true"></i></span><div><strong>Your own workspace</strong><small>Separate accounts and data for each tenant.</small></div></div>
                        </div>
                    </div>
                    <p class="app-auth-copyright">&copy; {{ now()->year }} {{ config('app.name') }}</p>
                </section>
                <section class="app-auth-form" aria-label="Sign in">
                    <div class="app-auth-form-inner">
                        @include('core::partials.feedback')
                        @yield('content')
                    </div>
                </section>
            </main>
        @endauth
    </body>
</html>
