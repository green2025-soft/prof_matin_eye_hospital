@php($moduleMenu = $contextMenu ?? $moduleMenu ?? [])
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
    <body @class(['app-shell', 'app-auth-shell d-flex align-items-center justify-content-center p-3 p-sm-4' => ! $layout['user']])>
        <a class="app-skip-link visually-hidden-focusable position-fixed p-3" href="#main-content">Skip to content</a>
        @if ($layout['user'])
            <aside class="app-sidebar offcanvas-lg offcanvas-start" id="appSidebar" tabindex="-1" aria-labelledby="sidebar-brand">
                <div class="app-sidebar-head">
                    <a class="app-brand" href="{{ $layout['homeUrl'] }}" id="sidebar-brand">
                        @include('core::partials.brand')
                    </a>
                    <button class="btn-close btn-close-white d-lg-none" type="button" data-bs-dismiss="offcanvas" data-bs-target="#appSidebar" aria-label="Close navigation"></button>
                </div>
                <div class="offcanvas-body">
                    <nav class="app-nav" aria-label="Main navigation">
                        <p class="app-nav-label">Overview</p>
                        @include('core::partials.navigation-link', [
                            'label' => $moduleMenu['homeLabel'] ?? 'Dashboard',
                            'icon' => 'table-cells-large',
                            'url' => $moduleMenu['homeUrl'] ?? $layout['navigation']['dashboard']['url'],
                            'active' => $moduleMenu['homeActive'] ?? $layout['navigation']['dashboard']['active'],
                        ])

                        @if ($layout['navigation']['modules']['allowed'])
                            @include('core::partials.navigation-link', [
                                'label' => 'Modules', 'icon' => 'layer-group',
                                'url' => $layout['navigation']['modules']['url'],
                                'active' => $layout['navigation']['modules']['active'],
                            ])
                        @endif
                        @include($moduleMenu['view'] ?? $foundationMenuView ?? 'core::partials.menu')
                    </nav>
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
                        @if (filled($layout['branchName']))
                            <div class="app-branch-context">
                                <span class="app-branch-badge" title="Active branch: {{ $layout['branchName'] }}">
                                    <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                                    <span class="visually-hidden">Active branch:</span>
                                    <span class="app-branch-context-name">{{ $layout['branchName'] }}</span>
                                </span>
                                @if ($layout['canSwitchBranch'])
                                    <form action="{{ $layout['switchBranchUrl'] }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="app-branch-switch" type="submit" aria-label="Switch branch" title="Choose a different branch">
                                            <i class="fa-solid fa-arrow-right-arrow-left" aria-hidden="true"></i>
                                            <span>Switch Branch</span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif
                        <div class="d-flex align-items-center gap-2 ms-auto">
                            {{-- <span class="app-tag d-none d-lg-inline-flex">{{ $layout['workspaceLabel'] }}</span> --}}
                            <button class="app-toolbar-button d-none d-md-inline-flex" type="button" data-fullscreen-toggle aria-pressed="false" aria-label="Enter fullscreen" title="Enter fullscreen">
                                <i class="fa-solid fa-expand" aria-hidden="true"></i>
                            </button>
                            <div class="dropdown">
                                <button class="app-user-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Account menu">
                                    <span class="app-avatar">{{ $layout['user']['initial'] }}</span>
                                    <span class="app-user-copy"><strong>{{ $layout['user']['name'] }}</strong></span>
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
                <footer class="app-page-footer"><span>&copy; {{ $layout['year'] }} <a href="https://greensoftwarebd.com" target="_blank">Green Software & Technology. All Rights Reserved</a></span></footer>
            </div>
        @else
            <main class="app-auth-layout d-grid w-100" id="main-content">
                <header class="app-auth-branding">
                    <a  class="d-flex justify-content-center align-items-center gap-2 text-decoration-none" href="{{ $layout['loginUrl'] }}">
                         @if ($layout['logoUrl'])
                            <img class="app-auth-logo flex-shrink-0 object-fit-contain" src="{{ $layout['logoUrl'] }}" width="50" height="50" alt="">
                        @else
                            <span class="app-auth-monogram d-inline-flex flex-shrink-0 align-items-center justify-content-center" aria-hidden="true">{{ $layout['appInitial'] }}</span>
                        @endif
                       <span class="app-auth-name">{{ $layout['appName'] }}</span>
                    </a>

                    {{-- <a class="app-auth-brand d-flex flex-column align-items-center text-center text-decoration-none" href="{{ $layout['loginUrl'] }}">
                        @if ($layout['logoUrl'])
                            <img class="app-auth-logo flex-shrink-0 object-fit-contain" src="{{ $layout['logoUrl'] }}" width="56" height="56" alt="">
                        @else
                            <span class="app-auth-monogram d-inline-flex flex-shrink-0 align-items-center justify-content-center" aria-hidden="true">{{ $layout['appInitial'] }}</span>
                        @endif
                        <span class="app-auth-name">{{ $layout['appName'] }}</span>
                    </a> --}}
                </header>
                <section class="app-auth-form" aria-label="Sign in">
                    <div class="w-100">
                        @include('core::partials.feedback')
                        @yield('content')
                    </div>
                </section>
                <footer class="app-auth-copyright text-center m-0">
                    &copy; {{ $layout['year'] }} <a href="https://greensoftwarebd.com" target="_blank">Green Software & Technology. All Rights Reserved</a>
                </footer>
            </main>
        @endif
    </body>
</html>
