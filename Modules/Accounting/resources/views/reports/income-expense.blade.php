@extends('accounting::reports.layout')

@section('report-filters')
    @include('accounting::reports.month-filters')
@endsection

@section('report-context')
    <span>Month: <strong>{{ $periods['current']['from_date'] }}</strong> to <strong>{{ $periods['current']['to_date'] }}</strong></span>
    <span>Financial year: <strong>{{ $periods['fiscal']['from_date'] }}</strong> to <strong>{{ $periods['fiscal']['to_date'] }}</strong></span>
@endsection

@section('report-summary')
    <div class="row row-cols-1 row-cols-sm-3 g-3 p-3 border-bottom small">
        <div class="col">Monthly income<strong class="d-block">{{ moneyDecimal((string) $income) }}</strong></div>
        <div class="col">Monthly expenditure<strong class="d-block">{{ moneyDecimal((string) $expense) }}</strong></div>
        <div class="col">Monthly surplus / (deficit)<strong class="d-block">{{ moneyDecimal((string) $profit) }}</strong></div>
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
                    <th scope="col" class="text-end">{{ $periods['current']['label'] }}</th>
                    <th scope="col" class="text-end">{{ $periods['fiscal']['label'] }}</th>
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
                                    <a class="link-primary" href="#income-note-{{ $row['account']->id }}" data-bs-toggle="modal"
                                        aria-label="Note {{ $row['note'] }}: {{ $row['account']->name }}">{{ $row['note'] }}</a>
                                @endif
                            </td>
                            <td class="text-end text-nowrap">{{ moneyDecimal((string) $row['current']) }}</td>
                            <td class="text-end text-nowrap">{{ moneyDecimal((string) $row['comparison']) }}</td>
                        </tr>
                    @empty
                        <tr><td class="text-body-secondary py-3" colspan="4">No {{ $type }} ledger activity through this month.</td></tr>
                    @endforelse
                    <tr class="fw-semibold border-top">
                        <th scope="row" colspan="2">Total {{ $section['label'] }}</th>
                        <td class="text-end text-nowrap">{{ moneyDecimal((string) $section['current']) }}</td>
                        <td class="text-end text-nowrap">{{ moneyDecimal((string) $section['comparison']) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="table-light">
                    <th scope="row" colspan="2">Surplus / (Deficit) (A&minus;B)</th>
                    <th class="text-end text-nowrap">{{ moneyDecimal((string) $profit) }}</th>
                    <th class="text-end text-nowrap">{{ moneyDecimal((string) $fiscalProfit) }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
    <p class="small text-body-secondary m-3">Financial-year totals start on 1 July. Group totals include their subaccounts; only unvoided journal entries are included.</p>

    @foreach ($rows->whereNotNull('note') as $row)
        <div class="modal fade d-print-none" id="income-note-{{ $row['account']->id }}" tabindex="-1"
            aria-labelledby="income-note-title-{{ $row['account']->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title fs-5" id="income-note-title-{{ $row['account']->id }}">Note {{ $row['note'] }}: {{ $row['account']->name }}</h2>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="small text-body-secondary">{{ $row['account']->code }} &ndash; Ledger account breakdown. Amounts in BDT.</p>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead><tr><th scope="col">Account</th><th scope="col" class="text-end">{{ $periods['current']['label'] }}</th><th scope="col" class="text-end">{{ $periods['fiscal']['label'] }}</th></tr></thead>
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
