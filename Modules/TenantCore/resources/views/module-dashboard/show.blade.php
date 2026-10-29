@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    <nav class="d-flex flex-wrap gap-2 mb-4" aria-label="Module navigation">
        <a class="btn btn-outline-secondary btn-sm" href="{{ $moduleDashboardUrl }}"><i class="fa-solid fa-arrow-left me-2" aria-hidden="true"></i>All modules</a>
        <a class="btn btn-outline-secondary btn-sm" href="{{ $workspaceDashboardUrl }}"><i class="fa-solid fa-table-cells-large me-2" aria-hidden="true"></i>Workspace overview</a>
        @if ($canSelectBranch)
            <a class="btn btn-outline-secondary btn-sm" href="{{ $branchDashboardUrl }}"><i class="fa-solid fa-location-dot me-2" aria-hidden="true"></i>Choose branch</a>
        @endif
    </nav>

    <section class="app-card p-3 p-md-4" aria-labelledby="module-title">
        <div class="d-flex align-items-start gap-3">
            <span class="app-selection-mark"><i class="fa-solid fa-{{ $module['icon'] }}" aria-hidden="true"></i></span>
            <div class="overflow-hidden">
                <h1 class="h4 fw-semibold mb-2 text-break" id="module-title">{{ $pageTitle }}</h1>
                <p class="text-body-secondary mb-0">{{ $module['description'] }}</p>
            </div>
        </div>
        <div class="border-top mt-4 pt-4">
            <h2 class="h6">Working in this module</h2>
            <p class="small text-body-secondary mb-0">Choose a branch when opening a branch-based feature.</p>
        </div>
    </section>
@endsection
