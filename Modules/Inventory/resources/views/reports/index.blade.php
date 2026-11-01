@extends('core::layouts.app')
@section('title', ucfirst($filters['product_type']).' '.$pageTitle)
@section('content')
    <div class="d-flex justify-content-between gap-3 mb-3">
        @include('core::partials.heading', ['title' => ucfirst($filters['product_type']).' '.$pageTitle])
        <button class="btn btn-outline-primary align-self-start d-print-none" type="button" data-inventory-print>
            Print
        </button>
    </div>
    <nav class="d-flex flex-wrap gap-2 mb-3 d-print-none" aria-label="Inventory reports">
        <a class="btn btn-sm {{ $report === 'stock' ? 'btn-primary' : 'btn-outline-secondary' }}" href="{{ route('inventory.reports.index', ['report' => 'stock', 'product_type' => $filters['product_type']]) }}">
            Stock
        </a>
        <a class="btn btn-sm {{ $report === 'movements' ? 'btn-primary' : 'btn-outline-secondary' }}" href="{{ route('inventory.reports.index', ['report' => 'movements', 'product_type' => $filters['product_type']]) }}">
            Movements
        </a>
        @if ($filters['product_type'] === 'medicine')
            <a class="btn btn-sm {{ $report === 'expiry' ? 'btn-primary' : 'btn-outline-secondary' }}" href="{{ route('inventory.reports.index', ['report' => 'expiry', 'product_type' => 'medicine']) }}">
                Expiry
            </a>
        @endif
        <a class="btn btn-sm {{ $report === 'profit-loss' ? 'btn-primary' : 'btn-outline-secondary' }}" href="{{ route('inventory.reports.index', ['report' => 'profit-loss', 'product_type' => $filters['product_type']]) }}">
            Profit / loss
        </a>
        <a class="btn btn-sm {{ $report === 'purchase-discounts' ? 'btn-primary' : 'btn-outline-secondary' }}" href="{{ route('inventory.reports.index', ['report' => 'purchase-discounts', 'product_type' => $filters['product_type']]) }}">
            Purchase discounts
        </a>
        <a class="btn btn-sm {{ $report === 'daily' ? 'btn-primary' : 'btn-outline-secondary' }}" href="{{ route('inventory.reports.index', ['report' => 'daily', 'product_type' => $filters['product_type']]) }}">
            Daily
        </a>
    </nav>
    <section class="app-card overflow-hidden">
        <form class="p-3 border-bottom d-print-none" action="{{ route('inventory.reports.index', ['report' => $report]) }}" method="GET">
            <input name="product_type" type="hidden" value="{{ $filters['product_type'] }}">
            <input name="per_page" type="hidden" value="{{ $filters['per_page'] ?? config('pagination.default') }}">
            <div class="row g-2 align-items-end">
                @if(in_array($report, ['movements', 'expiry'], true))
                    <div class="col-sm-6 col-lg-3">
                        <label class="form-label small" for="report_product">Product / SKU</label>
                        <select class="{{ inputClass('variant_id', 'form-select') }}" id="report_product" name="variant_id" data-ajax-select data-url="{{ route('inventory.reports.products', ['product_type' => $filters['product_type']]) }}" data-placeholder="Select product or SKU" {{ inputAttrs('variant_id', 'report_product') }}>
                            <option value=""></option>
                            @if ($selectedProduct)
                                <option value="{{ $selectedProduct->id }}" selected>{{ $selectedProduct->product->name }} / {{ $selectedProduct->sku }}</option>
                            @endif
                        </select>
                        @error('variant_id')<div class="invalid-feedback d-block" id="report_product-error">{{ $message }}</div>@enderror
                    </div>
                @endif
                @if($report !== 'expiry')
                    <div class="col-sm-6 col-lg-2">
                        <label class="form-label small" for="report_from">From date</label>
                        <div class="input-group" data-datepicker>
                            <input class="{{ inputClass('from_date') }}" id="report_from" name="from_date" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="{{ getValue('from_date', null, $filters['from_date']) }}" required data-input {{ inputAttrs('from_date', 'report_from') }}>
                            <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="report_from" aria-label="Choose start date" title="Choose start date">
                                <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
                            </button>
                        </div>
                        @error('from_date')
                            <div class="invalid-feedback d-block" id="report_from-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-6 col-lg-2">
                        <label class="form-label small" for="report_to">To date</label>
                        <div class="input-group" data-datepicker>
                            <input class="{{ inputClass('to_date') }}" id="report_to" name="to_date" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="{{ getValue('to_date', null, $filters['to_date']) }}" required data-input {{ inputAttrs('to_date', 'report_to') }}>
                            <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="report_to" aria-label="Choose end date" title="Choose end date">
                                <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
                            </button>
                        </div>
                        @error('to_date')
                            <div class="invalid-feedback d-block" id="report_to-error">{{ $message }}</div>
                        @enderror
                    </div>
                @endif
                @if($report === 'expiry')
                    <div class="col-sm-6 col-lg-3">
                        <label class="form-label small" for="expiry_before">Expires on / before</label>
                        <div class="input-group" data-datepicker>
                            <input class="{{ inputClass('expiry_before') }}" id="expiry_before" name="expiry_before" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="{{ getValue('expiry_before', null, $filters['expiry_before']) }}" required data-input {{ inputAttrs('expiry_before') }}>
                            <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="expiry_before" aria-label="Choose expiry limit" title="Choose expiry limit">
                                <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
                            </button>
                        </div>
                        @error('expiry_before')
                            <div class="invalid-feedback d-block" id="expiry_before-error">{{ $message }}</div>
                        @enderror
                    </div>
                @endif
                <div class="col-auto">
                    <button class="btn btn-primary" type="submit">
                        Filter
                    </button>
                </div>
            </div>
        </form>
        @if($summary && $report === 'movements')
            <div class="row g-2 p-3 border-bottom">
                <div class="col-6 col-lg-3">
                    <span class="small text-body-secondary">Opening</span>
                    <strong class="d-block">
                        {{ $summary->opening_quantity }}
                    </strong>
                </div>
                <div class="col-6 col-lg-3">
                    <span class="small text-body-secondary">In</span>
                    <strong class="d-block">
                        {{ $summary->in_quantity }}
                    </strong>
                </div>
                <div class="col-6 col-lg-3">
                    <span class="small text-body-secondary">Out</span>
                    <strong class="d-block">
                        {{ $summary->out_quantity }}
                    </strong>
                </div>
                <div class="col-6 col-lg-3">
                    <span class="small text-body-secondary">Closing</span>
                    <strong class="d-block">
                        {{ $summary->closing_quantity }}
                    </strong>
                </div>
            </div>
        @endif
        @include('inventory::reports.'.$report)
        <div class="d-print-none">
            @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters, 'routePrefix' => 'inventory.reports'])
        </div>
    </section>
@endsection
