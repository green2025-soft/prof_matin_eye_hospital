@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    <div class="app-page-heading d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h4 fw-semibold mb-1">{{ $pageTitle }}</h1>
            <p class="text-body-secondary mb-0">{{ $branchName }} · <time datetime="{{ $todayIso }}">{{ $todayLabel }}</time></p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            @if ($canCreateGeneral)
                <a class="btn btn-primary" href="{{ route('prescription.prescriptions.create') }}"><i class="fa-solid fa-plus me-2" aria-hidden="true"></i>New Prescription</a>
            @endif
            @if ($canCreateGlass)
                <a class="btn btn-outline-primary" href="{{ route('prescription.glass-prescriptions.create') }}"><i class="fa-solid fa-glasses me-2" aria-hidden="true"></i>New Glass Prescription</a>
            @endif
        </div>
    </div>

    @if ($canViewGeneral || $canViewGlass)
        <section class="app-stat-grid" aria-label="Today's prescriptions">
            <article class="app-stat-card">
                <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
                    <span class="app-stat-label">Today's Total</span>
                    <span class="app-stat-icon app-tone-primary"><i class="fa-solid fa-file-prescription" aria-hidden="true"></i></span>
                </div>
                <p class="app-stat-value">{{ number_format($totalToday) }}</p>
                <p class="app-stat-caption mb-0">From the prescription lists available to you</p>
            </article>
            @if ($canViewGeneral)
                <article class="app-stat-card">
                    <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
                        <span class="app-stat-label">General Prescriptions</span>
                        <span class="app-stat-icon app-tone-success"><i class="fa-solid fa-prescription" aria-hidden="true"></i></span>
                    </div>
                    <p class="app-stat-value">{{ number_format($generalToday) }}</p>
                    <p class="app-stat-caption mb-0">Prescribed Today</p>
                </article>
            @endif
            @if ($canViewGlass)
                <article class="app-stat-card">
                    <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
                        <span class="app-stat-label">Glass Prescriptions</span>
                        <span class="app-stat-icon app-tone-info"><i class="fa-solid fa-glasses" aria-hidden="true"></i></span>
                    </div>
                    <p class="app-stat-value">{{ number_format($glassToday) }}</p>
                    <p class="app-stat-caption mb-0">Prescribed today</p>
                </article>
            @endif
        </section>

        <div class="row row-cols-1 row-cols-xl-2 g-3">
            @if ($canViewGeneral)
                <div class="col flex-grow-1">
                    <section class="app-card overflow-hidden h-100">
                        <div class="app-section-head">
                            <div>
                                <h2 class="app-section-title">Recent General Prescription</h2>
                                <p class="app-section-note">The latest six at this branch</p>
                            </div>
                            <a class="btn btn-outline-secondary btn-sm text-nowrap" href="{{ route('prescription.prescriptions.index') }}">View all</a>
                        </div>
                        @include('prescription::dashboard.recent', [
                            'records' => $recentGeneral,
                            'showRoute' => 'prescription.prescriptions.show',
                            'caption' => 'Recent general prescriptions',
                        ])
                    </section>
                </div>
            @endif
            @if ($canViewGlass)
                <div class="col flex-grow-1">
                    <section class="app-card overflow-hidden h-100">
                        <div class="app-section-head">
                            <div>
                                <h2 class="app-section-title">Recent Glass Prescription</h2>
                                <p class="app-section-note">The latest six at this branch</p>
                            </div>
                            <a class="btn btn-outline-secondary btn-sm text-nowrap" href="{{ route('prescription.glass-prescriptions.index') }}">View all</a>
                        </div>
                        @include('prescription::dashboard.recent', [
                            'records' => $recentGlass,
                            'showRoute' => 'prescription.glass-prescriptions.show',
                            'caption' => 'Recent glass prescriptions',
                        ])
                    </section>
                </div>
            @endif
        </div>
    @else
        <section class="app-card app-dashboard-empty">
            <i class="fa-solid fa-lock" aria-hidden="true"></i>
            <h2 class="h6">No prescription lists available</h2>
            <p>Your permissions and enabled prescription features determine which lists appear here.</p>
        </section>
    @endif
@endsection
