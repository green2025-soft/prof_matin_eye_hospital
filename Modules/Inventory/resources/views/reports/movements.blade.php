@extends('inventory::reports.layout')

@section('report-filters')
    <form class="p-3" action="{{ route('inventory.reports.movements') }}" method="GET" aria-label="Filter stock movements">
        <input name="product_type" type="hidden" value="{{ $filters['product_type'] }}">
        <input name="per_page" type="hidden" value="{{ $filters['per_page'] ?? config('pagination.default') }}">
        <div class="row g-2 align-items-end">
            @include('inventory::reports.product-filter')
            @include('inventory::reports.date-range')
            <div class="col-lg-4 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Filter</button>
                <a class="btn btn-outline-secondary" href="{{ route('inventory.reports.movements', ['product_type' => $filters['product_type']]) }}">Reset</a>
            </div>
        </div>
    </form>
@endsection

@section('report-context')
    @isset($filters['search'])<span>Search: {{ $filters['search'] }}</span>@endisset
    <span>Period: <strong>{{ $filters['from_date'] }}</strong> to <strong>{{ $filters['to_date'] }}</strong></span>
    <span>Product: {{ $selectedProduct?->product?->name ?? 'All products' }} {{ $selectedProduct?->description() }}</span>
    <span>Quantities in Pcs. Reversals retain their original entry number.</span>
@endsection

@section('report-summary')
    <div class="app-report-summary row row-cols-2 row-cols-md-4 g-3">
        <div class="col"><span>Opening (Pcs)</span><strong>{{ $summary->opening_quantity }}</strong></div>
        <div class="col"><span>In (Pcs)</span><strong>{{ $summary->in_quantity }}</strong></div>
        <div class="col"><span>Out (Pcs)</span><strong>{{ $summary->out_quantity }}</strong></div>
        <div class="col"><span>Closing (Pcs)</span><strong>{{ $summary->closing_quantity }}</strong></div>
    </div>
@endsection

@section('report-table')
    <div class="table-responsive">
        <table class="table table-sm app-table app-report-table align-middle mb-0">
            <caption class="visually-hidden">Stock receipts, issues, returns, adjustments and reversals. Quantities in Pcs.</caption>
            <thead>
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col">Entry</th>
                    <th scope="col">Movement</th>
                    <th scope="col">Product / SKU</th>
                    <th class="text-end" scope="col">In</th>
                    <th class="text-end" scope="col">Out</th>
                    <th class="text-end" scope="col">Value</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($records as $record)
                    <tr>
                        <td>{{ $record->movement_date->format('Y-m-d') }}</td>
                        <td>{{ $record->stockEntry?->number ?? '--' }}</td>
                        <td>
                            {{ ucfirst(str_replace('_', ' ', $record->type)) }}
                            @if ($record->is_reversal)<small class="d-block">Reversal</small>@endif
                        </td>
                        <th scope="row">{{ $record->variant->product->name }}<small class="d-block fw-normal">{{ $record->variant->description() }}</small></th>
                        <td class="text-end">{{ max(0, $record->quantity) }}</td>
                        <td class="text-end">{{ abs(min(0, $record->quantity)) }}</td>
                        <td class="text-end">{{ moneyDecimal($record->cost) }}</td>
                    </tr>
                @empty
                    <tr><td class="text-center text-body-secondary py-4" colspan="7">No stock movements match this period.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
