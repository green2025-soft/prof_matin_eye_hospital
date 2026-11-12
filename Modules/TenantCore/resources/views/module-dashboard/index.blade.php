@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    <header class="app-selection-intro d-flex align-items-start align-items-sm-center justify-content-between gap-3 mb-4">
        <div>
            <h1>{{ $pageTitle }}</h1>
            <p class="mb-0">Choose a module to continue working.</p>
        </div>
        @if ($canArrangeModules)
            <a class="btn btn-outline-primary btn-sm flex-shrink-0"
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
        <div class="app-selection-grid row row-cols-1 row-cols-sm-2 row-cols-xl-3">
            @foreach ($modules as $module)
                @php($dashboard = $module->get('tenant_dashboard'))
                <div class="col">
                    <a class="app-selection-card card w-100 h-100 overflow-hidden p-0 text-start text-decoration-none"
                        href="{{ route($dashboard['route']) }}" aria-label="Open {{ $dashboard['label'] }} module"
                        aria-describedby="module-description-{{ $module->getName() }}">
                        <span class="d-flex flex-column flex-grow-1 p-4">
                            <span class="app-selection-card-head d-flex align-items-center">
                                <span class="app-selection-mark d-inline-flex flex-shrink-0 align-items-center justify-content-center overflow-hidden">
                                    <i class="fa-solid fa-{{ $dashboard['icon'] }}" aria-hidden="true"></i>
                                </span>
                                <span class="app-selection-name">{{ $dashboard['label'] }}</span>
                            </span>
                            <span class="app-selection-description" id="module-description-{{ $module->getName() }}">{{ $module->getDescription() }}</span>
                        </span>
                        <span class="app-selection-open d-flex align-items-center justify-content-between gap-3 px-4">
                            <span>Open module</span><i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                        </span>
                    </a>
                </div>
            @endforeach
        </div>
    @endif
@endsection
