<section class="app-card overflow-hidden mb-3" aria-labelledby="product-skus-heading">
    <div class="d-flex align-items-center justify-content-between gap-2 p-3 border-bottom">
        <h2 class="h6 mb-0" id="product-skus-heading"><i class="fa-solid fa-barcode me-2 text-body-secondary" aria-hidden="true"></i><?php echo e($product->type === 'optic' ? 'Variants / SKUs' : 'SKUs'); ?></h2>
        <span class="badge text-bg-light border"><?php echo e($product->variants_count); ?></span>
    </div>
    <ul class="list-group list-group-flush">
        <?php $__empty_1 = true; $__currentLoopData = $setupVariants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <li class="list-group-item px-3 py-2">
                <div class="d-flex align-items-start justify-content-between gap-2">
                    <div class="text-break">
                        <span class="fw-semibold text-break"><?php echo e($variant->sku); ?></span>
                        <?php if($variant->name): ?><small class="d-block text-body-secondary text-break"><?php echo e($variant->name); ?></small><?php endif; ?>
                        <?php if(! $variant->active): ?><small class="d-block text-body-secondary">Inactive</small><?php endif; ?>
                    </div>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $variant)): ?>
                        <a class="btn btn-outline-secondary btn-sm" href="<?php echo e(route('pharmacy.variants.edit', ['product_variant' => $variant->id, 'product_id' => $product->id] + menuParameters())); ?>" aria-label="Edit SKU <?php echo e($variant->sku); ?>"><i class="fa-solid fa-pen-to-square" aria-hidden="true"></i></a>
                    <?php endif; ?>
                </div>
                <?php if($product->type === 'optic' && ($variant->color || $variant->size || $variant->sph !== null || $variant->cyl !== null)): ?>
                    <small class="d-block text-body-secondary mt-1"><?php echo e(trim(($variant->color ?? '').' '.($variant->size ?? ''))); ?><?php echo e($variant->sph !== null ? ' · SPH '.$variant->sph : ''); ?><?php echo e($variant->cyl !== null ? ' · CYL '.$variant->cyl : ''); ?></small>
                <?php endif; ?>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <li class="list-group-item px-3 py-3 small text-body-secondary">Add a SKU to enter stock.</li>
        <?php endif; ?>
    </ul>
    <div class="d-flex flex-wrap gap-2 p-3 border-top">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \Modules\Pharmacy\Models\ProductVariant::class)): ?>
            <a class="btn btn-primary btn-sm" href="<?php echo e(route('pharmacy.variants.create', ['product_id' => $product->id] + menuParameters())); ?>"><i class="fa-solid fa-plus me-1" aria-hidden="true"></i>Add SKU</a>
        <?php endif; ?>
        <a class="btn btn-outline-secondary btn-sm" href="<?php echo e(route('pharmacy.variants.index', ['product_id' => $product->id] + menuParameters())); ?>">All SKUs</a>
    </div>
</section>

<section class="app-card overflow-hidden" aria-labelledby="product-units-heading">
    <div class="d-flex align-items-center justify-content-between gap-2 p-3 border-bottom">
        <h2 class="h6 mb-0" id="product-units-heading"><i class="fa-solid fa-boxes-stacked me-2 text-body-secondary" aria-hidden="true"></i>Units / packs</h2>
        <span class="badge text-bg-light border"><?php echo e($product->units_count); ?></span>
    </div>
    <ul class="list-group list-group-flush">
        <?php $__empty_1 = true; $__currentLoopData = $setupUnits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $productUnit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <li class="list-group-item d-flex align-items-center justify-content-between gap-2 px-3 py-2">
                <div>
                    <span class="fw-semibold"><?php echo e($productUnit->unit?->name); ?></span>
                    <small class="d-block text-body-secondary"><?php echo e($productUnit->factor); ?> <?php echo e($productUnit->factor === 1 ? 'base piece' : 'base pieces'); ?></small>
                </div>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $productUnit)): ?>
                    <a class="btn btn-outline-secondary btn-sm" href="<?php echo e(route('pharmacy.product-units.edit', ['product_unit' => $productUnit->id, 'product_id' => $product->id] + menuParameters())); ?>" aria-label="Edit <?php echo e($productUnit->unit?->name); ?> unit"><i class="fa-solid fa-pen-to-square" aria-hidden="true"></i></a>
                <?php endif; ?>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <li class="list-group-item px-3 py-3 small text-body-secondary">Add a base unit with quantity 1.</li>
        <?php endif; ?>
    </ul>
    <div class="d-flex flex-wrap gap-2 p-3 border-top">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \Modules\Pharmacy\Models\ProductUnit::class)): ?>
            <a class="btn btn-primary btn-sm" href="<?php echo e(route('pharmacy.product-units.create', ['product_id' => $product->id] + menuParameters())); ?>"><i class="fa-solid fa-plus me-1" aria-hidden="true"></i>Add unit</a>
        <?php endif; ?>
        <a class="btn btn-outline-secondary btn-sm" href="<?php echo e(route('pharmacy.product-units.index', ['product_id' => $product->id] + menuParameters())); ?>">All units</a>
    </div>
</section>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\products\stock-setup.blade.php ENDPATH**/ ?>