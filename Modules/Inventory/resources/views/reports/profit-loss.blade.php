@extends('core::layouts.app')

@section('title', ucfirst($filters['product_type']).' Product Profit / Loss')

@section('content')
    <div class="d-flex flex-wrap justify-content-between gap-3 mb-3">
        @include('core::partials.heading', ['title' => ucfirst($filters['product_type']).' Product Profit / Loss'])
        <button class="btn btn-outline-primary align-self-start d-print-none" type="button" data-inventory-print>
            <i class="fa-solid fa-print me-2" aria-hidden="true"></i>Print
        </button>
    </div>

    <nav class="d-flex flex-wrap gap-2 mb-3 d-print-none" aria-label="Inventory reports">
        <a class="btn btn-sm btn-outline-secondary" href="{{ route('inventory.reports.index', ['report' => 'stock', 'product_type' => $filters['product_type']]) }}">Stock</a>
        <a class="btn btn-sm btn-outline-secondary" href="{{ route('inventory.reports.index', ['report' => 'movements', 'product_type' => $filters['product_type']]) }}">Movements</a>
        @if ($filters['product_type'] === 'medicine')
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('inventory.reports.index', ['report' => 'expiry', 'product_type' => 'medicine']) }}">Expiry</a>
        @endif
        <a class="btn btn-sm btn-primary" href="{{ route('inventory.reports.index', ['report' => 'profit-loss', 'product_type' => $filters['product_type']]) }}" aria-current="page">Profit / loss</a>
        <a class="btn btn-sm btn-outline-secondary" href="{{ route('inventory.reports.index', ['report' => 'purchase-discounts', 'product_type' => $filters['product_type']]) }}">Purchase discounts</a>
        <a class="btn btn-sm btn-outline-secondary" href="{{ route('inventory.reports.index', ['report' => 'daily', 'product_type' => $filters['product_type']]) }}">Daily</a>
    </nav>

    <section class="app-card overflow-hidden" id="inventory-profit-report" aria-label="Product profit and loss report">
        <form class="p-3 border-bottom d-print-none" action="{{ route('inventory.reports.index', ['report' => 'profit-loss']) }}" method="GET" aria-label="Filter product profit and loss">
            <input name="product_type" type="hidden" value="{{ $filters['product_type'] }}">
            <input name="per_page" type="hidden" value="{{ $filters['per_page'] ?? config('pagination.default') }}">
            <div class="row g-2 align-items-end">
                <div class="col-md-6 col-xl-5">
                    <label class="form-label small" for="profit_product">Product / SKU</label>
                    <select class="{{ inputClass('variant_id', 'form-select') }}" id="profit_product" name="variant_id" data-ajax-select data-url="{{ route('inventory.reports.products', ['product_type' => $filters['product_type']]) }}" data-placeholder="All products / SKUs" {{ inputAttrs('variant_id', 'profit_product') }}>
                        <option value=""></option>
                        @if ($selectedProduct !== null)
                            <option value="{{ $selectedProduct->id }}" selected>
                                {{ $selectedProduct->product->name }} / {{ $selectedProduct->sku }}
                                @if (filled($selectedProduct->name) && strtolower(trim($selectedProduct->name)) !== 'default') / {{ $selectedProduct->name }}@endif
                                @if ($selectedProduct->color) / {{ $selectedProduct->color }}@endif
                                @if ($selectedProduct->size) / Size {{ $selectedProduct->size }}@endif
                                @if (filled($selectedProduct->sph)) / SPH {{ $selectedProduct->sph }}@endif
                                @if (filled($selectedProduct->cyl)) / CYL {{ $selectedProduct->cyl }}@endif
                            </option>
                        @endif
                    </select>
                    @error('variant_id')<div class="invalid-feedback d-block" id="profit_product-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-md-3 col-xl-2">
                    <label class="form-label small" for="profit_from">From date</label>
                    <div class="input-group" data-datepicker>
                        <input class="{{ inputClass('from_date') }}" id="profit_from" name="from_date" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="{{ getValue('from_date', null, $filters['from_date']) }}" required data-input {{ inputAttrs('from_date', 'profit_from') }}>
                        <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="profit_from" aria-label="Choose start date" title="Choose start date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
                    </div>
                    @error('from_date')<div class="invalid-feedback d-block" id="profit_from-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-md-3 col-xl-2">
                    <label class="form-label small" for="profit_to">To date</label>
                    <div class="input-group" data-datepicker>
                        <input class="{{ inputClass('to_date') }}" id="profit_to" name="to_date" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="{{ getValue('to_date', null, $filters['to_date']) }}" required data-input {{ inputAttrs('to_date', 'profit_to') }}>
                        <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="profit_to" aria-label="Choose end date" title="Choose end date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
                    </div>
                    @error('to_date')<div class="invalid-feedback d-block" id="profit_to-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-xl-3 d-flex gap-2">
                    <button class="btn btn-primary" type="submit">Filter</button>
                    <a class="btn btn-outline-secondary" href="{{ route('inventory.reports.index', ['report' => 'profit-loss', 'product_type' => $filters['product_type'], 'per_page' => $filters['per_page'] ?? config('pagination.default')]) }}">Reset</a>
                </div>
            </div>
        </form>

        <div class="row g-3 row-cols-2 row-cols-lg-4 p-3 border-bottom">
            <div class="col">
                <span class="small text-body-secondary">Products / SKUs</span>
                <strong class="d-block fs-5">{{ $summary->product_count }}</strong>
            </div>
            <div class="col">
                <span class="small text-body-secondary">Net sales (BDT)</span>
                <strong class="d-block fs-5">{{ \Modules\Core\Helpers\Money::decimal($summary->net_revenue_minor) }}</strong>
            </div>
            <div class="col">
                <span class="small text-body-secondary">Cost (BDT)</span>
                <strong class="d-block fs-5">{{ \Modules\Core\Helpers\Money::decimal($summary->cost_minor) }}</strong>
            </div>
            <div class="col">
                <span class="small text-body-secondary">Product profit / loss (BDT)</span>
                <strong @class(['d-block fs-5', 'text-success-emphasis' => $summary->profit_minor > 0, 'text-danger-emphasis' => $summary->profit_minor < 0])>{{ \Modules\Core\Helpers\Money::decimal($summary->profit_minor) }}</strong>
            </div>
        </div>

        <div class="small text-body-secondary px-3 py-2 border-bottom">
            <p class="mb-0">Period: <strong>{{ $filters['from_date'] }}</strong> to <strong>{{ $filters['to_date'] }}</strong>. Quantities in Pcs; amounts in BDT. Sales exclude VAT and include saved discounts.</p>
            @if (! $showInvoiceAdjustments)
                <p class="mb-0 mt-1">Product profit excludes payment settlement discounts.</p>
            @endif
        </div>

        @if ($records->isEmpty())
            @include('core::partials.empty', ['title' => 'No product activity found', 'description' => 'Choose another product or date range.', 'icon' => 'chart-line'])
        @else
            <div class="table-responsive" role="region" aria-label="Product sales, returns, costs and profit" tabindex="0">
                <table class="table table-sm app-table align-middle mb-0">
                    <caption class="visually-hidden">Product profit and loss for the selected period. Saved sales and return costs are used. All amounts in BDT.</caption>
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
                            <th class="text-end" scope="col"><abbr title="Stock adjustments + cost variance − damage / wastage">Other +/−</abbr></th>
                            <th class="text-end" scope="col">Profit / loss</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $record)
                            <tr>
                                <th scope="row">
                                    <a class="fw-semibold text-decoration-none" href="{{ route('inventory.reports.index', ['report' => 'movements', 'product_type' => $filters['product_type'], 'variant_id' => $record->id, 'from_date' => $filters['from_date'], 'to_date' => $filters['to_date']]) }}">{{ $record->name }}</a>
                                    <small class="d-block fw-normal text-body-secondary">{{ $record->sku }}</small>
                                    @if (filled($record->variant_name) && strtolower(trim($record->variant_name)) !== 'default')
                                        <small class="d-block fw-normal text-body-secondary">{{ $record->variant_name }}</small>
                                    @endif
                                    @if ($filters['product_type'] === 'optic' && ($record->color || $record->size || filled($record->sph) || filled($record->cyl)))
                                        <small class="d-block fw-normal text-body-secondary">
                                            @if ($record->color){{ $record->color }}@endif
                                            @if ($record->size) Size {{ $record->size }}@endif
                                            @if (filled($record->sph)) SPH {{ $record->sph }}@endif
                                            @if (filled($record->cyl)) CYL {{ $record->cyl }}@endif
                                        </small>
                                    @endif
                                </th>
                                <td class="text-end text-nowrap">{{ $record->sale_quantity }}</td>
                                <td class="text-end text-nowrap">{{ \Modules\Core\Helpers\Money::decimal($record->sale_amount_minor) }}</td>
                                <td class="text-end text-nowrap">{{ $record->return_quantity }}</td>
                                <td class="text-end text-nowrap">{{ \Modules\Core\Helpers\Money::decimal($record->return_amount_minor) }}</td>
                                <td class="text-end text-nowrap">{{ \Modules\Core\Helpers\Money::decimal($record->net_revenue_minor) }}</td>
                                <td class="text-end text-nowrap">{{ \Modules\Core\Helpers\Money::decimal($record->cost_minor) }}</td>
                                <td class="text-end text-nowrap">{{ \Modules\Core\Helpers\Money::decimal($record->gross_profit_minor) }}</td>
                                <td class="text-end text-nowrap">{{ \Modules\Core\Helpers\Money::decimal($record->other_minor) }}</td>
                                <td @class(['text-end text-nowrap fw-semibold', 'text-success-emphasis' => $record->profit_minor > 0, 'text-danger-emphasis' => $record->profit_minor < 0])>{{ \Modules\Core\Helpers\Money::decimal($record->profit_minor) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th scope="row">Grand total <small class="d-block fw-normal">All filtered products / SKUs</small></th>
                            <td class="text-end text-nowrap fw-semibold">{{ $summary->sale_quantity }}</td>
                            <td class="text-end text-nowrap fw-semibold">{{ \Modules\Core\Helpers\Money::decimal($summary->sale_amount_minor) }}</td>
                            <td class="text-end text-nowrap fw-semibold">{{ $summary->return_quantity }}</td>
                            <td class="text-end text-nowrap fw-semibold">{{ \Modules\Core\Helpers\Money::decimal($summary->return_amount_minor) }}</td>
                            <td class="text-end text-nowrap fw-semibold">{{ \Modules\Core\Helpers\Money::decimal($summary->net_revenue_minor) }}</td>
                            <td class="text-end text-nowrap fw-semibold">{{ \Modules\Core\Helpers\Money::decimal($summary->cost_minor) }}</td>
                            <td class="text-end text-nowrap fw-semibold">{{ \Modules\Core\Helpers\Money::decimal($summary->gross_profit_minor) }}</td>
                            <td class="text-end text-nowrap fw-semibold">{{ \Modules\Core\Helpers\Money::decimal($summary->other_minor) }}</td>
                            <td class="text-end text-nowrap fw-semibold">{{ \Modules\Core\Helpers\Money::decimal($summary->profit_minor) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif

        <p class="small text-body-secondary px-3 py-2 mb-0 border-top">Other +/−: stock adjustments + cost variance − damage / wastage.</p>

        @if ($showInvoiceAdjustments)
            <div class="row g-3 row-cols-1 row-cols-md-3 p-3 border-top">
                <div class="col">
                    <span class="small text-body-secondary">Customer settlement discounts (−)</span>
                    <strong class="d-block">{{ \Modules\Core\Helpers\Money::decimal($summary->customer_discount_minor) }}</strong>
                </div>
                <div class="col">
                    <span class="small text-body-secondary">Supplier settlement discounts (+)</span>
                    <strong class="d-block">{{ \Modules\Core\Helpers\Money::decimal($summary->supplier_discount_minor) }}</strong>
                </div>
                <div class="col">
                    <span class="small text-body-secondary">Net profit / loss (BDT)</span>
                    <strong @class(['d-block fs-5', 'text-success-emphasis' => $summary->net_profit_minor > 0, 'text-danger-emphasis' => $summary->net_profit_minor < 0])>{{ \Modules\Core\Helpers\Money::decimal($summary->net_profit_minor) }}</strong>
                </div>
            </div>
        @endif

        <div class="d-print-none">
            @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters])
        </div>
    </section>

    <style>
        @media print {
            #inventory-profit-report .table-responsive { overflow: visible; }
            #inventory-profit-report table { width: 100%; font-size: 9pt; }
            #inventory-profit-report th, #inventory-profit-report td { padding: 0.3rem; }
            #inventory-profit-report .text-nowrap { white-space: normal !important; }
            #inventory-profit-report tr { break-inside: avoid; }
        }
    </style>
@endsection
