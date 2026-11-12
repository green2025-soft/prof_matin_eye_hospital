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
        <div class="col">Current month receipts<strong class="d-block">{{ moneyDecimal((string) $totals['current']['receipts']) }}</strong></div>
        <div class="col">Current month payments<strong class="d-block">{{ moneyDecimal((string) $totals['current']['payments']) }}</strong></div>
        <div class="col">Reconciliation difference<strong class="d-block">{{ moneyDecimal((string) $totals['current']['difference']) }}</strong></div>
    </div>
@endsection

@section('report-table')
    @if ($cashAccounts->isEmpty())
        <p class="p-3 mb-0 text-body-secondary">Assign Cash in hand or Cash at bank report roles in Chart of Accounts to include your cash ledgers.</p>
    @endif
    <div class="table-responsive">
        <table class="table table-sm app-table app-report-table align-middle mb-0">
            <caption class="visually-hidden">Opening cash, receipts, payments and closing cash, compared with the financial year.</caption>
            <thead><tr><th scope="col">Particulars</th><th class="text-end" scope="col">Current month<br>{{ $periods['current']['label'] }}</th><th class="text-end" scope="col">For the period<br>{{ $periods['fiscal']['label'] }}</th></tr></thead>
            <tbody>
                <tr class="table-light"><th colspan="3" scope="rowgroup">Receipts</th></tr>
                <tr><th colspan="3" scope="rowgroup">Opening balance</th></tr>
                @include('accounting::reports.cash-balance-rows', ['balanceField' => 'opening'])
                <tr class="fw-semibold"><th scope="row">Total opening balance</th><td class="text-end">{{ moneyDecimal((string) $balances['current']['opening']) }}</td><td class="text-end">{{ moneyDecimal((string) $balances['fiscal']['opening']) }}</td></tr>
                <tr><th colspan="3" scope="rowgroup">Receipts during the period</th></tr>
                @forelse (collect($groups['receipts'])->sortBy('account.code') as $row)
                    <tr><th class="fw-normal" scope="row">{{ $row['account']->code }} - {{ $row['account']->name }}</th><td class="text-end">{{ moneyDecimal((string) $row['current']) }}</td><td class="text-end">{{ moneyDecimal((string) $row['fiscal']) }}</td></tr>
                @empty
                    <tr><td class="text-body-secondary" colspan="3">No receipts.</td></tr>
                @endforelse
                <tr class="table-light fw-semibold"><th scope="row">Total receipts</th><td class="text-end">{{ moneyDecimal((string) $totals['current']['total_receipts']) }}</td><td class="text-end">{{ moneyDecimal((string) $totals['fiscal']['total_receipts']) }}</td></tr>
                <tr class="table-light"><th colspan="3" scope="rowgroup">Payments</th></tr>
                @forelse (collect($groups['payments'])->sortBy('account.code') as $row)
                    <tr><th class="fw-normal" scope="row">{{ $row['account']->code }} - {{ $row['account']->name }}</th><td class="text-end">{{ moneyDecimal((string) $row['current']) }}</td><td class="text-end">{{ moneyDecimal((string) $row['fiscal']) }}</td></tr>
                @empty
                    <tr><td class="text-body-secondary" colspan="3">No payments.</td></tr>
                @endforelse
                <tr><th colspan="3" scope="rowgroup">Closing balance</th></tr>
                @include('accounting::reports.cash-balance-rows', ['balanceField' => 'closing'])
                <tr class="fw-semibold"><th scope="row">Total closing balance</th><td class="text-end">{{ moneyDecimal((string) $balances['current']['closing']) }}</td><td class="text-end">{{ moneyDecimal((string) $balances['fiscal']['closing']) }}</td></tr>
                <tr class="table-light fw-semibold"><th scope="row">Total payments</th><td class="text-end">{{ moneyDecimal((string) $totals['current']['total_payments']) }}</td><td class="text-end">{{ moneyDecimal((string) $totals['fiscal']['total_payments']) }}</td></tr>
            </tbody>
            <tfoot><tr><th scope="row">Reconciliation difference</th><td class="text-end">{{ moneyDecimal((string) $totals['current']['difference']) }}</td><td class="text-end">{{ moneyDecimal((string) $totals['fiscal']['difference']) }}</td></tr></tfoot>
        </table>
    </div>
@endsection
