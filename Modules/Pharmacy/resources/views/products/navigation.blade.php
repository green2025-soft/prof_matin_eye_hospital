<nav class="d-flex flex-wrap gap-2 mb-3" aria-label="Product management">
    @can('viewAny', \Modules\Pharmacy\Models\Product::class)
        <a class="btn btn-outline-secondary btn-sm" href="{{ route('pharmacy.products.index', ['type' => $productType]) }}">
            <i class="fa-solid fa-arrow-left me-1" aria-hidden="true"></i>{{ ucfirst($productType) }} products
        </a>
    @endcan
    @if ($selectedProduct !== null && $selectedProduct->exists)
        @can('update', $selectedProduct)
            <a class="btn btn-sm {{ $activeSection === 'details' ? 'btn-primary' : 'btn-outline-secondary' }}" href="{{ route('pharmacy.products.edit', ['product' => $selectedProduct->id, 'type' => $productType]) }}">Product details</a>
        @endcan
    @endif
    @if ($productId !== null)
        @can('viewAny', \Modules\Pharmacy\Models\ProductVariant::class)
            <a class="btn btn-sm {{ $activeSection === 'variants' ? 'btn-primary' : 'btn-outline-secondary' }}" href="{{ route('pharmacy.variants.index', ['product_id' => $productId]) }}">{{ $productType === 'optic' ? 'Variants / SKUs' : 'SKUs' }}</a>
        @endcan
        @can('viewAny', \Modules\Pharmacy\Models\ProductUnit::class)
            <a class="btn btn-sm {{ $activeSection === 'units' ? 'btn-primary' : 'btn-outline-secondary' }}" href="{{ route('pharmacy.product-units.index', ['product_id' => $productId]) }}">Units / packs</a>
        @endcan
    @endif
</nav>
