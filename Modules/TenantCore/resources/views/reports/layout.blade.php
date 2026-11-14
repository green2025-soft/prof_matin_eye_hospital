@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3 d-print-none">
        @include('core::partials.heading', ['title' => $pageTitle])
        <button class="btn btn-outline-primary" type="button" data-print><i class="fa-solid fa-print me-2" aria-hidden="true"></i>Print report</button>
    </div>

    <div class="app-card p-3 mb-3 d-print-none">@yield('report-filters')</div>

    <article class="app-card app-pharmacy-report" aria-label="{{ $pageTitle }}">
        @include('tenantcore::partials.report-letterhead')
        <div class="app-report-title d-flex flex-wrap align-items-center justify-content-between gap-2">
            <h3>{{ $pageTitle }}</h3>
        </div>
        <div class="app-report-context">Period: {{ $fromDate }} to {{ $toDate }}</div>

        @section('report-summary')
            @include('tenantcore::reports.partials.balances')
        @show
        @yield('report-table')

        <footer class="app-report-footer d-flex flex-wrap justify-content-between gap-2">
            <span>Printed by: {{ $printedBy }}</span>
            <span>Printed: {{ $printedAt }}</span>
        </footer>
        <div class="app-report-signatures d-none d-print-flex justify-content-between gap-3">
            <span>Prepared by</span><span>Checked by</span><span>Authorized signature</span>
        </div>
    </article>
@endsection
