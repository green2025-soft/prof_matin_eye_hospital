@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    <header class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
        <div>
            <h1 class="h4 fw-semibold mb-1">{{ $pageTitle }}</h1>
            <p class="small text-body-secondary mb-0">
                @if (filled($branchName)){{ $branchName }} &middot; @endif
                <time datetime="{{ $todayIso }}">{{ $todayLabel }}</time>
            </p>
        </div>
        <span class="badge text-bg-primary px-3 py-2"><i class="fa-solid fa-prescription me-2" aria-hidden="true"></i>Prescription</span>
    </header>

    @if ($canCreateGeneral || $canCreateGlass || $canViewGeneral || $canViewGlass)
        <section class="mb-4" aria-labelledby="prescription-quick-entry-heading">
            <h2 class="h6 fw-semibold mb-3" id="prescription-quick-entry-heading">Quick access</h2>
            <div class="row row-cols-2 row-cols-lg-4 g-3">
                @if ($canCreateGeneral)
                    <div class="col flex-grow-1">
                        <a class="btn btn-primary bg-gradient border-0 rounded-3 shadow-sm d-flex flex-column align-items-center justify-content-center gap-2 py-3 h-100"
                            href="{{ route('prescription.prescriptions.create') }}">
                            <i class="fa-solid fa-file-circle-plus fs-3" aria-hidden="true"></i>
                            <span class="small fw-semibold">New prescription</span>
                        </a>
                    </div>
                @endif
                @if ($canCreateGlass)
                    <div class="col flex-grow-1">
                        <a class="btn btn-info bg-gradient border-0 rounded-3 shadow-sm d-flex flex-column align-items-center justify-content-center gap-2 py-3 h-100"
                            href="{{ route('prescription.glass-prescriptions.create') }}">
                            <i class="fa-solid fa-glasses fs-3" aria-hidden="true"></i>
                            <span class="small fw-semibold">New glass prescription</span>
                        </a>
                    </div>
                @endif
                @if ($canViewGeneral)
                    <div class="col flex-grow-1">
                        <a class="btn btn-success bg-gradient border-0 rounded-3 shadow-sm d-flex flex-column align-items-center justify-content-center gap-2 py-3 h-100"
                            href="{{ route('prescription.prescriptions.index') }}">
                            <i class="fa-solid fa-file-prescription fs-3" aria-hidden="true"></i>
                            <span class="small fw-semibold">General prescriptions</span>
                        </a>
                    </div>
                @endif
                @if ($canViewGlass)
                    <div class="col flex-grow-1">
                        <a class="btn btn-warning bg-gradient border-0 rounded-3 shadow-sm d-flex flex-column align-items-center justify-content-center gap-2 py-3 h-100"
                            href="{{ route('prescription.glass-prescriptions.index') }}">
                            <i class="fa-solid fa-list-check fs-3" aria-hidden="true"></i>
                            <span class="small fw-semibold">Glass prescriptions</span>
                        </a>
                    </div>
                @endif
            </div>
        </section>
    @endif

    @if ($canViewGeneral || $canViewGlass)
        <section class="row row-cols-1 row-cols-sm-2 row-cols-xl-3 g-3 mb-3" aria-label="Today's prescriptions">
            <div class="col flex-grow-1">
                <article class="bg-primary-subtle border border-primary-subtle rounded-3 d-flex align-items-center gap-3 h-100 p-3">
                    <span class="d-inline-flex align-items-center justify-content-center flex-shrink-0 rounded-circle bg-white text-primary p-3">
                        <i class="fa-solid fa-file-prescription fs-4" aria-hidden="true"></i>
                    </span>
                    <div>
                        <h2 class="small fw-medium text-primary-emphasis mb-1">Today's total</h2>
                        <p class="h3 fw-bold text-body-emphasis mb-1">{{ number_format($totalToday) }}</p>
                        <p class="small text-body-secondary mb-0">From your available lists</p>
                    </div>
                </article>
            </div>
            @if ($canViewGeneral)
                <div class="col flex-grow-1">
                    <article class="bg-success-subtle border border-success-subtle rounded-3 d-flex align-items-center gap-3 h-100 p-3">
                        <span class="d-inline-flex align-items-center justify-content-center flex-shrink-0 rounded-circle bg-white text-success p-3">
                            <i class="fa-solid fa-prescription fs-4" aria-hidden="true"></i>
                        </span>
                        <div>
                            <h2 class="small fw-medium text-success-emphasis mb-1">General prescriptions</h2>
                            <p class="h3 fw-bold text-body-emphasis mb-1">{{ number_format($generalToday) }}</p>
                            <p class="small text-body-secondary mb-0">Prescribed today</p>
                        </div>
                    </article>
                </div>
            @endif
            @if ($canViewGlass)
                <div class="col flex-grow-1">
                    <article class="bg-info-subtle border border-info-subtle rounded-3 d-flex align-items-center gap-3 h-100 p-3">
                        <span class="d-inline-flex align-items-center justify-content-center flex-shrink-0 rounded-circle bg-white text-info-emphasis p-3">
                            <i class="fa-solid fa-glasses fs-4" aria-hidden="true"></i>
                        </span>
                        <div>
                            <h2 class="small fw-medium text-info-emphasis mb-1">Glass prescriptions</h2>
                            <p class="h3 fw-bold text-body-emphasis mb-1">{{ number_format($glassToday) }}</p>
                            <p class="small text-body-secondary mb-0">Prescribed today</p>
                        </div>
                    </article>
                </div>
            @endif
        </section>

        <div class="row row-cols-1 row-cols-xl-2 g-3">
            @if ($canViewGeneral)
                <div class="col flex-grow-1">
                    <section class="app-card overflow-hidden h-100" aria-labelledby="prescription-recent-general-heading">
                        <header class="d-flex flex-wrap align-items-center justify-content-between gap-3 p-3 border-bottom bg-success-subtle">
                            <div>
                                <h2 class="h6 fw-semibold mb-1" id="prescription-recent-general-heading">Recent general prescriptions</h2>
                                <p class="small text-body-secondary mb-0">Latest 6 prescriptions</p>
                            </div>
                            <a class="btn btn-success btn-sm" href="{{ route('prescription.prescriptions.index') }}">View all</a>
                        </header>
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
                    <section class="app-card overflow-hidden h-100" aria-labelledby="prescription-recent-glass-heading">
                        <header class="d-flex flex-wrap align-items-center justify-content-between gap-3 p-3 border-bottom bg-info-subtle">
                            <div>
                                <h2 class="h6 fw-semibold mb-1" id="prescription-recent-glass-heading">Recent glass prescriptions</h2>
                                <p class="small text-body-secondary mb-0">Latest 6 prescriptions</p>
                            </div>
                            <a class="btn btn-info btn-sm" href="{{ route('prescription.glass-prescriptions.index') }}">View all</a>
                        </header>
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
        <section class="app-card p-4 text-center">
            <i class="fa-solid fa-lock text-body-secondary fs-4 mb-3" aria-hidden="true"></i>
            <h2 class="h6 fw-semibold">No prescription lists available</h2>
            <p class="small text-body-secondary mb-0">Your permissions and enabled prescription features determine which lists appear here.</p>
        </section>
    @endif
@endsection
