@extends('inventory::reports.layout')

@section('report-page-class', 'app-report-wide')

@section('report-filters')
    <form class="p-3" action="{{ route('inventory.reports.profit-loss') }}" method="GET" aria-label="Filter product profit and loss">
        <input name="product_type" type="hidden" value="{{ $filters['product_type'] }}">
        <input name="per_page" type="hidden" value="{{ $filters['per_page'] ?? config('pagination.default') }}">
        <div class="row g-2 align-items-end">
            @include('inventory::reports.product-filter')
            @include('inventory::reports.date-range')
            <div class="col-lg-4 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Filter</button>
                <a class="btn btn-outline-secondary" href="{{ route('inventory.reports.profit-loss', ['product_type' => $filters['product_type']]) }}">Reset</a>
            </div>
        </div>
    </form>
@endsection

@section('report-context')
    <span>Period: <strong>{{ $filters['from_date'] }}</strong> to <strong>{{ $filters['to_date'] }}</strong></span>
    <span>Product: {{ $selectedProduct?->product?->name ?? 'All products' }} {{ $selectedProduct?->description() }}</span>
    <span>Quantities in Pcs. Sales exclude VAT and include saved discounts.</span>
@endsection

@section('report-summary')
    <div class="app-report-summary row row-cols-2 row-cols-lg-4 g-3">
        <div class="col"><span>Products / SKUs</span><strong>{{ $summary->product_count }}</strong></div>
        <div class="col"><span>Net sales (BDT)</span><strong>{{ moneyDecimal($summary->net_revenue) }}</strong></div>
        <div class="col"><span>Cost (BDT)</span><strong>{{ moneyDecimal($summary->cost) }}</strong></div>
        <div class="col"><span>Product profit / loss (BDT)</span><strong>{{ moneyDecimal($summary->profit) }}</strong></div>
    </div>
@endsection

@section('report-table')
    <div class="table-responsive">
        <table class="table table-sm app-table app-report-table align-middle mb-0">
            <caption class="visually-hidden">Product sales, returns, saved costs and profit for the selected period. Amounts in BDT.</caption>
            <thead>
                <tr>
                    <th scope="col">Product / SKU</th>
                    <th class="text-end" scope="col">Sales qty</th>
                    <th class="text-end" scope="col">Sales amount</th>
                    <th class="text-end" scope="col">Return qty</th>
                    <th class="text-end" scope="col">Return amount</th>
                    <th class="text-end" scope="col">Net sales</th>
                    <th class="text-end" scope="col">Cost</th>
                    <th class="text-end" scope="col">Gross profit</th>
                    <th class="text-end" scope="col">Other +/-</th>
                    <th class="text-end" scope="col">Profit / loss</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($records as $record)
                    <tr>
                        <th scope="row">
                            <a class="text-decoration-none" href="{{ route('inventory.reports.movements', ['product_type' => $filters['product_type'], 'variant_id' => $record->id, 'from_date' => $filters['from_date'], 'to_date' => $filters['to_date']]) }}">{{ $record->product->name }}</a>
                            <small class="d-block fw-normal text-body-secondary">{{ $record->description() }}</small>
                        </th>
                        <td class="text-end">{{ $record->sale_quantity }}</td>
                        <td class="text-end">{{ moneyDecimal($record->sale_amount) }}</td>
                        <td class="text-end">{{ $record->return_quantity }}</td>
                        <td class="text-end">{{ moneyDecimal($record->return_amount) }}</td>
                        <td class="text-end">{{ moneyDecimal($record->net_revenue) }}</td>
                        <td class="text-end">{{ moneyDecimal($record->cost) }}</td>
                        <td class="text-end">{{ moneyDecimal($record->gross_profit) }}</td>
                        <td class="text-end">{{ moneyDecimal($record->other) }}</td>
                        <td class="text-end fw-semibold">{{ moneyDecimal($record->profit) }}</td>
                    </tr>
                @empty
                    <tr><td class="text-center text-body-secondary py-4" colspan="10">No product activity matches this period.</td></tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th scope="row">Grand total - all filtered products</th>
                    <td class="text-end">{{ $summary->sale_quantity }}</td>
                    <td class="text-end">{{ moneyDecimal($summary->sale_amount) }}</td>
                    <td class="text-end">{{ $summary->return_quantity }}</td>
                    <td class="text-end">{{ moneyDecimal($summary->return_amount) }}</td>
                    <td class="text-end">{{ moneyDecimal($summary->net_revenue) }}</td>
                    <td class="text-end">{{ moneyDecimal($summary->cost) }}</td>
                    <td class="text-end">{{ moneyDecimal($summary->gross_profit) }}</td>
                    <td class="text-end">{{ moneyDecimal($summary->other) }}</td>
                    <td class="text-end">{{ moneyDecimal($summary->profit) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
    <p class="small px-3 py-2 mb-0">Other +/-: stock adjustments + cost variance - damage / wastage. Product profit excludes payment settlement discounts.</p>
    @if ($showInvoiceAdjustments)
        <div class="app-report-summary row row-cols-1 row-cols-md-3 g-3 border-top">
            <div class="col"><span>Customer settlement discounts (-)</span><strong>{{ moneyDecimal($summary->customer_discount) }}</strong></div>
            <div class="col"><span>Supplier settlement discounts (+)</span><strong>{{ moneyDecimal($summary->supplier_discount) }}</strong></div>
            <div class="col"><span>Net profit / loss (BDT)</span><strong>{{ moneyDecimal($summary->net_profit) }}</strong></div>
        </div>
    @endif
@endsection
