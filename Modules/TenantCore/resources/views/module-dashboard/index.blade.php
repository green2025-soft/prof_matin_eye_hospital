@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    <header class="app-selection-intro d-flex align-items-start align-items-sm-center justify-content-between gap-3 mb-4">
        <div>
            <h1>{{ $pageTitle }}</h1>
            <p class="mb-0">Choose a module to continue working.</p>
        </div>
        {{-- <div class="d-flex flex-wrap align-items-center gap-2">
            <span class="app-selection-count d-inline-flex flex-shrink-0 align-items-center"><strong>{{ count($modules) }}</strong><span>{{ count($modules) === 1 ? 'module' : 'modules' }}</span></span>
            @if ($canSelectBranch)
                <a class="btn btn-outline-secondary btn-sm" href="{{ $branchDashboardUrl }}"><i class="fa-solid fa-location-dot me-2" aria-hidden="true"></i>Choose branch</a>
            @endif
        </div> --}}
    </header>

    @if ($modules === [])
        <section class="app-card">
            @include('core::partials.empty', ['title' => 'No modules available', 'description' => 'Your account does not have access to an installed module.', 'icon' => 'layer-group'])
        </section>
    @else
        <div class="app-selection-grid row row-cols-1 row-cols-sm-2 row-cols-xl-3">
            @foreach ($modules as $module)
                <div class="col">
                    <a class="app-selection-card card w-100 h-100 overflow-hidden p-0 text-start text-decoration-none"
                        href="{{ $module['url'] }}" aria-label="Open {{ $module['label'] }} module"
                        aria-describedby="module-description-{{ $module['name'] }}">
                        <span class="d-flex flex-column flex-grow-1 p-4">
                            <span class="app-selection-card-head d-flex align-items-center">
                                <span class="app-selection-mark d-inline-flex flex-shrink-0 align-items-center justify-content-center overflow-hidden">
                                    <i class="fa-solid fa-{{ $module['icon'] }}" aria-hidden="true"></i>
                                </span>
                                <span class="app-selection-name">{{ $module['label'] }}</span>
                            </span>
                            <span class="app-selection-description" id="module-description-{{ $module['name'] }}">{{ $module['description'] }}</span>
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
