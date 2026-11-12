@extends('accounting::reports.layout')

@section('report-filters')
    @include('accounting::reports.month-filters')
@endsection

@section('report-context')
    <span>Month: <strong>{{ $periods['current']['label'] }}</strong></span>
    <span>{{ $periods['fiscal']['label'] }}: {{ $periods['fiscal']['from_date'] }} to {{ $periods['fiscal']['to_date'] }}</span>
@endsection

@section('report-summary')
    <div class="row row-cols-1 row-cols-sm-3 g-3 p-3 border-bottom small">
        <div class="col">Current month net cash<strong class="d-block">{{ moneyDecimal((string) $totals['current']['net_change']) }}</strong></div>
        <div class="col">Financial year net cash<strong class="d-block">{{ moneyDecimal((string) $totals['fiscal']['net_change']) }}</strong></div>
        <div class="col">Reconciliation difference<strong class="d-block">{{ moneyDecimal((string) $totals['current']['difference']) }}</strong></div>
    </div>
@endsection

@section('report-table')
    @if ($cashAccounts->isEmpty())
        <p class="p-3 mb-0 text-body-secondary">Assign Cash in hand or Cash at bank report roles in Chart of Accounts to include your cash ledgers.</p>
    @endif
    <div class="table-responsive">
        <table class="table table-sm app-table app-report-table align-middle mb-0">
            <caption class="visually-hidden">Cash flows from operating, investing and financing activities, compared with the financial year.</caption>
            <thead><tr><th scope="col">Particulars</th><th class="text-end" scope="col">Current month<br>{{ $periods['current']['label'] }}</th><th class="text-end" scope="col">For the period<br>{{ $periods['fiscal']['label'] }}</th></tr></thead>
            <tbody>
                @foreach ([
                    'operating' => 'Operating activities',
                    'investing' => 'Investing activities',
                    'financing' => 'Financing activities',
                    'unclassified' => 'Unclassified cash movements — review required',
                ] as $activity => $label)
                    @continue($activity === 'unclassified' && $groups[$activity] === [])
                    <tr class="table-light"><th colspan="3" scope="rowgroup">{{ $label }}</th></tr>
                    @forelse (collect($groups[$activity])->sortBy('account.code') as $row)
                        <tr>
                            <th class="fw-normal" scope="row">
                                @if ($row['account'] !== null)
                                    {{ $row['account']->code }} - {{ $row['account']->name }}
                                @else
                                    Allocation required: voucher {{ $row['entry']->number }}
                                @endif
                            </th>
                            <td class="text-end text-nowrap">{{ moneyDecimal((string) $row['current']) }}</td>
                            <td class="text-end text-nowrap">{{ moneyDecimal((string) $row['fiscal']) }}</td>
                        </tr>
                    @empty
                        <tr><td class="text-body-secondary" colspan="3">No cash movement.</td></tr>
                    @endforelse
                    <tr class="fw-semibold"><th scope="row">Net cash from {{ strtolower($label) }}</th><td class="text-end">{{ moneyDecimal((string) $totals['current'][$activity]) }}</td><td class="text-end">{{ moneyDecimal((string) $totals['fiscal'][$activity]) }}</td></tr>
                @endforeach
                <tr class="table-light fw-semibold"><th scope="row">Net increase / (decrease) in cash & bank</th><td class="text-end">{{ moneyDecimal((string) $totals['current']['net_change']) }}</td><td class="text-end">{{ moneyDecimal((string) $totals['fiscal']['net_change']) }}</td></tr>
                <tr class="table-light"><th colspan="3" scope="rowgroup">Opening cash & bank</th></tr>
                @include('accounting::reports.cash-balance-rows', ['balanceField' => 'opening'])
                <tr class="fw-semibold"><th scope="row">Total opening cash & bank</th><td class="text-end">{{ moneyDecimal((string) $balances['current']['opening']) }}</td><td class="text-end">{{ moneyDecimal((string) $balances['fiscal']['opening']) }}</td></tr>
                <tr class="table-light"><th colspan="3" scope="rowgroup">Closing cash & bank</th></tr>
                @include('accounting::reports.cash-balance-rows', ['balanceField' => 'closing'])
                <tr class="fw-semibold"><th scope="row">Total closing cash & bank</th><td class="text-end">{{ moneyDecimal((string) $balances['current']['closing']) }}</td><td class="text-end">{{ moneyDecimal((string) $balances['fiscal']['closing']) }}</td></tr>
            </tbody>
            <tfoot><tr><th scope="row">Reconciliation difference</th><td class="text-end">{{ moneyDecimal((string) $totals['current']['difference']) }}</td><td class="text-end">{{ moneyDecimal((string) $totals['fiscal']['difference']) }}</td></tr></tfoot>
        </table>
    </div>
@endsection
