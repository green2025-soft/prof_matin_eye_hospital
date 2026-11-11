@extends('core::layouts.app')

@php
    $pageTitle = 'Medicine/Product';
    $isMedicine = ($filters['type'] ?? 'medicine') === 'medicine';
@endphp

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', [
        'title' => $pageTitle,
        'create' => ['model' => \Modules\Pharmacy\Models\Product::class, 'url' => route($routePrefix.'.create', $routeParameters), 'label' => 'Add product'],
    ])

    <section class="app-card overflow-hidden">
        <form class="px-3 px-md-4 py-3 border-bottom" action="{{ route($routePrefix.'.index') }}" method="GET" aria-label="Filter products">
            <input name="type" type="hidden" value="{{ $filters['type'] ?? 'medicine' }}">
            <div class="row g-2 align-items-end">
                <div class="col-sm-8 col-xl-6">
                    <label class="form-label small" for="search">Name, ID, SKU or barcode</label>
                    <div class="app-input-wrap app-search-field">
                        <i class="fa-solid fa-magnifying-glass app-search-icon" aria-hidden="true"></i>
                        <input class="{{ inputClass('search') }}" id="search" name="search" type="search" value="{{ $filters['search'] ?? '' }}" maxlength="100" autocomplete="off" spellcheck="false" {{ inputAttrs('search') }}>
                    </div>
                    @error('search')<div class="invalid-feedback d-block" id="search-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-4 col-xl-auto">
                    @include('core::partials.filter-actions', ['routePrefix' => $routePrefix])
                </div>
            </div>
        </form>

        @if ($records->isEmpty())
            @include('core::partials.empty', ['title' => 'No products found', 'description' => 'Add a product or change the filters.', 'icon' => 'box-open'])
        @else
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <caption class="visually-hidden">Products, prices, shelf locations and stock levels</caption>
                    <thead>
                        <tr>
                            <th scope="col">Medicine/Product</th>
                            <th scope="col">Generic/Category </th>
                            @if ($isMedicine)
                                <th scope="col">Dose Type</th>
                            @endif
                            <th scope="col">Supplier</th>
                            <th scope="col">Shelf</th>
                            <th class="text-end" scope="col">Purchase</th>
                            <th class="text-end" scope="col">Sale</th>
                            <th class="text-end" scope="col">Re-order Level</th>
                            <th class="text-end" scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $record)
                            <tr>
                                <th scope="row">
                                    @can('update', $record)
                                        <a class="fw-semibold text-break text-decoration-none" href="{{ route('pharmacy.products.edit', ['product' => $record->id, 'type' => $record->type] + menuParameters()) }}">{{ $record->name }}</a>
                                    @else
                                        <span class="fw-semibold text-break">{{ $record->name }}</span>
                                    @endcan
                                    <small class="d-block fw-normal text-body-secondary mt-1">
                                        <span>ID / SKU: {{ $record->primaryVariant?->sku ?? $record->code ?? '—' }}</span>
                                        @if (filled($record->barcode))
                                            <span class="ms-2">Barcode: {{ $record->barcode }}</span>
                                        @endif
                                        @if ($record->strength)
                                            <span class="d-block">{{ $record->strength }}</span>
                                        @endif
                                    </small>
                                </th>
                                <td class="small text-break">{{ $record->category?->name ?? '—' }}</td>
                                @if ($isMedicine)
                                    <td class="small text-break">{{ $record->dosage?->name ?? '—' }}</td>
                                @endif
                                <td class="small text-break">{{ $record->supplier?->name ?? '—' }}</td>
                                <td class="small text-break">{{ $record->shelf?->name ?? '—' }}</td>
                                <td class="text-end text-nowrap">{{ $record->purchase_price }}</td>
                                <td class="text-end text-nowrap">{{ $record->sale_price }}</td>
                                <td class="text-end text-nowrap">{{ $record->reorder_level }}</td>
                                <td>
                                    <div class="d-flex flex-wrap justify-content-end align-items-center gap-2">
                                        @can('viewAny', \Modules\Pharmacy\Models\ProductVariant::class)
                                            <a class="btn btn-outline-secondary btn-sm text-nowrap" href="{{ route('pharmacy.variants.index', ['product_id' => $record->id] + menuParameters()) }}" aria-label="Manage SKUs for {{ $record->name }}"><i class="fa-solid fa-barcode me-1" aria-hidden="true"></i>{{ $record->type === 'optic' ? 'Variants' : 'SKUs' }}</a>
                                        @endcan
                                        @can('viewAny', \Modules\Pharmacy\Models\ProductUnit::class)
                                            <a class="btn btn-outline-secondary btn-sm text-nowrap" href="{{ route('pharmacy.product-units.index', ['product_id' => $record->id] + menuParameters()) }}" aria-label="Manage units and packs for {{ $record->name }}"><i class="fa-solid fa-boxes-stacked me-1" aria-hidden="true"></i>Units</a>
                                        @endcan
                                        @include('core::partials.row-actions', ['model' => $record, 'routePrefix' => $routePrefix])
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters])
    </section>
@endsection
