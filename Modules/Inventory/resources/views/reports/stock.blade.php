@extends('core::layouts.app')

@section('title', ucfirst($filters['product_type']).' Stock Report')

@section('content')
    @php($medicine = $filters['product_type'] === 'medicine')

    <div class="d-flex flex-wrap justify-content-between gap-3 mb-3">
        @include('core::partials.heading', ['title' => ucfirst($filters['product_type']).' Stock Report'])
        <button class="btn btn-outline-primary align-self-start d-print-none" type="button" data-inventory-print>
            <i class="fa-solid fa-print me-2" aria-hidden="true"></i>Print
        </button>
    </div>

    <nav class="d-flex flex-wrap gap-2 mb-3 d-print-none" aria-label="Inventory reports">
        <a class="btn btn-sm btn-primary" href="{{ route('inventory.reports.index', ['report' => 'stock', 'product_type' => $filters['product_type']]) }}" aria-current="page">Stock</a>
        <a class="btn btn-sm btn-outline-secondary" href="{{ route('inventory.reports.index', ['report' => 'movements', 'product_type' => $filters['product_type']]) }}">Movements</a>
        @if ($medicine)
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('inventory.reports.index', ['report' => 'expiry', 'product_type' => 'medicine']) }}">Expiry</a>
        @endif
        <a class="btn btn-sm btn-outline-secondary" href="{{ route('inventory.reports.index', ['report' => 'profit-loss', 'product_type' => $filters['product_type']]) }}">Profit / loss</a>
        <a class="btn btn-sm btn-outline-secondary" href="{{ route('inventory.reports.index', ['report' => 'purchase-discounts', 'product_type' => $filters['product_type']]) }}">Purchase discounts</a>
        <a class="btn btn-sm btn-outline-secondary" href="{{ route('inventory.reports.index', ['report' => 'daily', 'product_type' => $filters['product_type']]) }}">Daily</a>
    </nav>

    <section class="app-card overflow-hidden" id="inventory-stock-report" aria-label="Stock report">
        <form class="p-3 border-bottom d-print-none" action="{{ route('inventory.reports.index', ['report' => 'stock']) }}" method="GET" aria-label="Filter stock report">
            <input name="product_type" type="hidden" value="{{ $filters['product_type'] }}">
            <input name="per_page" type="hidden" value="{{ $filters['per_page'] ?? config('pagination.default') }}">
            <div class="row g-2 align-items-end">
                <div class="col-md-6 col-xl-4">
                    <label class="form-label small" for="stock_product">Product / SKU</label>
                    <select class="{{ inputClass('variant_id', 'form-select') }}" id="stock_product" name="variant_id" data-ajax-select data-url="{{ route('inventory.reports.products', ['product_type' => $filters['product_type']]) }}" data-placeholder="Select product or SKU" {{ inputAttrs('variant_id', 'stock_product') }}>
                        <option value=""></option>
                        @if ($selectedProduct)
                            <option value="{{ $selectedProduct->id }}" selected>{{ $selectedProduct->product->name }} / {{ $selectedProduct->sku }}</option>
                        @endif
                    </select>
                    @error('variant_id')<div class="invalid-feedback d-block" id="stock_product-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-md-3 col-xl-2">
                    <label class="form-label small" for="stock_from">From date</label>
                    <div class="input-group" data-datepicker>
                        <input class="{{ inputClass('from_date') }}" id="stock_from" name="from_date" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="{{ getValue('from_date', null, $filters['from_date']) }}" required data-input {{ inputAttrs('from_date', 'stock_from') }}>
                        <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="stock_from" aria-label="Choose start date" title="Choose start date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
                    </div>
                    @error('from_date')<div class="invalid-feedback d-block" id="stock_from-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-md-3 col-xl-2">
                    <label class="form-label small" for="stock_to">To date</label>
                    <div class="input-group" data-datepicker>
                        <input class="{{ inputClass('to_date') }}" id="stock_to" name="to_date" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="{{ getValue('to_date', null, $filters['to_date']) }}" required data-input {{ inputAttrs('to_date', 'stock_to') }}>
                        <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="stock_to" aria-label="Choose end date" title="Choose end date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
                    </div>
                    @error('to_date')<div class="invalid-feedback d-block" id="stock_to-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-xl-4">
                    <label class="form-label small" for="stock_status">Stock status now</label>
                    <select class="{{ inputClass('stock_status', 'form-select') }}" id="stock_status" name="stock_status" {{ inputAttrs('stock_status') }}>
                        <option value="all" @selected(($filters['stock_status'] ?? 'all') === 'all')>All products</option>
                        <option value="available" @selected(($filters['stock_status'] ?? 'all') === 'available')>Available to sell</option>
                        <option value="out" @selected(($filters['stock_status'] ?? 'all') === 'out')>Out of stock</option>
                        <option value="low" @selected(($filters['stock_status'] ?? 'all') === 'low')>Low stock</option>
                        @if ($medicine)
                            <option value="expired" @selected(($filters['stock_status'] ?? 'all') === 'expired')>Has expired stock</option>
                        @endif
                        <option value="future" @selected(($filters['stock_status'] ?? 'all') === 'future')>Has future-dated stock</option>
                        <option value="negative" @selected(($filters['stock_status'] ?? 'all') === 'negative')>Negative stock</option>
                    </select>
                    @error('stock_status')<div class="invalid-feedback d-block" id="stock_status-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label small" for="category_id">{{ $medicine ? 'Category / generic' : 'Category' }}</label>
                    <select class="{{ inputClass('category_id', 'form-select') }}" id="category_id" name="category_id" data-ajax-select data-url="{{ route('inventory.categories.options', ['type' => $filters['product_type']]) }}" data-placeholder="All categories" {{ inputAttrs('category_id') }}>
                        <option value=""></option>
                        @if ($selectedCategory !== null)
                            <option value="{{ $selectedCategory->id }}" selected>{{ $selectedCategory->name }}</option>
                        @endif
                    </select>
                    @error('category_id')<div class="invalid-feedback d-block" id="category_id-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label small" for="supplier_id">Supplier</label>
                    <select class="{{ inputClass('supplier_id', 'form-select') }}" id="supplier_id" name="supplier_id" data-ajax-select data-url="{{ route('inventory.suppliers.options', ['type' => $filters['product_type']]) }}" data-placeholder="All suppliers" {{ inputAttrs('supplier_id') }}>
                        <option value=""></option>
                        @if ($selectedSupplier !== null)
                            <option value="{{ $selectedSupplier->id }}" selected>{{ $selectedSupplier->name }}</option>
                        @endif
                    </select>
                    @error('supplier_id')<div class="invalid-feedback d-block" id="supplier_id-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label small" for="shelf_id">Shelf</label>
                    <select class="{{ inputClass('shelf_id', 'form-select') }}" id="shelf_id" name="shelf_id" data-ajax-select data-url="{{ route('inventory.shelves.options', ['type' => $filters['product_type']]) }}" data-placeholder="All shelves" {{ inputAttrs('shelf_id') }}>
                        <option value=""></option>
                        @if ($selectedShelf !== null)
                            <option value="{{ $selectedShelf->id }}" selected>{{ $selectedShelf->name }}</option>
                        @endif
                    </select>
                    @error('shelf_id')<div class="invalid-feedback d-block" id="shelf_id-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-xl-3 d-flex gap-2">
                    <button class="btn btn-primary" type="submit">Filter</button>
                    <a class="btn btn-outline-secondary" href="{{ route('inventory.reports.index', ['report' => 'stock', 'product_type' => $filters['product_type'], 'per_page' => $filters['per_page'] ?? config('pagination.default')]) }}">Reset</a>
                </div>
            </div>
        </form>

        <div class="row g-3 row-cols-2 {{ $medicine ? 'row-cols-lg-5' : 'row-cols-lg-4' }} p-3 border-bottom">
            <div class="col">
                <span class="small text-body-secondary">Products / SKUs</span>
                <strong class="d-block fs-5">{{ $summary->product_count }}</strong>
            </div>
            <div class="col">
                <span class="small text-body-secondary">Available now (Pcs)</span>
                <strong class="d-block fs-5 text-success-emphasis">{{ $summary->available_quantity }}</strong>
            </div>
            @if ($medicine)
                <div class="col">
                    <span class="small text-body-secondary">Expired now (Pcs)</span>
                    <strong class="d-block fs-5 {{ $summary->expired_quantity > 0 ? 'text-danger-emphasis' : '' }}">{{ $summary->expired_quantity }}</strong>
                </div>
            @endif
            <div class="col">
                <span class="small text-body-secondary">Period closing (Pcs)</span>
                <strong class="d-block fs-5">{{ $summary->closing_quantity }}</strong>
            </div>
            <div class="col">
                <span class="small text-body-secondary">Closing value (BDT)</span>
                <strong class="d-block fs-5">{{ \Modules\Core\Helpers\Money::decimal($summary->closing_cost_minor) }}</strong>
            </div>
        </div>

        <p class="small text-body-secondary px-3 py-2 mb-0 border-bottom">
            Period: <strong>{{ $filters['from_date'] }}</strong> to <strong>{{ $filters['to_date'] }}</strong>.
            Available{{ $medicine ? ' / expired' : '' }}: today, {{ now()->toDateString() }}. Quantities in Pcs; amounts in BDT.
        </p>

        @if ($records->isEmpty())
            @include('core::partials.empty', ['title' => 'No products found', 'description' => 'Change the stock status or other filters.', 'icon' => 'boxes-stacked'])
        @else
            <div class="table-responsive" role="region" aria-label="Stock quantities and values" tabindex="0">
                <table class="table table-sm app-table align-middle mb-0">
                    <caption class="visually-hidden">Stock movement totals for the selected period, with stock available to sell today. Quantities in Pcs and amounts in BDT.</caption>
                    <thead>
                        <tr>
                            <th scope="col">Product / SKU</th>
                            <th scope="col">Supplier / Shelf</th>
                            <th class="text-end" scope="col">Opening</th>
                            <th class="text-end" scope="col">Purchase</th>
                            <th class="text-end" scope="col">Sales</th>
                            <th class="text-end" scope="col">Purchase return</th>
                            <th class="text-end" scope="col">Sales return</th>
                            <th class="text-end" scope="col">Other +/−</th>
                            <th class="text-end" scope="col">Closing</th>
                            <th class="text-end text-success-emphasis" scope="col">Available now</th>
                            @if ($medicine)
                                <th class="text-end text-danger-emphasis" scope="col">Expired now</th>
                            @endif
                            <th class="text-end" scope="col" title="Default purchase price per Pcs">P.Price</th>
                            <th class="text-end" scope="col" title="Default sale price per Pcs">S.Price</th>
                            <th class="text-end" scope="col" title="Recorded movement cost through the To date">Closing value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $record)
                            <tr>
                                <th scope="row">
                                    <a class="fw-semibold text-decoration-none" href="{{ route('inventory.reports.index', ['report' => 'movements', 'product_type' => $filters['product_type'], 'variant_id' => $record->id, 'from_date' => $filters['from_date'], 'to_date' => $filters['to_date']]) }}">{{ $record->name }}</a>
                                    <small class="d-block fw-normal text-body-secondary">{{ $record->sku }}</small>
                                    @if ($record->strength)
                                        <small class="d-block fw-normal text-body-secondary">{{ $record->strength }}</small>
                                    @endif
                                    @if ($record->variant_name)
                                        <small class="d-block fw-normal text-body-secondary">{{ $record->variant_name }}</small>
                                    @endif
                                    @if (! $medicine && ($record->color || $record->size || filled($record->sph) || filled($record->cyl)))
                                        <small class="d-block fw-normal text-body-secondary">
                                            @if ($record->color){{ $record->color }}@endif
                                            @if ($record->size) Size {{ $record->size }}@endif
                                            @if (filled($record->sph)) SPH {{ $record->sph }}@endif
                                            @if (filled($record->cyl)) CYL {{ $record->cyl }}@endif
                                        </small>
                                    @endif
                                    @if ($record->category_name)
                                        <small class="d-block fw-normal text-body-secondary">{{ $record->category_name }}</small>
                                    @endif
                                </th>
                                <td class="small">
                                    {{ $record->supplier_name ?: '—' }}
                                    <span class="d-block text-body-secondary">{{ $record->shelf_name ?: '—' }}</span>
                                </td>
                                <td class="text-end text-nowrap">{{ $record->opening_quantity }}</td>
                                <td class="text-end text-nowrap">{{ $record->purchase_quantity }}</td>
                                <td class="text-end text-nowrap">{{ $record->sale_quantity }}</td>
                                <td class="text-end text-nowrap">{{ $record->purchase_return_quantity }}</td>
                                <td class="text-end text-nowrap">{{ $record->sale_return_quantity }}</td>
                                <td class="text-end text-nowrap">{{ $record->other_quantity > 0 ? '+' : '' }}{{ $record->other_quantity }}</td>
                                <td class="text-end text-nowrap fw-semibold">{{ $record->closing_quantity }}</td>
                                <td class="text-end">
                                    <strong class="text-nowrap {{ $record->available_quantity > 0 ? 'text-success-emphasis' : '' }}">{{ $record->available_quantity }}</strong>
                                    @if (! $record->sale_enabled)
                                        <span class="badge text-bg-secondary d-block mt-1">Inactive</span>
                                    @elseif ($record->quantity < 0)
                                        <span class="badge text-bg-danger d-block mt-1">Negative stock</span>
                                    @elseif ($record->available_quantity <= 0)
                                        <span class="badge text-bg-secondary d-block mt-1">Out of stock</span>
                                    @elseif ($record->available_quantity <= $record->reorder_level)
                                        <span class="badge text-bg-warning d-block mt-1">Low stock</span>
                                    @endif
                                    @if ($record->quantity != $record->closing_quantity)
                                        <small class="d-block text-body-secondary">Current stock: {{ $record->quantity }}</small>
                                    @endif
                                    @if ($record->future_quantity > 0)
                                        <small class="d-block text-body-secondary">Future-dated: {{ $record->future_quantity }}</small>
                                    @endif
                                </td>
                                @if ($medicine)
                                    <td class="text-end text-nowrap">
                                        @if ($record->expired_quantity > 0)
                                            <a class="fw-semibold text-danger-emphasis" href="{{ route('inventory.reports.index', ['report' => 'expiry', 'product_type' => 'medicine', 'variant_id' => $record->id, 'expiry_before' => now()->subDay()->toDateString()]) }}" aria-label="View expired stock for {{ $record->name }}">{{ $record->expired_quantity }}</a>
                                        @else
                                            0
                                        @endif
                                    </td>
                                @endif
                                <td class="text-end text-nowrap">{{ $record->purchase_price }}</td>
                                <td class="text-end text-nowrap">{{ $record->sale_price }}</td>
                                <td class="text-end text-nowrap">{{ \Modules\Core\Helpers\Money::decimal($record->closing_cost_minor) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th scope="row" colspan="2">Grand total <small class="d-block fw-normal">All filtered products / SKUs</small></th>
                            <td class="text-end text-nowrap fw-semibold">{{ $summary->opening_quantity }}</td>
                            <td class="text-end text-nowrap fw-semibold">{{ $summary->purchase_quantity }}</td>
                            <td class="text-end text-nowrap fw-semibold">{{ $summary->sale_quantity }}</td>
                            <td class="text-end text-nowrap fw-semibold">{{ $summary->purchase_return_quantity }}</td>
                            <td class="text-end text-nowrap fw-semibold">{{ $summary->sale_return_quantity }}</td>
                            <td class="text-end text-nowrap fw-semibold">{{ $summary->other_quantity > 0 ? '+' : '' }}{{ $summary->other_quantity }}</td>
                            <td class="text-end text-nowrap fw-semibold">{{ $summary->closing_quantity }}</td>
                            <td class="text-end text-nowrap fw-semibold">{{ $summary->available_quantity }}</td>
                            @if ($medicine)
                                <td class="text-end text-nowrap fw-semibold">{{ $summary->expired_quantity }}</td>
                            @endif
                            <td></td>
                            <td></td>
                            <td class="text-end text-nowrap fw-semibold">{{ \Modules\Core\Helpers\Money::decimal($summary->closing_cost_minor) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif

        <div class="d-print-none">
            @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters])
        </div>
    </section>

    <style>
        @media print {
            #inventory-stock-report .table-responsive { overflow: visible; }
            #inventory-stock-report table { width: 100%; font-size: 8pt; }
            #inventory-stock-report th, #inventory-stock-report td { padding: 0.3rem; }
            #inventory-stock-report .text-nowrap { white-space: normal !important; }
            #inventory-stock-report tr { break-inside: avoid; }
        }
    </style>
@endsection
