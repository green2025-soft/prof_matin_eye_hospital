<nav class="d-flex flex-wrap gap-2 mb-3" aria-label="Product management">
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewAny', \Modules\Pharmacy\Models\Product::class)): ?>
        <a class="btn btn-outline-secondary btn-sm" href="<?php echo e(route('pharmacy.products.index', ['type' => $productType] + menuParameters())); ?>">
            <i class="fa-solid fa-arrow-left me-1" aria-hidden="true"></i><?php echo e(ucfirst($productType)); ?> products
        </a>
    <?php endif; ?>
    <?php if($selectedProduct?->exists): ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $selectedProduct)): ?>
            <a class="btn btn-sm <?php echo e($activeSection === 'details' ? 'btn-primary' : 'btn-outline-secondary'); ?>" href="<?php echo e(route('pharmacy.products.edit', ['product' => $selectedProduct->id, 'type' => $productType] + menuParameters())); ?>">Product details</a>
        <?php endif; ?>
    <?php endif; ?>
    <?php if($productId !== null): ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewAny', \Modules\Pharmacy\Models\ProductVariant::class)): ?>
            <a class="btn btn-sm <?php echo e($activeSection === 'variants' ? 'btn-primary' : 'btn-outline-secondary'); ?>" href="<?php echo e(route('pharmacy.variants.index', ['product_id' => $productId] + menuParameters())); ?>"><?php echo e($productType === 'optic' ? 'Variants / SKUs' : 'SKUs'); ?></a>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewAny', \Modules\Pharmacy\Models\ProductUnit::class)): ?>
            <a class="btn btn-sm <?php echo e($activeSection === 'units' ? 'btn-primary' : 'btn-outline-secondary'); ?>" href="<?php echo e(route('pharmacy.product-units.index', ['product_id' => $productId] + menuParameters())); ?>">Unit/Pack</a>
        <?php endif; ?>
    <?php endif; ?>
</nav>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\products\navigation.blade.php ENDPATH**/ ?>