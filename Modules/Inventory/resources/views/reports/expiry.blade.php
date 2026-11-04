@extends('inventory::reports.layout')

@section('report-filters')
    <form class="p-3" action="{{ route('inventory.reports.expiry') }}" method="GET" aria-label="Filter medicine expiry">
        <input name="product_type" type="hidden" value="medicine">
        <input name="per_page" type="hidden" value="{{ $filters['per_page'] ?? config('pagination.default') }}">
        <div class="row g-2 align-items-end">
            @include('inventory::reports.product-filter')
            <div class="col-sm-6 col-lg-3">
                <label class="form-label small" for="expiry_before">Expires on / before</label>
                <div class="input-group" data-datepicker>
                    <input class="{{ inputClass('expiry_before') }}" id="expiry_before" name="expiry_before" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="{{ getValue('expiry_before', null, $filters['expiry_before']) }}" required data-input {{ inputAttrs('expiry_before') }}>
                    <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="expiry_before" aria-label="Choose expiry limit"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
                </div>
                @error('expiry_before')<div class="invalid-feedback d-block" id="expiry_before-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-lg-4 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Filter</button>
                <a class="btn btn-outline-secondary" href="{{ route('inventory.reports.expiry') }}">Reset</a>
            </div>
        </div>
    </form>
@endsection

@section('report-context')
    @isset($filters['search'])<span>Search: {{ $filters['search'] }}</span>@endisset
    <span>Expires on / before: <strong>{{ $filters['expiry_before'] }}</strong></span>
    <span>Product: {{ $selectedProduct?->product?->name ?? 'All medicines' }} {{ $selectedProduct?->description() }}</span>
    <span>Stock as of {{ now()->toDateString() }}. Quantities in Pcs.</span>
@endsection

@section('report-table')
    <div class="table-responsive">
        <table class="table table-sm app-table app-report-table align-middle mb-0">
            <caption class="visually-hidden">Medicine lots with remaining stock and an expiry on or before the selected date.</caption>
            <thead>
                <tr>
                    <th scope="col">Medicine / SKU</th>
                    <th scope="col">Batch</th>
                    <th scope="col">Expiry</th>
                    <th class="text-end" scope="col">Quantity (Pcs)</th>
                    <th class="text-end" scope="col">Value</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($records as $record)
                    <tr>
                        <th scope="row">{{ $record->variant->product->name }}<small class="d-block fw-normal">{{ $record->variant->description() }}</small></th>
                        <td>{{ $record->batch_no ?: '--' }}</td>
                        <td>{{ $record->expiry_date->format('Y-m-d') }}</td>
                        <td class="text-end">{{ $record->remaining_quantity }}</td>
                        <td class="text-end">{{ moneyDecimal($record->remaining_cost) }}</td>
                    </tr>
                @empty
                    <tr><td class="text-center text-body-secondary py-4" colspan="5">No medicine stock expires within this period.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
