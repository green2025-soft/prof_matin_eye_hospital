<?php $pageTitle = 'Medicine/Product Variant' ?>
<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle, 'create' => ['model' => \Modules\Pharmacy\Models\ProductVariant::class, 'url' => route($routePrefix.'.create', $routeParameters), 'label' => 'Add SKU']], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('pharmacy::products.navigation', [
        'selectedProduct' => !empty($routeParameters['product_id']) ? $records->first()?->product : null,
        'productId' => $routeParameters['product_id'] ?? null,
        'productType' => request()->attributes->get('pharmacy_product_type', 'medicine'),
        'activeSection' => 'variants',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <section class="app-card overflow-hidden">
        <form class="px-3 px-md-4 py-3 border-bottom" action="<?php echo e(route($routePrefix.'.index')); ?>" method="GET" aria-label="Filter product SKUs">
            <div class="row g-2 align-items-end">
                <div class="col-sm-7 col-lg-5">
                    <label class="form-label small" for="search">SKU or variant name</label>
                    <div class="app-input-wrap app-search-field">
                        <i class="fa-solid fa-magnifying-glass app-search-icon" aria-hidden="true"></i>
                        <input class="<?php echo e(inputClass('search')); ?>" id="search" name="search" type="search" value="<?php echo e($filters['search'] ?? ''); ?>" maxlength="80" <?php echo e(inputAttrs('search')); ?>>
                    </div>
                    <?php $__errorArgs = ['search'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="search-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <?php if(!empty($filters['product_id'])): ?>
                    <input type="hidden" name="product_id" value="<?php echo e($filters['product_id']); ?>">
                <?php endif; ?>
                <div class="col-sm-5">
                    <?php echo $__env->make('core::partials.filter-actions', ['routePrefix' => $routePrefix], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
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
                    <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <th scope="row" class="text-nowrap"><?php echo e($record->sku); ?></th>
                            <td><?php echo e($record->name ?? '—'); ?></td>
                            <td class="text-break"><?php echo e($record->product?->name); ?></td>
                            <td><span class="badge <?php echo e($record->active ? 'text-bg-success' : 'text-bg-secondary'); ?>"><?php echo e($record->active ? 'Active' : 'Inactive'); ?></span></td>
                            <td><?php echo $__env->make('core::partials.row-actions', ['model' => $record, 'routePrefix' => $routePrefix, 'actionLabel' => $record->sku], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td class="text-center text-body-secondary py-4" colspan="5">No SKUs added yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php echo $__env->make('core::partials.pagination', ['paginator' => $records, 'filters' => $filters], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\variants\index.blade.php ENDPATH**/ ?>