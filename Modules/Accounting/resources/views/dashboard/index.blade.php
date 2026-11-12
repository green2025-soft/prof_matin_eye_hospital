@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    <header class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h4 fw-semibold mb-1">{{ $pageTitle }}</h1>
            <time class="small text-body-secondary" datetime="{{ $todayIso }}">{{ $todayLabel }}</time>
        </div>
        <span class="badge text-bg-success px-3 py-2"><i class="fa-solid fa-book-open me-2" aria-hidden="true"></i>Accounting</span>
    </header>

    <nav class="row row-cols-2 row-cols-lg-5 g-3 mb-4" aria-label="Accounting quick actions">
        @if ($canCreateJournal)
            <div class="col">
                <a class="btn btn-success bg-gradient border-0 rounded-3 shadow-sm d-flex flex-column align-items-center justify-content-center gap-2 py-3 h-100"
                    href="{{ route('accounting.journal-entries.create') }}">
                    <i class="fa-solid fa-file-circle-plus fs-3" aria-hidden="true"></i><span class="small fw-semibold">New voucher</span>
                </a>
            </div>
        @endif
        @if ($canViewJournals)
            <div class="col">
                <a class="btn btn-primary bg-gradient border-0 rounded-3 shadow-sm d-flex flex-column align-items-center justify-content-center gap-2 py-3 h-100"
                    href="{{ route('accounting.journal-entries.index') }}">
                    <i class="fa-solid fa-receipt fs-3" aria-hidden="true"></i><span class="small fw-semibold">Vouchers</span>
                </a>
            </div>
        @endif
        @if ($canViewAccounts)
            <div class="col">
                <a class="btn btn-warning bg-gradient border-0 rounded-3 shadow-sm d-flex flex-column align-items-center justify-content-center gap-2 py-3 h-100"
                    href="{{ route('accounting.chart-of-accounts.index') }}">
                    <i class="fa-solid fa-sitemap fs-3" aria-hidden="true"></i><span class="small fw-semibold">Chart of Accounts</span>
                </a>
            </div>
        @endif
        @if ($canViewReports)
            <div class="col">
                <a class="btn btn-danger bg-gradient border-0 rounded-3 shadow-sm d-flex flex-column align-items-center justify-content-center gap-2 py-3 h-100"
                    href="{{ route('accounting.reports.trial-balance') }}">
                    <i class="fa-solid fa-chart-column fs-3" aria-hidden="true"></i><span class="small fw-semibold">Reports</span>
                </a>
            </div>
        @endif
        @if ($canViewMappings)
            <div class="col">
                <a class="btn btn-info bg-gradient border-0 rounded-3 shadow-sm d-flex flex-column align-items-center justify-content-center gap-2 py-3 h-100"
                    href="{{ route('accounting.account-modules.index') }}">
                    <i class="fa-solid fa-sliders fs-3" aria-hidden="true"></i><span class="small fw-semibold">Account mappings</span>
                </a>
            </div>
        @endif
    </nav>

    <section class="row g-3 mb-4" aria-label="Current branch accounting activity">
        @if ($canViewJournals)
            <div class="col-md-6 col-xl-4">
                <article class="bg-primary-subtle border border-primary-subtle rounded-3 d-flex align-items-center gap-3 p-3 h-100">
                    <span class="bg-white text-primary rounded-circle p-3"><i class="fa-solid fa-file-invoice fs-4" aria-hidden="true"></i></span>
                    <div><h2 class="small fw-medium mb-1">Today's vouchers</h2><p class="h3 fw-bold mb-0">{{ number_format($todayCount) }}</p></div>
                </article>
            </div>
            <div class="col-md-6 col-xl-4">
                <article class="bg-success-subtle border border-success-subtle rounded-3 d-flex align-items-center gap-3 p-3 h-100">
                    <span class="bg-white text-success rounded-circle p-3"><i class="fa-solid fa-bangladeshi-taka-sign fs-4" aria-hidden="true"></i></span>
                    <div><h2 class="small fw-medium mb-1">Today's voucher value</h2><p class="h4 fw-bold mb-0">৳ {{ moneyDecimal($todayTotal) }}</p></div>
                </article>
            </div>
        @endif
        @if ($canViewAccounts)
            <div class="col-md-6 col-xl-4">
                <article class="bg-warning-subtle border border-warning-subtle rounded-3 d-flex align-items-center gap-3 p-3 h-100">
                    <span class="bg-white text-warning-emphasis rounded-circle p-3"><i class="fa-solid fa-book fs-4" aria-hidden="true"></i></span>
                    <div><h2 class="small fw-medium mb-1">Active ledger accounts</h2><p class="h3 fw-bold mb-0">{{ number_format($ledgerCount) }}</p></div>
                </article>
            </div>
        @endif
    </section>

    @if ($canViewJournals)
        <section class="app-card overflow-hidden" aria-labelledby="recent-vouchers-heading">
            <div class="d-flex align-items-center justify-content-between gap-2 p-3 border-bottom">
                <h2 class="h6 mb-0" id="recent-vouchers-heading"><i class="fa-solid fa-clock-rotate-left text-primary me-2" aria-hidden="true"></i>Recent vouchers</h2>
                <a class="small" href="{{ route('accounting.journal-entries.index') }}">View all</a>
            </div>
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <thead><tr><th scope="col">Voucher no.</th><th scope="col">Date</th><th scope="col">Type</th><th class="text-end" scope="col">Amount (BDT)</th></tr></thead>
                    <tbody>
                        @forelse ($recentEntries as $entry)
                            <tr>
                                <th scope="row"><a href="{{ route('accounting.journal-entries.show', $entry) }}">{{ $entry->number }}</a></th>
                                <td class="text-nowrap">{{ $entry->entry_date->format('d M Y') }}</td>
                                <td>{{ $entry->voucherType->name }} @if ($entry->voided_at)<span class="badge text-bg-secondary ms-1">Voided</span>@endif</td>
                                <td class="text-end text-nowrap">{{ moneyDecimal($entry->total) }}</td>
                            </tr>
                        @empty
                            <tr><td class="text-center text-body-secondary py-4" colspan="4">No vouchers yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    @endif
@endsection
