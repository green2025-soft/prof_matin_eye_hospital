@extends('core::layouts.app')

@php($reportTitle = ucfirst($filters['product_type']).' '.$pageTitle)

@section('title', $reportTitle)

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3 d-print-none">
        @include('core::partials.heading', ['title' => $reportTitle])
        <button class="btn btn-outline-primary" type="button" data-print>
            <i class="fa-solid fa-print me-2" aria-hidden="true"></i>Print report
        </button>
    </div>

    <nav class="d-flex flex-wrap gap-2 mb-3 d-print-none" aria-label="Pharmacy reports">
        <a class="btn btn-sm {{ request()->routeIs('pharmacy.reports.stock') ? 'btn-primary' : 'btn-outline-secondary' }}" href="{{ route('pharmacy.reports.stock', ['product_type' => $filters['product_type']]) }}">Stock</a>
        <a class="btn btn-sm {{ request()->routeIs('pharmacy.reports.movements') ? 'btn-primary' : 'btn-outline-secondary' }}" href="{{ route('pharmacy.reports.movements', ['product_type' => $filters['product_type']]) }}">Movements</a>
        <a class="btn btn-sm {{ request()->routeIs('pharmacy.reports.expiry') ? 'btn-primary' : 'btn-outline-secondary' }}" href="{{ route('pharmacy.reports.expiry', ['product_type' => 'medicine']) }}">Medicine expiry</a>
        <a class="btn btn-sm {{ request()->routeIs('pharmacy.reports.profit-loss') ? 'btn-primary' : 'btn-outline-secondary' }}"
            href="{{ route('pharmacy.reports.profit-loss', ['product_type' => $filters['product_type']]) }}">Profit / loss</a>
        <a class="btn btn-sm {{ request()->routeIs('pharmacy.reports.purchase-discounts') ? 'btn-primary' : 'btn-outline-secondary' }}"
            href="{{ route('pharmacy.reports.purchase-discounts', ['product_type' => $filters['product_type']]) }}">Purchase discounts</a>
        <a class="btn btn-sm {{ request()->routeIs('pharmacy.reports.daily') ? 'btn-primary' : 'btn-outline-secondary' }}" href="{{ route('pharmacy.reports.daily', ['product_type' => $filters['product_type']]) }}">Daily</a>
        <a class="btn btn-sm {{ request()->routeIs('pharmacy.reports.customer-dues') ? 'btn-primary' : 'btn-outline-secondary' }}"
            href="{{ route('pharmacy.reports.customer-dues', ['product_type' => $filters['product_type']]) }}">Customer dues</a>
        <a class="btn btn-sm {{ request()->routeIs('pharmacy.reports.supplier-dues') ? 'btn-primary' : 'btn-outline-secondary' }}"
            href="{{ route('pharmacy.reports.supplier-dues', ['product_type' => $filters['product_type']]) }}">Supplier dues</a>
    </nav>

    <div class="app-card mb-3 d-print-none">
        @yield('report-filters')
    </div>

    <article class="app-card app-pharmacy-report @yield('report-page-class')" aria-label="{{ $pageTitle }}">
        @include('tenantcore::partials.report-letterhead')
        <div class="app-report-title d-flex flex-wrap align-items-center justify-content-between row-gap-2 column-gap-4">
            <h3>{{ ucfirst($filters['product_type']) }} {{ $pageTitle }}</h3>
            <span>Amounts in BDT</span>
        </div>
        <div class="app-report-context d-flex flex-wrap justify-content-between row-gap-2 column-gap-4">
            @yield('report-context')
        </div>

        @yield('report-summary')
        @yield('report-table')

        <footer class="app-report-footer d-flex flex-wrap justify-content-between row-gap-2 column-gap-4">
            <span>Printed by: {{ $printedBy }}</span>
            <span>Printed: {{ $printedAt }}</span>
            <span class="d-print-none">Page {{ $records->currentPage() }} of {{ $records->lastPage() }}</span>
        </footer>
        <div class="app-report-signatures d-none d-print-flex flex-wrap justify-content-between row-gap-2 column-gap-4">
            <span>Prepared by</span>
            <span>Checked by</span>
            <span>Authorized signature</span>
        </div>
    </article>

    <div class="d-print-none">
        @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters])
    </div>
@endsection
