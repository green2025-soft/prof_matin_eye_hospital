@extends('inventory::reports.layout')

@section('report-page-class', 'app-report-wide')

@section('report-filters')
    <form class="p-3" action="{{ route('inventory.reports.customer-dues') }}" method="GET" aria-label="Filter customer dues">
        <input name="product_type" type="hidden" value="{{ $filters['product_type'] }}">
        <input name="per_page" type="hidden" value="{{ $filters['per_page'] ?? config('pagination.default') }}">
        <div class="row g-2 align-items-end">
            <div class="col-md-7 col-lg-5">
                <label class="form-label small" for="customer_id">Customer</label>
                <select class="{{ inputClass('customer_id', 'form-select') }}" id="customer_id" name="customer_id" data-ajax-select data-url="{{ route('inventory.payments.customers', ['product_type' => $filters['product_type']]) }}" data-label-field="text" data-placeholder="Patient ID, name or mobile" {{ inputAttrs('customer_id') }}>
                    <option value=""></option>
                    @if ($selectedCustomer !== null)
                        <option value="{{ $selectedCustomer['id'] }}" selected>{{ $selectedCustomer['text'] }}</option>
                    @endif
                </select>
                @error('customer_id')<div class="invalid-feedback d-block" id="customer_id-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-5 col-lg-4 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Filter</button>
                <a class="btn btn-outline-secondary" href="{{ route('inventory.reports.customer-dues', ['product_type' => $filters['product_type']]) }}">Reset</a>
            </div>
        </div>
    </form>
@endsection

@section('report-context')
    <span>As of: <strong>{{ now()->toDateString() }}</strong></span>
    <span>Customer: {{ $selectedCustomer['text'] ?? 'All customers with dues' }}</span>
    <span>All current outstanding invoices, including earlier periods.</span>
@endsection

@section('report-summary')
    <div class="app-report-summary row row-cols-3 g-3">
        <div class="col"><span>Total dues (BDT)</span><strong>{{ moneyDecimal($summary->due) }}</strong></div>
        <div class="col"><span>Customers</span><strong>{{ $summary->party_count }}</strong></div>
        <div class="col"><span>Invoices</span><strong>{{ $summary->invoice_count }}</strong></div>
    </div>
@endsection

@section('report-table')
    <div class="table-responsive">
        <table class="table table-sm app-table app-report-table align-middle mb-0">
            <caption class="visually-hidden">Current customer outstanding balances. Amounts in BDT.</caption>
            <thead>
                <tr>
                    <th scope="col">Customer / ID</th>
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
                    @php($customer = $customers[$record->party_id] ?? null)
                    <tr>
                        <th scope="row">{{ $customer['name'] ?? 'Customer #'.$record->party_id }}<small class="d-block fw-normal">{{ $customer['patient_id'] ?? $record->party_id }}</small></th>
                        <td>{{ $customer['mobile_no'] ?? '--' }}</td>
                        <td class="text-end">{{ $record->invoice_count }}</td>
                        <td class="text-end">{{ moneyDecimal($record->invoice_total) }}</td>
                        <td class="text-end">{{ moneyDecimal($record->returned) }}</td>
                        <td class="text-end">{{ moneyDecimal($record->paid) }}</td>
                        <td class="text-end">{{ moneyDecimal($record->refunded) }}</td>
                        <td class="text-end">{{ moneyDecimal($record->settlement_discount) }}</td>
                        <td class="text-end fw-semibold">{{ moneyDecimal($record->due) }}</td>
                        @if ($canCreatePayment)
                            <td class="text-end d-print-none"><a class="btn btn-outline-primary btn-sm text-nowrap" href="{{ route('inventory.payments.create', ['product_type' => $filters['product_type'], 'direction' => 'customer', 'customer_id' => $record->party_id]) }}">Collect due</a></td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td class="text-center text-body-secondary py-4" colspan="9">No outstanding customer balances match these filters.</td>
                        @if ($canCreatePayment)<td class="d-print-none"></td>@endif
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
