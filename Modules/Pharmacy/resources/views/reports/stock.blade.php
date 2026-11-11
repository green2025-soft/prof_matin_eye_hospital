@extends('pharmacy::reports.layout')

@section('report-page-class', 'app-report-wide')

@section('report-filters')
    <form class="p-3" action="{{ route('pharmacy.reports.stock') }}" method="GET" aria-label="Filter stock report">
        <input name="product_type" type="hidden" value="{{ $filters['product_type'] }}">
        <input name="per_page" type="hidden" value="{{ $filters['per_page'] ?? config('pagination.default') }}">
        <div class="row g-2 align-items-end">
            @include('pharmacy::reports.product-filter')
            @include('pharmacy::reports.date-range')
            <div class="col-sm-6 col-lg-4">
                <label class="form-label small" for="stock_status">Stock status now</label>
                <select class="{{ inputClass('stock_status', 'form-select') }}" id="stock_status" name="stock_status" {{ inputAttrs('stock_status') }}>
                    <option value="all" @selected($filters['stock_status'] === 'all')>All products</option>
                    <option value="available" @selected($filters['stock_status'] === 'available')>Available to sell</option>
                    <option value="out" @selected($filters['stock_status'] === 'out')>Out of stock</option>
                    <option value="low" @selected($filters['stock_status'] === 'low')>Low stock</option>
                    <option value="expired" @selected($filters['stock_status'] === 'expired')>Has expired stock</option>
                    <option value="future" @selected($filters['stock_status'] === 'future')>Has future-dated stock</option>
                    <option value="negative" @selected($filters['stock_status'] === 'negative')>Negative stock</option>
                </select>
                @error('stock_status')<div class="invalid-feedback d-block" id="stock_status-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-sm-6 col-lg-3">
                <label class="form-label small" for="category_id">Category / generic</label>
                <select class="{{ inputClass('category_id', 'form-select') }}" id="category_id" name="category_id"
                    data-ajax-select data-url="{{ route('pharmacy.categories.options', ['type' => $filters['product_type']]) }}"
                    data-placeholder="All categories"
                    {{ inputAttrs('category_id') }}>
                    <option value=""></option>
                    @if ($selectedCategory !== null)
                        <option value="{{ $selectedCategory->id }}" selected>{{ $selectedCategory->name }}</option>
                    @endif
                </select>
                @error('category_id')<div class="invalid-feedback d-block" id="category_id-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-sm-6 col-lg-3">
                <label class="form-label small" for="supplier_id">Supplier</label>
                <select class="{{ inputClass('supplier_id', 'form-select') }}" id="supplier_id" name="supplier_id"
                    data-ajax-select data-url="{{ route('pharmacy.suppliers.options', ['type' => $filters['product_type']]) }}"
                    data-placeholder="All suppliers"
                    {{ inputAttrs('supplier_id') }}>
                    <option value=""></option>
                    @if ($selectedSupplier !== null)
                        <option value="{{ $selectedSupplier->id }}" selected>{{ $selectedSupplier->name }}</option>
                    @endif
                </select>
                @error('supplier_id')<div class="invalid-feedback d-block" id="supplier_id-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-sm-6 col-lg-3">
                <label class="form-label small" for="shelf_id">Shelf</label>
                <select class="{{ inputClass('shelf_id', 'form-select') }}" id="shelf_id" name="shelf_id"
                    data-ajax-select data-url="{{ route('pharmacy.shelf.options', ['type' => $filters['product_type']]) }}"
                    data-placeholder="All Shelf"
                    {{ inputAttrs('shelf_id') }}>
                    <option value=""></option>
                    @if ($selectedShelf !== null)
                        <option value="{{ $selectedShelf->id }}" selected>{{ $selectedShelf->name }}</option>
                    @endif
                </select>
                @error('shelf_id')<div class="invalid-feedback d-block" id="shelf_id-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-sm-6 col-lg-3 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Filter</button>
                <a class="btn btn-outline-secondary" href="{{ route('pharmacy.reports.stock', ['product_type' => $filters['product_type']]) }}">Reset</a>
            </div>
        </div>
    </form>
@endsection

@section('report-context')
    @isset($filters['search'])<span>Search: {{ $filters['search'] }}</span>@endisset
    <span>Period: <strong>{{ $filters['from_date'] }}</strong> to <strong>{{ $filters['to_date'] }}</strong></span>
    <span>Product: {{ $selectedProduct?->product?->name ?? 'All products' }} {{ $selectedProduct?->description() }}</span>
    <span>Category: {{ $selectedCategory?->name ?? 'All' }}</span>
    <span>Supplier: {{ $selectedSupplier?->name ?? 'All' }}</span>
    <span>Shelf: {{ $selectedShelf?->name ?? 'All' }}</span>
    <span>Current stock status: {{ ucfirst($filters['stock_status']) }}</span>
    <span>Available / expired stock as of {{ now()->toDateString() }}. Quantities in Pcs.</span>
