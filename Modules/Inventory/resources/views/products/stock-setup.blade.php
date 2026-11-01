<section class="app-card overflow-hidden mb-3" aria-labelledby="product-skus-heading">
    <div class="d-flex align-items-center justify-content-between gap-2 p-3 border-bottom">
        <h2 class="h6 mb-0" id="product-skus-heading"><i class="fa-solid fa-barcode me-2 text-body-secondary" aria-hidden="true"></i>{{ $product->type === 'optic' ? 'Variants / SKUs' : 'SKUs' }}</h2>
        <span class="badge text-bg-light border">{{ $product->variants_count }}</span>
    </div>
    <ul class="list-group list-group-flush">
        @forelse ($setupVariants as $variant)
            <li class="list-group-item px-3 py-2">
                <div class="d-flex align-items-start justify-content-between gap-2">
                    <div class="text-break">
                        <span class="fw-semibold text-break">{{ $variant->sku }}</span>
                        @if ($variant->name)<small class="d-block text-body-secondary text-break">{{ $variant->name }}</small>@endif
                        @if (! $variant->active)<small class="d-block text-body-secondary">Inactive</small>@endif
                    </div>
                    @can('update', $variant)
                        <a class="btn btn-outline-secondary btn-sm" href="{{ route('inventory.variants.edit', ['product_variant' => $variant->id, 'product_id' => $product->id]) }}" aria-label="Edit SKU {{ $variant->sku }}"><i class="fa-solid fa-pen-to-square" aria-hidden="true"></i></a>
                    @endcan
                </div>
                @if ($product->type === 'optic' && ($variant->color || $variant->size || $variant->sph !== null || $variant->cyl !== null))
                    <small class="d-block text-body-secondary mt-1">{{ trim(($variant->color ?? '').' '.($variant->size ?? '')) }}{{ $variant->sph !== null ? ' · SPH '.$variant->sph : '' }}{{ $variant->cyl !== null ? ' · CYL '.$variant->cyl : '' }}</small>
                @endif
            </li>
        @empty
            <li class="list-group-item px-3 py-3 small text-body-secondary">Add a SKU to enter stock.</li>
        @endforelse
    </ul>
    <div class="d-flex flex-wrap gap-2 p-3 border-top">
        @can('create', \Modules\Inventory\Models\ProductVariant::class)
            <a class="btn btn-primary btn-sm" href="{{ route('inventory.variants.create', ['product_id' => $product->id]) }}"><i class="fa-solid fa-plus me-1" aria-hidden="true"></i>Add SKU</a>
        @endcan
        <a class="btn btn-outline-secondary btn-sm" href="{{ route('inventory.variants.index', ['product_id' => $product->id]) }}">All SKUs</a>
    </div>
</section>

<section class="app-card overflow-hidden" aria-labelledby="product-units-heading">
    <div class="d-flex align-items-center justify-content-between gap-2 p-3 border-bottom">
        <h2 class="h6 mb-0" id="product-units-heading"><i class="fa-solid fa-boxes-stacked me-2 text-body-secondary" aria-hidden="true"></i>Units / packs</h2>
        <span class="badge text-bg-light border">{{ $product->units_count }}</span>
    </div>
    <ul class="list-group list-group-flush">
        @forelse ($setupUnits as $productUnit)
            <li class="list-group-item d-flex align-items-center justify-content-between gap-2 px-3 py-2">
                <div>
                    <span class="fw-semibold">{{ $productUnit->unit?->name }}</span>
                    <small class="d-block text-body-secondary">{{ $productUnit->factor }} {{ $productUnit->factor === 1 ? 'base piece' : 'base pieces' }}</small>
                </div>
                @can('update', $productUnit)
                    <a class="btn btn-outline-secondary btn-sm" href="{{ route('inventory.product-units.edit', ['product_unit' => $productUnit->id, 'product_id' => $product->id]) }}" aria-label="Edit {{ $productUnit->unit?->name }} unit"><i class="fa-solid fa-pen-to-square" aria-hidden="true"></i></a>
                @endcan
            </li>
        @empty
            <li class="list-group-item px-3 py-3 small text-body-secondary">Add a base unit with quantity 1.</li>
        @endforelse
    </ul>
    <div class="d-flex flex-wrap gap-2 p-3 border-top">
        @can('create', \Modules\Inventory\Models\ProductUnit::class)
            <a class="btn btn-primary btn-sm" href="{{ route('inventory.product-units.create', ['product_id' => $product->id]) }}"><i class="fa-solid fa-plus me-1" aria-hidden="true"></i>Add unit</a>
        @endcan
        <a class="btn btn-outline-secondary btn-sm" href="{{ route('inventory.product-units.index', ['product_id' => $product->id]) }}">All units</a>
    </div>
</section>
