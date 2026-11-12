@extends('accounting::reports.layout')

@section('report-filters')
    @include('accounting::reports.month-filters')
@endsection

@section('report-context')
    <span>Period: <strong>{{ $period['from_date'] }}</strong> to <strong>{{ $period['to_date'] }}</strong></span>
    <span>Closing balances as at {{ $period['to_date'] }}</span>
@endsection

@section('report-summary')
    <div class="row row-cols-2 row-cols-lg-4 g-3 p-3 border-bottom small">
        <div class="col">Period debit<strong class="d-block">{{ moneyDecimal((string) $periodDebit) }}</strong></div>
        <div class="col">Period credit<strong class="d-block">{{ moneyDecimal((string) $periodCredit) }}</strong></div>
        <div class="col">Closing debit<strong class="d-block">{{ moneyDecimal((string) $totalDebit) }}</strong></div>
        <div class="col">Closing credit<strong class="d-block">{{ moneyDecimal((string) $totalCredit) }}</strong></div>
    </div>
@endsection

@section('report-table')
    <div class="table-responsive">
        <table class="table table-sm app-table app-report-table align-middle mb-0">
            <thead><tr><th>Code</th><th>Account</th><th class="text-end">Opening Dr</th><th class="text-end">Opening Cr</th><th class="text-end">Period Dr</th><th class="text-end">Period Cr</th><th class="text-end">Closing Dr</th><th class="text-end">Closing Cr</th></tr></thead>
            <tbody>
                @forelse ($records as $account)
                    <tr>
                        <td>{{ $account->code }}</td><th scope="row">{{ $account->name }}</th>
                        <td class="text-end">{{ moneyDecimal($account->opening_debit) }}</td><td class="text-end">{{ moneyDecimal($account->opening_credit) }}</td>
                        <td class="text-end">{{ moneyDecimal($account->period_debit ?? 0) }}</td><td class="text-end">{{ moneyDecimal($account->period_credit ?? 0) }}</td>
                        <td class="text-end">{{ moneyDecimal($account->closing_debit) }}</td><td class="text-end">{{ moneyDecimal($account->closing_credit) }}</td>
                    </tr>
                @empty
                    <tr><td class="text-center text-body-secondary py-4" colspan="8">No ledger activity through this date.</td></tr>
                @endforelse
            </tbody>
            <tfoot><tr><th colspan="2">Total</th><th class="text-end">{{ moneyDecimal((string) $openingDebit) }}</th><th class="text-end">{{ moneyDecimal((string) $openingCredit) }}</th><th class="text-end">{{ moneyDecimal((string) $periodDebit) }}</th><th class="text-end">{{ moneyDecimal((string) $periodCredit) }}</th><th class="text-end">{{ moneyDecimal((string) $totalDebit) }}</th><th class="text-end">{{ moneyDecimal((string) $totalCredit) }}</th></tr></tfoot>
        </table>
    </div>
@endsection
