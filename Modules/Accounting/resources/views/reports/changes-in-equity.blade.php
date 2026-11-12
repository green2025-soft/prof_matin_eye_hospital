@extends('accounting::reports.layout')

@section('report-filters')
    @include('accounting::reports.month-filters')
@endsection

@section('report-context')
    <span>Month ended: <strong>{{ $periods['current']['to_date'] }}</strong></span>
    <span>Financial year begins 1 July. Opening balances precede each period.</span>
@endsection

@section('report-table')
    <div class="table-responsive">
        <table class="table table-sm app-table app-report-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Particulars</th>
                    @foreach ($periods as $period)
                        <th class="text-end">{{ $period['label'] }}<small class="d-block fw-normal">{{ $period['from_date'] }} to {{ $period['to_date'] }}</small></th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                <tr class="table-light">
                    <th>Opening equity</th>
                    @foreach ($periods as $key => $period)
                        <td class="text-end fw-semibold">{{ moneyDecimal((string) $balances[$key]['opening']) }}</td>
                    @endforeach
                </tr>
                @foreach ($records as $account)
                    <tr>
                        <th scope="row">{{ $account->code }} · {{ $account->name }}</th>
                        @foreach ($periods as $key => $period)
                            <td class="text-end">{{ moneyDecimal((string) $balances[$key]['movements']->get($account->id, 0)) }}</td>
                        @endforeach
                    </tr>
                @endforeach
                <tr>
                    <th scope="row">Change in unclosed income over expenditure</th>
                    @foreach ($periods as $key => $period)
                        <td class="text-end">{{ moneyDecimal((string) $balances[$key]['unclosed']) }}</td>
                    @endforeach
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th>Closing equity</th>
                    @foreach ($periods as $key => $period)
                        <th class="text-end">{{ moneyDecimal((string) $balances[$key]['closing']) }}</th>
                    @endforeach
                </tr>
            </tfoot>
        </table>
    </div>
@endsection
