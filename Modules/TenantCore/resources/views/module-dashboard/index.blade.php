@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    <header class="app-selection-intro d-flex align-items-start align-items-sm-center justify-content-between gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <span class="app-module-heading-icon d-inline-flex flex-shrink-0 align-items-center justify-content-center"
                aria-hidden="true">
                <i class="fa-solid fa-cube"></i>
            </span>
            <div>
                <h1>{{ $pageTitle }}</h1>
                <p class="mb-0">Choose a module to continue working.</p>
            </div>
        </div>
        @if ($canArrangeModules)
            <a class="app-module-arrange btn btn-outline-primary btn-sm flex-shrink-0"
                href="{{ route('tenantcore.settings.index', ['group' => 'general']).'#module-order-heading' }}">
                <i class="fa-solid fa-arrow-down-wide-short me-2" aria-hidden="true"></i>Arrange modules
            </a>
        @endif
    </header>

    @if ($modules === [])
        <section class="app-card">
            @include('core::partials.empty', ['title' => 'No modules available', 'description' => 'Your account does not have access to an installed module.', 'icon' => 'layer-group'])
        </section>
    @else
        <div class="app-selection-grid row row-cols-1 row-cols-sm-2 row-cols-lg-3">
            @foreach ($modules as $module)
                @php
                    $dashboard = $module->get('tenant_dashboard');
                    $moduleName = $moduleNames[$module->getLowerName()] ?? $dashboard['label'];
                @endphp
                <div class="col">
                    <a class="app-selection-card app-module-card card w-100 h-100 overflow-hidden p-0 text-start text-decoration-none"
                        href="{{ route($dashboard['route']) }}" aria-label="Open {{ $moduleName }} module"
                        aria-describedby="module-description-{{ $module->getName() }}">
                        <span class="app-module-card-main d-flex align-items-center flex-grow-1">
                            <span class="app-module-icon d-inline-flex flex-shrink-0 align-items-center justify-content-center">
                                <i class="fa-solid fa-{{ $dashboard['icon'] }}" aria-hidden="true"></i>
                            </span>
                            <span class="d-flex flex-column gap-1">
                                <span class="app-selection-name">{{ $moduleName }}</span>
                                <span class="app-selection-description" id="module-description-{{ $module->getName() }}">{{ $module->getDescription() }}</span>
                            </span>
                        </span>
                        <span class="app-module-open d-flex align-items-center justify-content-between">
                            <span>Open module</span>
                            <span class="app-module-open-icon d-inline-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                            </span>
                        </span>
                    </a>
                </div>
            @endforeach
        </div>

        <section class="app-module-highlights row row-cols-2 row-cols-md-4 g-0 mt-4 d-none"
            aria-label="Platform highlights">
            <div class="col">
                <div class="app-module-highlight d-flex align-items-center justify-content-center gap-3 h-100">
                    <i class="fa-solid fa-eye" aria-hidden="true"></i>
                    <span>Advanced Healthcare<br class="d-none d-lg-block"> Management</span>
                </div>
            </div>
            <div class="col">
                <div class="app-module-highlight d-flex align-items-center justify-content-center gap-3 h-100">
                    <i class="fa-solid fa-shield-halved" aria-hidden="true"></i>
                    <span>Secure &amp; Reliable<br class="d-none d-lg-block"> System</span>
                </div>
            </div>
            <div class="col">
                <div class="app-module-highlight d-flex align-items-center justify-content-center gap-3 h-100">
                    <i class="fa-solid fa-headset" aria-hidden="true"></i>
                    <span>24/7 Support</span>
                </div>
            </div>
            <div class="col">
                <div class="app-module-highlight d-flex align-items-center justify-content-center gap-3 h-100">
                    <i class="fa-solid fa-chart-line" aria-hidden="true"></i>
                    <span>Better Patient Care</span>
                </div>
            </div>
        </section>
    @endif
@endsection
