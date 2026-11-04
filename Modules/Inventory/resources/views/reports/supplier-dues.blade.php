@extends('inventory::reports.layout')

@section('report-page-class', 'app-report-wide')

@section('report-filters')
    <form class="p-3" action="{{ route('inventory.reports.supplier-dues') }}" method="GET" aria-label="Filter supplier dues">
        <input name="product_type" type="hidden" value="{{ $filters['product_type'] }}">
        <input name="per_page" type="hidden" value="{{ $filters['per_page'] ?? config('pagination.default') }}">
        <div class="row g-2 align-items-end">
            <div class="col-md-7 col-lg-5">
                <label class="form-label small" for="supplier_id">Supplier</label>
                <select class="{{ inputClass('supplier_id', 'form-select') }}" id="supplier_id" name="supplier_id" data-ajax-select data-url="{{ route('inventory.payments.suppliers', ['product_type' => $filters['product_type']]) }}" data-placeholder="Search supplier" {{ inputAttrs('supplier_id') }}>
                    <option value=""></option>
                    @if ($selectedSupplier !== null)
                        <option value="{{ $selectedSupplier->id }}" selected>{{ $selectedSupplier->name }}</option>
                    @endif
                </select>
                @error('supplier_id')<div class="invalid-feedback d-block" id="supplier_id-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-5 col-lg-4 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Filter</button>
                <a class="btn btn-outline-secondary" href="{{ route('inventory.reports.supplier-dues', ['product_type' => $filters['product_type']]) }}">Reset</a>
            </div>
        </div>
    </form>
@endsection

@section('report-context')
    <span>As of: <strong>{{ now()->toDateString() }}</strong></span>
    <span>Supplier: {{ $selectedSupplier?->name ?? 'All suppliers with dues' }}</span>
    <span>All current outstanding invoices, including earlier periods.</span>
@endsection

@section('report-summary')
    <div class="app-report-summary row row-cols-3 g-3">
        <div class="col"><span>Total dues (BDT)</span><strong>{{ moneyDecimal($summary->due) }}</strong></div>
        <div class="col"><span>Suppliers</span><strong>{{ $summary->party_count }}</strong></div>
        <div class="col"><span>Invoices</span><strong>{{ $summary->invoice_count }}</strong></div>
    </div>
@endsection

@section('report-table')
    <div class="table-responsive">
        <table class="table table-sm app-table app-report-table align-middle mb-0">
            <caption class="visually-hidden">Current supplier outstanding balances. Amounts in BDT.</caption>
            <thead>
                <tr>
                    <th scope="col">Supplier / ID</th>
                    <th scope="col">Contact</th>
                    <th class="text-end" scope="col">Invoices</th>
                    <th class="text-end" scope="col">Invoice amount</th>
                    <th class="text-end" scope="col">Returns</th>
                    <th class="text-end" scope="col">Paid</th>
                    <th class="text-end" scope="col">Refunds</th>
                    <th class="text-end" scope="col">Settlement discount</th>
                    <th class="text-end" scope="col">Due</th>
                    @if ($canCreatePayment)<th class="text-end d-print-none" scope="col">Payment</th>@endif
                </tr>
            </thead>
            <tbody>
                @forelse ($records as $record)
                    @php($supplier = $suppliers->get($record->party_id))
                    <tr>
                        <th scope="row">{{ $supplier?->name ?? 'Supplier #'.$record->party_id }}<small class="d-block fw-normal">{{ $record->party_id }}</small></th>
                        <td>{{ $supplier?->contact_no ?? '--' }}</td>
                        <td class="text-end">{{ $record->invoice_count }}</td>
                        <td class="text-end">{{ moneyDecimal($record->invoice_total) }}</td>
                        <td class="text-end">{{ moneyDecimal($record->returned) }}</td>
                        <td class="text-end">{{ moneyDecimal($record->paid) }}</td>
                        <td class="text-end">{{ moneyDecimal($record->refunded) }}</td>
                        <td class="text-end">{{ moneyDecimal($record->settlement_discount) }}</td>
                        <td class="text-end fw-semibold">{{ moneyDecimal($record->due) }}</td>
                        @if ($canCreatePayment)
                            <td class="text-end d-print-none"><a class="btn btn-outline-primary btn-sm text-nowrap" href="{{ route('inventory.payments.create', ['product_type' => $filters['product_type'], 'direction' => 'supplier', 'supplier_id' => $record->party_id]) }}">Pay due</a></td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td class="text-center text-body-secondary py-4" colspan="9">No outstanding supplier balances match these filters.</td>
                        @if ($canCreatePayment)<td class="d-print-none"></td>@endif
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
