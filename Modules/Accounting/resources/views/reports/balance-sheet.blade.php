@extends('accounting::reports.layout')

@section('report-filters')
    @include('accounting::reports.month-filters')
@endsection

@section('report-context')
    <span>As at <strong>{{ $periods['current']['to_date'] }}</strong></span>
    <span>Compared with the financial-year closing balance at <strong>{{ $periods['previous_fiscal']['to_date'] }}</strong></span>
@endsection

@section('report-summary')
    <div class="row row-cols-1 row-cols-sm-3 g-3 p-3 border-bottom small">
        <div class="col">Assets<strong class="d-block">{{ moneyDecimal((string) $assets) }}</strong></div>
        <div class="col">Liabilities &amp; equity<strong class="d-block">{{ moneyDecimal((string) $liabilitiesAndEquity) }}</strong></div>
        <div class="col">Difference<strong class="d-block">{{ moneyDecimal((string) $difference) }}</strong></div>
    </div>
@endsection

@section('report-table')
    <div class="table-responsive">
        <table class="table table-sm app-table app-report-table align-middle mb-0">
            <thead>
                <tr>
                    <th scope="col" rowspan="2">Particulars</th>
                    <th scope="col" rowspan="2" class="text-center">Notes</th>
                    <th scope="colgroup" colspan="2" class="text-center">Amount (BDT)</th>
                </tr>
                <tr>
                    <th scope="col" class="text-end">{{ $periods['current']['to_date'] }}</th>
                    <th scope="col" class="text-end">{{ $periods['previous_fiscal']['to_date'] }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sections as $type => $section)
                    <tr class="table-light"><th scope="rowgroup" colspan="4">{{ $section['label'] }}</th></tr>
                    @forelse ($rows->where('account.type', $type) as $row)
                        <tr class="{{ $row['note'] === null ? 'fw-semibold' : '' }}">
                            <th scope="row" class="ps-{{ $row['account']->depth }}">{{ $row['account']->code }} &ndash; {{ $row['account']->name }}</th>
                            <td class="text-center">
                                @if ($row['note'] !== null)
                                    <a class="link-primary" href="#balance-note-{{ $row['account']->id }}" data-bs-toggle="modal"
                                        aria-label="Note {{ $row['note'] }}: {{ $row['account']->name }}">{{ $row['note'] }}</a>
                                @endif
                            </td>
                            <td class="text-end text-nowrap">{{ moneyDecimal((string) $row['current']) }}</td>
                            <td class="text-end text-nowrap">{{ moneyDecimal((string) $row['comparison']) }}</td>
                        </tr>
                    @empty
                        <tr><td class="text-body-secondary py-3" colspan="4">No {{ $type }} ledger activity through this month.</td></tr>
                    @endforelse
                    @if ($type === 'equity')
                        <tr>
                            <th scope="row" colspan="2" class="ps-2">Unclosed accumulated profit / loss</th>
                            <td class="text-end text-nowrap">{{ moneyDecimal((string) $earnings) }}</td>
                            <td class="text-end text-nowrap">{{ moneyDecimal((string) $comparisonEarnings) }}</td>
                        </tr>
                    @endif
                    <tr class="fw-semibold border-top">
                        <th scope="row" colspan="2">Total {{ $section['label'] }}</th>
                        <td class="text-end text-nowrap">{{ moneyDecimal((string) $section['current']) }}</td>
                        <td class="text-end text-nowrap">{{ moneyDecimal((string) $section['comparison']) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr><th scope="row" colspan="2">Total liabilities &amp; equity</th><th class="text-end text-nowrap">{{ moneyDecimal((string) $liabilitiesAndEquity) }}</th><th class="text-end text-nowrap">{{ moneyDecimal((string) $comparisonLiabilitiesAndEquity) }}</th></tr>
                <tr><th scope="row" colspan="2">Difference</th><th class="text-end text-nowrap">{{ moneyDecimal((string) $difference) }}</th><th class="text-end text-nowrap">{{ moneyDecimal((string) $comparisonDifference) }}</th></tr>
            </tfoot>
        </table>
    </div>
    <p class="small text-body-secondary m-3">Balances include all unvoided ledger history through each stated date. Group totals include their subaccounts; unclosed earnings are shown separately within equity.</p>

    @foreach ($rows->whereNotNull('note') as $row)
        <div class="modal fade d-print-none" id="balance-note-{{ $row['account']->id }}" tabindex="-1"
            aria-labelledby="balance-note-title-{{ $row['account']->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title fs-5" id="balance-note-title-{{ $row['account']->id }}">Note {{ $row['note'] }}: {{ $row['account']->name }}</h2>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="small text-body-secondary">{{ $row['account']->code }} &ndash; Ledger account breakdown. Amounts in BDT.</p>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead><tr><th scope="col">Account</th><th scope="col" class="text-end">{{ $periods['current']['to_date'] }}</th><th scope="col" class="text-end">{{ $periods['previous_fiscal']['to_date'] }}</th></tr></thead>
                                <tbody>
                                    @foreach ($row['accounts'] as $account)
                                        <tr>
                                            <th scope="row">{{ $account->code }} &ndash; {{ $account->name }}</th>
                                            <td class="text-end text-nowrap">{{ moneyDecimal((string) $account->current_amount) }}</td>
                                            <td class="text-end text-nowrap">{{ moneyDecimal((string) $account->comparison_amount) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot><tr><th scope="row">Total</th><th class="text-end">{{ moneyDecimal((string) $row['current']) }}</th><th class="text-end">{{ moneyDecimal((string) $row['comparison']) }}</th></tr></tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer"><button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button></div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
