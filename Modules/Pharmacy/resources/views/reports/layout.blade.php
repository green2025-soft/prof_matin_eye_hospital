@extends('core::layouts.app')

@section('title', ucfirst($filters['product_type']).' '.$pageTitle)

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3 d-print-none">
        @include('core::partials.heading', ['title' => ucfirst($filters['product_type']).' '.$pageTitle])
        <button class="btn btn-outline-primary" type="button" data-print>
            <i class="fa-solid fa-print me-2" aria-hidden="true"></i>Print report
        </button>
    </div>

    <nav class="d-flex flex-wrap gap-2 mb-3 d-print-none" aria-label="Pharmacy reports">
        <a @class(['btn btn-sm', 'btn-primary' => request()->routeIs('pharmacy.reports.stock'), 'btn-outline-secondary' => ! request()->routeIs('pharmacy.reports.stock')]) href="{{ route('pharmacy.reports.stock', ['product_type' => $filters['product_type']]) }}">Stock</a>
        <a @class(['btn btn-sm', 'btn-primary' => request()->routeIs('pharmacy.reports.movements'), 'btn-outline-secondary' => ! request()->routeIs('pharmacy.reports.movements')]) href="{{ route('pharmacy.reports.movements', ['product_type' => $filters['product_type']]) }}">Movements</a>
        <a @class(['btn btn-sm', 'btn-primary' => request()->routeIs('pharmacy.reports.expiry'), 'btn-outline-secondary' => ! request()->routeIs('pharmacy.reports.expiry')]) href="{{ route('pharmacy.reports.expiry', ['product_type' => 'medicine']) }}">Medicine expiry</a>
        <a @class(['btn btn-sm', 'btn-primary' => request()->routeIs('pharmacy.reports.profit-loss'), 'btn-outline-secondary' => ! request()->routeIs('pharmacy.reports.profit-loss')]) href="{{ route('pharmacy.reports.profit-loss', ['product_type' => $filters['product_type']]) }}">Profit / loss</a>
        <a @class(['btn btn-sm', 'btn-primary' => request()->routeIs('pharmacy.reports.purchase-discounts'), 'btn-outline-secondary' => ! request()->routeIs('pharmacy.reports.purchase-discounts')]) href="{{ route('pharmacy.reports.purchase-discounts', ['product_type' => $filters['product_type']]) }}">Purchase discounts</a>
        <a @class(['btn btn-sm', 'btn-primary' => request()->routeIs('pharmacy.reports.daily'), 'btn-outline-secondary' => ! request()->routeIs('pharmacy.reports.daily')]) href="{{ route('pharmacy.reports.daily', ['product_type' => $filters['product_type']]) }}">Daily</a>
        <a @class(['btn btn-sm', 'btn-primary' => request()->routeIs('pharmacy.reports.customer-dues'), 'btn-outline-secondary' => ! request()->routeIs('pharmacy.reports.customer-dues')]) href="{{ route('pharmacy.reports.customer-dues', ['product_type' => $filters['product_type']]) }}">Customer dues</a>
        <a @class(['btn btn-sm', 'btn-primary' => request()->routeIs('pharmacy.reports.supplier-dues'), 'btn-outline-secondary' => ! request()->routeIs('pharmacy.reports.supplier-dues')]) href="{{ route('pharmacy.reports.supplier-dues', ['product_type' => $filters['product_type']]) }}">Supplier dues</a>
    </nav>

    <div class="app-card mb-3 d-print-none">
        @yield('report-filters')
    </div>

    <article class="app-card app-pharmacy-report @yield('report-page-class')" aria-label="{{ $pageTitle }}">
        <header class="app-report-letterhead">
            @isset($reportBrand['logo_url'])
                <img class="app-report-logo" src="{{ $reportBrand['logo_url'] }}" width="76" height="76" alt="">
            @endisset
            <div class="app-report-brand">
                <h2>{{ $reportBrand['name'] }}</h2>
                <p>{{ $reportBrand['tagline'] }}</p>
                <p>{{ $reportBrand['address'] }}</p>
                <p>{{ $reportBrand['contact'] }}</p>
                <p class="app-report-branch">{{ $reportBrand['branch_name'] }}</p>
            </div>
        </header>
        <div class="app-report-title">
            <h3>{{ ucfirst($filters['product_type']) }} {{ $pageTitle }}</h3>
            <span>Amounts in BDT</span>
        </div>
        <div class="app-report-context">
            @yield('report-context')
        </div>

        @yield('report-summary')
        @yield('report-table')

        <footer class="app-report-footer">
            <span>Printed by: {{ $printedBy }}</span>
            <span>Printed: {{ $printedAt }}</span>
            <span class="d-print-none">Page {{ $records->currentPage() }} of {{ $records->lastPage() }}</span>
        </footer>
        <div class="app-report-signatures d-none d-print-flex">
            <span>Prepared by</span>
            <span>Checked by</span>
            <span>Authorized signature</span>
        </div>
    </article>

    <div class="d-print-none">
        @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters])
    </div>
@endsection
