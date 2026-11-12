@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3 d-print-none">
        @include('core::partials.heading', ['title' => $pageTitle])
        <button class="btn btn-outline-primary" type="button" data-print>
            <i class="fa-solid fa-print me-2" aria-hidden="true"></i>Print report
        </button>
    </div>
    <nav class="d-flex flex-wrap gap-2 mb-3 d-print-none" aria-label="Accounting reports">
        <a class="btn btn-sm {{ request()->routeIs('accounting.reports.ledger') ? 'btn-primary' : 'btn-outline-secondary' }}"
            href="{{ route('accounting.reports.ledger') }}">Ledger</a>
        <a class="btn btn-sm {{ request()->routeIs('accounting.reports.trial-balance') ? 'btn-primary' : 'btn-outline-secondary' }}"
            href="{{ route('accounting.reports.trial-balance') }}">Trial Balance</a>
        <a class="btn btn-sm {{ request()->routeIs('accounting.reports.income-expense') ? 'btn-primary' : 'btn-outline-secondary' }}"
            href="{{ route('accounting.reports.income-expense') }}">Income–Expense</a>
        <a class="btn btn-sm {{ request()->routeIs('accounting.reports.balance-sheet') ? 'btn-primary' : 'btn-outline-secondary' }}"
            href="{{ route('accounting.reports.balance-sheet') }}">Balance Sheet</a>
        <a class="btn btn-sm {{ request()->routeIs('accounting.reports.cash-flow') ? 'btn-primary' : 'btn-outline-secondary' }}"
            href="{{ route('accounting.reports.cash-flow') }}">Cash Flow</a>
        <a class="btn btn-sm {{ request()->routeIs('accounting.reports.cash-bank-book') ? 'btn-primary' : 'btn-outline-secondary' }}"
            href="{{ route('accounting.reports.cash-bank-book') }}">Cash & Bank Book</a>
        <a class="btn btn-sm {{ request()->routeIs('accounting.reports.changes-in-equity') ? 'btn-primary' : 'btn-outline-secondary' }}"
            href="{{ route('accounting.reports.changes-in-equity') }}">Changes in Equity</a>
        <a class="btn btn-sm {{ request()->routeIs('accounting.reports.receipts-payments') ? 'btn-primary' : 'btn-outline-secondary' }}"
            href="{{ route('accounting.reports.receipts-payments') }}">Receipts & Payments</a>
    </nav>

    <div class="app-card mb-3 d-print-none">@yield('report-filters')</div>

    <article class="app-card app-pharmacy-report" aria-label="{{ $pageTitle }}">
        @include('tenantcore::partials.report-letterhead')
        <div class="app-report-title d-flex flex-wrap align-items-center justify-content-between gap-2">
            <h3>{{ $pageTitle }}</h3>
            <span>Amounts in BDT</span>
        </div>
        <div class="app-report-context d-flex flex-wrap justify-content-between gap-2">
            @yield('report-context')
        </div>
        @yield('report-summary')
        @yield('report-table')

        <footer class="app-report-footer d-flex flex-wrap justify-content-between gap-2">
            <span>Printed by: {{ $printedBy }}</span>
            <span>Printed: {{ $printedAt }}</span>
        </footer>
        <div class="app-report-signatures d-none d-print-flex flex-wrap justify-content-between gap-2">
            <span>Prepared by</span><span>Checked by</span><span>Authorized signature</span>
        </div>
    </article>
    <div class="d-print-none mt-3">@yield('report-pagination')</div>
@endsection
