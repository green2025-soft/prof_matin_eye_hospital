@extends('inventory::reports.layout')

@section('report-filters')
    <form class="p-3" action="{{ route('inventory.reports.daily') }}" method="GET" aria-label="Filter daily activity">
        <input name="product_type" type="hidden" value="{{ $filters['product_type'] }}">
        <input name="per_page" type="hidden" value="{{ $filters['per_page'] ?? config('pagination.default') }}">
        <div class="row g-2 align-items-end">
            @include('inventory::reports.date-range')
            <div class="col-lg-4 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Filter</button>
                <a class="btn btn-outline-secondary" href="{{ route('inventory.reports.daily', ['product_type' => $filters['product_type']]) }}">Reset</a>
            </div>
        </div>
    </form>
@endsection

@section('report-context')
    <span>Period: <strong>{{ $filters['from_date'] }}</strong> to <strong>{{ $filters['to_date'] }}</strong></span>
    <span>Daily totals by activity. Positive cash is received; negative cash is paid.</span>
@endsection

@section('report-table')
    <div class="table-responsive">
        <table class="table table-sm app-table app-report-table align-middle mb-0">
            <caption class="visually-hidden">Daily transaction counts, values and cash movements.</caption>
            <thead>
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col">Activity</th>
                    <th class="text-end" scope="col">Entries</th>
                    <th class="text-end" scope="col">Value</th>
                    <th class="text-end" scope="col">Cash</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($records as $record)
                    <tr>
                        <td>{{ $record->activity_date }}</td>
                        <th scope="row">{{ ucfirst(str_replace('_', ' ', $record->activity)) }}</th>
                        <td class="text-end">{{ $record->entries }}</td>
                        <td class="text-end">{{ moneyDecimal($record->value) }}</td>
                        <td class="text-end">{{ moneyDecimal($record->cash) }}</td>
                    </tr>
                @empty
                    <tr><td class="text-center text-body-secondary py-4" colspan="5">No activity matches this period.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
