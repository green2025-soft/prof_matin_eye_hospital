@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    <header class="app-selection-intro">
        <div>
            <h1>{{ $pageTitle }}</h1>
            <p>Choose a module to continue working.</p>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <span class="app-selection-count"><strong>{{ count($modules) }}</strong><span>{{ count($modules) === 1 ? 'module' : 'modules' }}</span></span>
            @if ($canSelectBranch)
                <a class="btn btn-outline-secondary btn-sm" href="{{ $branchDashboardUrl }}"><i class="fa-solid fa-location-dot me-2" aria-hidden="true"></i>Choose branch</a>
            @endif
        </div>
    </header>

    @if ($modules === [])
        <section class="app-card">
            @include('core::partials.empty', ['title' => 'No modules available', 'description' => 'Your account does not have access to an installed module.', 'icon' => 'layer-group'])
        </section>
    @else
        <div class="app-selection-grid">
            @foreach ($modules as $module)
                <a class="app-selection-card" href="{{ $module['url'] }}" aria-label="Open {{ $module['label'] }} module" aria-describedby="module-description-{{ $module['name'] }}">
                    <span class="app-selection-card-content">
                        <span class="app-selection-card-head">
                            <span class="app-selection-mark"><i class="fa-solid fa-{{ $module['icon'] }}" aria-hidden="true"></i></span>
                            <span class="app-selection-name">{{ $module['label'] }}</span>
                        </span>
                        <span class="app-selection-description" id="module-description-{{ $module['name'] }}">{{ $module['description'] }}</span>
                    </span>
                    <span class="app-selection-open"><span>Open module</span><i class="fa-solid fa-arrow-right" aria-hidden="true"></i></span>
                </a>
            @endforeach
        </div>
    @endif
@endsection
