@extends('core::layouts.app')

@section('title', ucfirst($filters['product_type']).' Supplier Dues')

@section('content')
    <div class="d-flex flex-wrap justify-content-between gap-3 mb-3">
        @include('core::partials.heading', ['title' => ucfirst($filters['product_type']).' Supplier Dues'])
        <button class="btn btn-outline-primary align-self-start d-print-none" type="button" data-inventory-print>
            <i class="fa-solid fa-print me-2" aria-hidden="true"></i>Print
        </button>
    </div>

    <section class="app-card overflow-hidden">
        <form class="p-3 border-bottom d-print-none" action="{{ route('inventory.reports.supplier-dues') }}" method="GET" aria-label="Filter supplier dues">
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
                    @error('supplier_id')
                        <div class="invalid-feedback d-block" id="supplier_id-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-5 col-lg-4 d-flex gap-2">
                    <button class="btn btn-primary" type="submit">Filter</button>
                    <a class="btn btn-outline-secondary" href="{{ route('inventory.reports.supplier-dues', ['product_type' => $filters['product_type'], 'per_page' => $filters['per_page'] ?? config('pagination.default')]) }}">Reset</a>
                </div>
            </div>
        </form>

        <div class="row g-3 p-3 border-bottom">
            <div class="col-12 col-sm-6 col-lg-4">
                <span class="small text-body-secondary">Total dues (BDT)</span>
                <strong class="d-block fs-5">{{ \Modules\Core\Helpers\Money::decimal($summary->due_minor) }}</strong>
            </div>
            <div class="col-6 col-lg-4">
                <span class="small text-body-secondary">Suppliers</span>
                <strong class="d-block fs-5">{{ $summary->party_count }}</strong>
            </div>
            <div class="col-6 col-lg-4">
                <span class="small text-body-secondary">Invoices</span>
                <strong class="d-block fs-5">{{ $summary->invoice_count }}</strong>
            </div>
        </div>

        @if ($records->isEmpty())
            @include('core::partials.empty', ['title' => 'No supplier dues', 'description' => 'No outstanding supplier balances match your filter.', 'icon' => 'truck'])
        @else
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
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
                            @if ($canCreatePayment ?? false)
                                <th class="text-end d-print-none" scope="col">Payment</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $record)
                            @php($supplier = $suppliers->get($record->party_id))
                            <tr>
                                <th scope="row">
                                    <span class="fw-semibold">{{ $supplier?->name ?? 'Supplier #'.$record->party_id }}</span>
                                    <small class="d-block fw-normal text-body-secondary">{{ $record->party_id }}</small>
                                </th>
                                <td>{{ $supplier?->contact_no ?? '—' }}</td>
                                <td class="text-end">{{ $record->invoice_count }}</td>
                                <td class="text-end text-nowrap">{{ \Modules\Core\Helpers\Money::decimal($record->invoice_total_minor) }}</td>
                                <td class="text-end text-nowrap">{{ \Modules\Core\Helpers\Money::decimal($record->returned_minor) }}</td>
                                <td class="text-end text-nowrap">{{ \Modules\Core\Helpers\Money::decimal($record->paid_minor) }}</td>
                                <td class="text-end text-nowrap">{{ \Modules\Core\Helpers\Money::decimal($record->refunded_minor) }}</td>
                                <td class="text-end text-nowrap">{{ \Modules\Core\Helpers\Money::decimal($record->settlement_discount_minor) }}</td>
                                <td class="text-end text-nowrap fw-semibold">{{ \Modules\Core\Helpers\Money::decimal($record->due_minor) }}</td>
                                @if ($canCreatePayment ?? false)
                                    <td class="text-end d-print-none">
                                        <a class="btn btn-outline-primary btn-sm text-nowrap" href="{{ route('inventory.payments.create', ['product_type' => $filters['product_type'], 'direction' => 'supplier', 'supplier_id' => $record->party_id]) }}">Pay due</a>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="d-print-none">
            @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters])
        </div>
    </section>
@endsection
