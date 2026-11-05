@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle, 'create' => ['model' => \Modules\Pharmacy\Models\ProductUnit::class, 'url' => route($routePrefix.'.create', $routeParameters), 'label' => 'Add unit']])
    @include('pharmacy::products.navigation', [
        'selectedProduct' => !empty($routeParameters['product_id']) ? $records->first()?->product : null,
        'productId' => $routeParameters['product_id'] ?? null,
        'productType' => request()->attributes->get('pharmacy_product_type', 'medicine'),
        'activeSection' => 'units',
    ])

    <section class="app-card overflow-hidden">
        <div class="app-card-heading px-3 px-md-4 py-3 border-bottom">
            <h2 class="h6 mb-0"><i class="fa-solid fa-boxes-stacked me-2 text-body-secondary" aria-hidden="true"></i>Units / packs</h2>
        </div>
        <div class="table-responsive">
            <table class="table app-table align-middle mb-0">
                <caption class="visually-hidden">Product units and pack conversions</caption>
                <thead>
                    <tr>
                        <th scope="col">Unit</th>
                        <th scope="col">Product</th>
                        <th scope="col">Conversion</th>
                        <th scope="col">Use</th>
                        <th class="text-end" scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr>
                            <th scope="row">{{ $record->unit?->name }}</th>
                            <td class="text-break">{{ $record->product?->name }}</td>
                            <td>1 {{ $record->unit?->symbol }} = {{ $record->factor }} {{ $record->factor === 1 ? 'piece' : 'pieces' }}</td>
                            <td><span class="badge text-bg-light border">{{ $record->factor === 1 ? 'Base unit' : 'Pack' }}</span></td>
                            <td>@include('core::partials.row-actions', ['model' => $record, 'routePrefix' => $routePrefix, 'actionLabel' => $record->unit?->name.' for '.$record->product?->name])</td>
                        </tr>
                    @empty
                        <tr><td class="text-center text-body-secondary py-4" colspan="5">Add a base unit with quantity 1 before entering stock.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters])
    </section>
@endsection