@endsection

@section('report-summary')
    <div class="app-report-summary row row-cols-2 row-cols-lg-5 g-3">
        <div class="col"><span class="d-block">Products / SKUs</span><strong class="d-block">{{ $summary->product_count }}</strong></div>
        <div class="col"><span class="d-block">Available now (Pcs)</span><strong class="d-block">{{ (int) $summary->available_quantity }}</strong></div>
        <div class="col"><span class="d-block">Expired now (Pcs)</span><strong class="d-block">{{ (int) $summary->expired_quantity }}</strong></div>
        <div class="col"><span class="d-block">Period closing (Pcs)</span><strong class="d-block">{{ (int) $summary->closing_quantity }}</strong></div>
        <div class="col"><span class="d-block">Closing value (BDT)</span><strong class="d-block">{{ moneyDecimal($summary->closing_cost) }}</strong></div>
    </div>
@endsection

@section('report-table')
    <div class="table-responsive">
        <table class="table table-sm app-table app-report-table align-middle mb-0">
            <caption class="visually-hidden">Stock movements for the selected period and stock available today. Quantities in Pcs.</caption>
            <thead>
                <tr>
                    <th scope="col">Product / SKU</th>
                    <th scope="col">Supplier / Shelf</th>
                    <th class="text-end" scope="col">Opening</th>
                    <th class="text-end" scope="col">Purchase</th>
                    <th class="text-end" scope="col">Sales</th>
                    <th class="text-end" scope="col">Purchase return</th>
                    <th class="text-end" scope="col">Sales return</th>
                    <th class="text-end" scope="col">Other +/-</th>
                    <th class="text-end" scope="col">Closing</th>
                    <th class="text-end" scope="col">Available now</th>
                    <th class="text-end" scope="col">Expired now</th>
                    <th class="text-end" scope="col">P.Price</th>
                    <th class="text-end" scope="col">S.Price</th>
                    <th class="text-end" scope="col">Closing value</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($records as $record)
                    <tr>
                        <th scope="row">
                            <a class="text-decoration-none"
                                href="{{ route('pharmacy.reports.movements', ['product_type' => $filters['product_type'], 'variant_id' => $record->id, 'from_date' => $filters['from_date'], 'to_date' => $filters['to_date']]) }}">{{ $record->product->name }}</a>
                            <small class="d-block fw-normal">{{ $record->description() }}</small>
                            <small class="d-block fw-normal text-body-secondary">{{ $record->product->category?->name }}</small>
                        </th>
                        <td class="small">{{ $record->product->supplier?->name ?? '--' }}<small class="d-block text-body-secondary">{{ $record->product->shelf?->name ?? '--' }}</small></td>
                        <td class="text-end">{{ (int) $record->opening_quantity }}</td>
                        <td class="text-end">{{ (int) $record->purchase_quantity }}</td>
                        <td class="text-end">{{ -(int) $record->sale_quantity }}</td>
                        <td class="text-end">{{ -(int) $record->purchase_return_quantity }}</td>
                        <td class="text-end">{{ (int) $record->sale_return_quantity }}</td>
                        <td class="text-end">{{ (int) $record->other_quantity }}</td>
                        <td class="text-end fw-semibold">{{ (int) $record->closing_quantity }}</td>
                        <td class="text-end fw-semibold">{{ (int) $record->available_quantity }}</td>
                        <td class="text-end">{{ (int) $record->expired_quantity }}</td>
                        <td class="text-end">{{ $record->product->purchase_price }}</td>
                        <td class="text-end">{{ $record->product->sale_price }}</td>
                        <td class="text-end">{{ moneyDecimal($record->closing_cost ?? 0) }}</td>
                    </tr>
                @empty
                    <tr><td class="text-center text-body-secondary py-4" colspan="14">No products match these filters.</td></tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th scope="row" colspan="2">Grand total - all filtered products</th>
                    <td class="text-end">{{ (int) $summary->opening_quantity }}</td>
                    <td class="text-end">{{ (int) $summary->purchase_quantity }}</td>
                    <td class="text-end">{{ -(int) $summary->sale_quantity }}</td>
                    <td class="text-end">{{ -(int) $summary->purchase_return_quantity }}</td>
                    <td class="text-end">{{ (int) $summary->sale_return_quantity }}</td>
                    <td class="text-end">{{ (int) $summary->other_quantity }}</td>
                    <td class="text-end">{{ (int) $summary->closing_quantity }}</td>
                    <td class="text-end">{{ (int) $summary->available_quantity }}</td>
                    <td class="text-end">{{ (int) $summary->expired_quantity }}</td>
                    <td></td><td></td>
                    <td class="text-end">{{ moneyDecimal($summary->closing_cost) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection
