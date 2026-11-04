@extends('inventory::reports.layout')

@section('report-filters')
    <form class="p-3" action="{{ route('inventory.reports.purchase-discounts') }}" method="GET" aria-label="Filter purchase discounts">
        <input name="product_type" type="hidden" value="{{ $filters['product_type'] }}">
        <input name="per_page" type="hidden" value="{{ $filters['per_page'] ?? config('pagination.default') }}">
        <div class="row g-2 align-items-end">
            @include('inventory::reports.date-range')
            <div class="col-lg-4 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Filter</button>
                <a class="btn btn-outline-secondary" href="{{ route('inventory.reports.purchase-discounts', ['product_type' => $filters['product_type']]) }}">Reset</a>
            </div>
        </div>
    </form>
@endsection

@section('report-context')
    <span>Period: <strong>{{ $filters['from_date'] }}</strong> to <strong>{{ $filters['to_date'] }}</strong></span>
    <span>Item, invoice and payment settlement discounts are shown separately.</span>
@endsection

@section('report-table')
    <div class="table-responsive">
        <table class="table table-sm app-table app-report-table align-middle mb-0">
            <caption class="visually-hidden">Discounts on active purchase invoices within the selected period.</caption>
            <thead>
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col">Invoice / supplier</th>
                    <th class="text-end" scope="col">Item discounts</th>
                    <th class="text-end" scope="col">Invoice discount</th>
                    <th class="text-end" scope="col">Settlement discount</th>
                    <th class="text-end" scope="col">Invoice total</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($records as $record)
                    <tr>
                        <td>{{ $record->entry_date->format('Y-m-d') }}</td>
                        <th scope="row">{{ $record->number }}<small class="d-block fw-normal">{{ $record->supplier?->name }}</small></th>
                        <td class="text-end">{{ moneyDecimal($record->line_discount ?? 0) }}</td>
                        <td class="text-end">{{ moneyDecimal($record->discount - ($record->line_discount ?? 0)) }}</td>
                        <td class="text-end">{{ moneyDecimal($record->settlement_discount) }}</td>
                        <td class="text-end">{{ moneyDecimal($record->total) }}</td>
                    </tr>
                @empty
                    <tr><td class="text-center text-body-secondary py-4" colspan="6">No purchases match this period.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
