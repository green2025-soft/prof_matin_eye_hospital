@extends('core::layouts.app')
@php $pageTitle = 'Medicine/Product Variant' @endphp
@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle, 'create' => ['model' => \Modules\Pharmacy\Models\ProductVariant::class, 'url' => route($routePrefix.'.create', $routeParameters), 'label' => 'Add SKU']])
    @include('pharmacy::products.navigation', [
        'selectedProduct' => !empty($routeParameters['product_id']) ? $records->first()?->product : null,
        'productId' => $routeParameters['product_id'] ?? null,
        'productType' => request()->attributes->get('pharmacy_product_type', 'medicine'),
        'activeSection' => 'variants',
    ])

    <section class="app-card overflow-hidden">
        <form class="px-3 px-md-4 py-3 border-bottom" action="{{ route($routePrefix.'.index') }}" method="GET" aria-label="Filter product SKUs">
            <div class="row g-2 align-items-end">
                <div class="col-sm-7 col-lg-5">
                    <label class="form-label small" for="search">SKU or variant name</label>
                    <div class="app-input-wrap app-search-field">
                        <i class="fa-solid fa-magnifying-glass app-search-icon" aria-hidden="true"></i>
                        <input class="{{ inputClass('search') }}" id="search" name="search" type="search" value="{{ $filters['search'] ?? '' }}" maxlength="80" {{ inputAttrs('search') }}>
                    </div>
                    @error('search')<div class="invalid-feedback d-block" id="search-error">{{ $message }}</div>@enderror
                </div>
                @if (!empty($filters['product_id']))
                    <input type="hidden" name="product_id" value="{{ $filters['product_id'] }}">
                @endif
                <div class="col-sm-5">
                    @include('core::partials.filter-actions', ['routePrefix' => $routePrefix])
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table app-table align-middle mb-0">
                <caption class="visually-hidden">Product SKUs and variants</caption>
                <thead>
                    <tr>
                        <th scope="col">SKU</th>
                        <th scope="col">Variant</th>
                        <th scope="col">Product</th>
                        <th scope="col">Status</th>
                        <th class="text-end" scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr>
                            <th scope="row" class="text-nowrap">{{ $record->sku }}</th>
                            <td>{{ $record->name ?? '—' }}</td>
                            <td class="text-break">{{ $record->product?->name }}</td>
                            <td><span class="badge {{ $record->active ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $record->active ? 'Active' : 'Inactive' }}</span></td>
                            <td>@include('core::partials.row-actions', ['model' => $record, 'routePrefix' => $routePrefix, 'actionLabel' => $record->sku])</td>
                        </tr>
                    @empty
                        <tr><td class="text-center text-body-secondary py-4" colspan="5">No SKUs added yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters])
    </section>
@endsection
