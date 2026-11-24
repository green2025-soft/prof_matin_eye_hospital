<?php $pageTitle = ' Medicine/Product Unit' ?>
<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle, 'create' => ['model' => \Modules\Pharmacy\Models\ProductUnit::class, 'url' => route($routePrefix.'.create', $routeParameters), 'label' => 'Add unit']], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('pharmacy::products.navigation', [
        'selectedProduct' => !empty($routeParameters['product_id']) ? $records->first()?->product : null,
        'productId' => $routeParameters['product_id'] ?? null,
        'productType' => request()->attributes->get('pharmacy_product_type', 'medicine'),
        'activeSection' => 'units',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <section class="app-card overflow-hidden">
        <div class="app-card-heading px-3 px-md-4 py-3 border-bottom">
            <h2 class="h6 mb-0"><i class="fa-solid fa-boxes-stacked me-2 text-body-secondary" aria-hidden="true"></i>Unit/Pack</h2>
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
                    <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <th scope="row"><?php echo e($record->unit?->name); ?></th>
                            <td class="text-break"><?php echo e($record->product?->name); ?></td>
                            <td>1 <?php echo e($record->unit?->symbol); ?> = <?php echo e($record->factor); ?> <?php echo e($record->factor === 1 ? 'piece' : 'pieces'); ?></td>
                            <td><span class="badge text-bg-light border"><?php echo e($record->factor === 1 ? 'Base unit' : 'Pack'); ?></span></td>
                            <td><?php echo $__env->make('core::partials.row-actions', ['model' => $record, 'routePrefix' => $routePrefix, 'actionLabel' => $record->unit?->name.' for '.$record->product?->name], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td class="text-center text-body-secondary py-4" colspan="5">Add a base unit with quantity 1 before entering stock.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php echo $__env->make('core::partials.pagination', ['paginator' => $records, 'filters' => $filters], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\product-units\index.blade.php ENDPATH**/ ?>