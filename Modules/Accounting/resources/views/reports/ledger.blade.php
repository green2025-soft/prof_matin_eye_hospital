@extends('accounting::reports.layout')

@section('report-account-filter')
    <div class="col-lg-4">
        <label class="form-label small" for="account_head_id">Ledger account</label>
        <select class="{{ inputClass('account_head_id', 'form-select') }}" id="account_head_id" name="account_head_id"
            data-ajax-select data-url="{{ route('accounting.ledgers.options') }}" data-placeholder="Select ledger account">
            <option value=""></option>
            @if ($selectedAccount !== null)
                <option value="{{ $selectedAccount->id }}" selected>{{ $selectedAccount->code }} · {{ $selectedAccount->name }}</option>
            @endif
        </select>
        @error('account_head_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>
@endsection

@section('report-extra-filters')
    <input type="hidden" name="per_page" value="{{ $filters['per_page'] ?? config('pagination.default') }}">
@endsection

@section('report-filters')
    @include('accounting::reports.filters')
@endsection

@section('report-context')
    <span>Period: <strong>{{ $filters['from_date'] }}</strong> to <strong>{{ $filters['to_date'] }}</strong></span>
    <span>Account: {{ $selectedAccount?->code }} {{ $selectedAccount?->name ?? 'Select an account' }}</span>
@endsection

@section('report-summary')
    @if ($selectedAccount !== null)
        <div class="row row-cols-2 row-cols-lg-4 g-3 p-3 border-bottom small">
            <div class="col">Opening balance<strong class="d-block">{{ moneyDecimal((string) $opening->abs()) }} {{ $opening->isNegative() ? 'Cr' : 'Dr' }}</strong></div>
            <div class="col">Period debit<strong class="d-block">{{ moneyDecimal((string) $periodDebit) }}</strong></div>
            <div class="col">Period credit<strong class="d-block">{{ moneyDecimal((string) $periodCredit) }}</strong></div>
            <div class="col">Closing balance<strong class="d-block">{{ moneyDecimal((string) $closing->abs()) }} {{ $closing->isNegative() ? 'Cr' : 'Dr' }}</strong></div>
        </div>
    @endif
@endsection

@section('report-table')
    @if ($records === null)
        <p class="text-center text-body-secondary p-4 mb-0">Select a ledger account to view its transactions.</p>
    @else
        @php($running = $pageOpening)
        <div class="table-responsive">
            <table class="table table-sm app-table app-report-table align-middle mb-0">
                <thead><tr><th>Date</th><th>Voucher</th><th>Reference / narration</th><th class="text-end">Debit</th><th class="text-end">Credit</th><th class="text-end">Balance</th></tr></thead>
                <tbody>
                    <tr class="table-light"><th colspan="5">{{ $records->currentPage() > 1 ? 'Brought forward from previous page' : 'Opening balance' }}</th><td class="text-end fw-semibold">{{ moneyDecimal((string) $running->abs()) }} {{ $running->isNegative() ? 'Cr' : 'Dr' }}</td></tr>
                    @forelse ($records as $line)
                        @php($entry = $line->journalEntry)
                        @php($running = $running->plus($line->debit)->minus($line->credit))
                        <tr>
                            <td class="text-nowrap">{{ $entry->entry_date->format('d-m-Y') }}</td>
                            <td>
                                @can('view', $entry)
                                    <a href="{{ route('accounting.journal-entries.show', $entry) }}">{{ $entry->number }}</a>
                                @else
                                    {{ $entry->number }}
                                @endcan
                                <small class="d-block text-body-secondary">{{ $entry->voucherType->name }}</small>
                            </td>
                            <td>{{ $entry->reference }}<small class="d-block text-body-secondary">{{ $line->remarks ?: $entry->narration }}</small></td>
                            <td class="text-end text-nowrap">{{ moneyDecimal($line->debit) }}</td><td class="text-end text-nowrap">{{ moneyDecimal($line->credit) }}</td>
                            <td class="text-end text-nowrap">{{ moneyDecimal((string) $running->abs()) }} {{ $running->isNegative() ? 'Cr' : 'Dr' }}</td>
                        </tr>
                    @empty
                        <tr><td class="text-center text-body-secondary py-4" colspan="6">No transactions in this period.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
@endsection

@section('report-pagination')
    @if ($records !== null)
        @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters])
    @endif
@endsection
