@extends('accounting::reports.layout')

@section('report-extra-filters')
    <input type="hidden" name="per_page" value="{{ $filters['per_page'] ?? config('pagination.default') }}">
@endsection

@section('report-filters')
    @include('accounting::reports.filters')
@endsection

@section('report-context')
    <span>Period: <strong>{{ $filters['from_date'] }}</strong> to <strong>{{ $filters['to_date'] }}</strong></span>
    <span>Cash and bank movements, including transfers between these accounts.</span>
@endsection

@section('report-summary')
    <div class="row row-cols-2 row-cols-lg-4 g-3 p-3 border-bottom small">
        <div class="col">Opening cash<strong class="d-block">{{ moneyDecimal((string) $opening['cash']) }}</strong></div>
        <div class="col">Opening bank<strong class="d-block">{{ moneyDecimal((string) $opening['bank']) }}</strong></div>
        <div class="col">Closing cash<strong class="d-block">{{ moneyDecimal((string) $closing['cash']) }}</strong></div>
        <div class="col">Closing bank<strong class="d-block">{{ moneyDecimal((string) $closing['bank']) }}</strong></div>
    </div>
@endsection

@section('report-table')
    @php
        $running = $pageOpening;
    @endphp
    <div class="table-responsive">
        <table class="table table-sm app-table app-report-table align-middle mb-0">
            <thead>
                <tr><th>Date</th><th>Account</th><th>Particulars</th><th>Voucher</th><th class="text-end">Debit</th><th class="text-end">Credit</th><th class="text-end">Cash</th><th class="text-end">Bank</th></tr>
            </thead>
            <tbody>
                <tr class="table-light">
                    <th colspan="6">{{ $records->currentPage() > 1 ? 'Brought forward from previous page' : 'Opening balance' }}</th>
                    <td class="text-end fw-semibold">{{ moneyDecimal((string) $running['cash']) }}</td>
                    <td class="text-end fw-semibold">{{ moneyDecimal((string) $running['bank']) }}</td>
                </tr>
                @forelse ($records as $line)
                    @php
                        $entry = $line->journalEntry;
                        $account = $cashAccounts->get($line->account_head_id);
                        $running[$account->cash_kind] = $running[$account->cash_kind]->plus($line->debit)->minus($line->credit);
                    @endphp
                    <tr>
                        <td class="text-nowrap">{{ $entry->entry_date->format('d-m-Y') }}</td>
                        <th scope="row">{{ $account->code }}<small class="d-block fw-normal">{{ $account->name }}</small></th>
                        <td>{{ $line->remarks ?: $entry->narration }}<small class="d-block text-body-secondary">{{ $entry->reference }}</small></td>
                        <td class="text-nowrap">{{ $entry->number }}</td>
                        <td class="text-end text-nowrap">{{ moneyDecimal($line->debit) }}</td>
                        <td class="text-end text-nowrap">{{ moneyDecimal($line->credit) }}</td>
                        <td class="text-end text-nowrap">{{ moneyDecimal((string) $running['cash']) }}</td>
                        <td class="text-end text-nowrap">{{ moneyDecimal((string) $running['bank']) }}</td>
                    </tr>
                @empty
                    <tr><td class="text-center text-body-secondary py-4" colspan="8">No cash or bank transactions in this period.</td></tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4">Period totals / closing balance</th>
                    <th class="text-end text-nowrap">{{ moneyDecimal((string) $periodDebit) }}</th>
                    <th class="text-end text-nowrap">{{ moneyDecimal((string) $periodCredit) }}</th>
                    <th class="text-end text-nowrap">{{ moneyDecimal((string) $closing['cash']) }}</th>
                    <th class="text-end text-nowrap">{{ moneyDecimal((string) $closing['bank']) }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection

@section('report-pagination')
    @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters])
@endsection
