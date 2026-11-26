<?php
    $pageTitle = 'Medicine/Product';
    $isMedicine = ($filters['type'] ?? 'medicine') === 'medicine';
?>

<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', [
        'title' => $pageTitle,
        'create' => ['model' => \Modules\Pharmacy\Models\Product::class, 'url' => route($routePrefix.'.create', $routeParameters), 'label' => 'Add Medicine'],
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <section class="app-card overflow-hidden">
        <form class="px-3 px-md-4 py-3 border-bottom" action="<?php echo e(route($routePrefix.'.index')); ?>" method="GET" aria-label="Filter products">
            <input name="type" type="hidden" value="<?php echo e($filters['type'] ?? 'medicine'); ?>">
            <div class="row g-2 align-items-end">
                <div class="col-sm-8 col-xl-6">
                    <label class="form-label small" for="search">Name, ID or Barcode</label>
                    <div class="app-input-wrap app-search-field">
                        <i class="fa-solid fa-magnifying-glass app-search-icon" aria-hidden="true"></i>
                        <input class="<?php echo e(inputClass('search')); ?>" id="search" name="search" type="search" value="<?php echo e($filters['search'] ?? ''); ?>" maxlength="100" autocomplete="off" spellcheck="false" <?php echo e(inputAttrs('search')); ?>>
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
                <div class="col-sm-4 col-xl-auto">
                    <?php echo $__env->make('core::partials.filter-actions', ['routePrefix' => $routePrefix], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>
        </form>

        <?php if($records->isEmpty()): ?>
            <?php echo $__env->make('core::partials.empty', ['title' => 'No products found', 'description' => 'Add a product or change the filters.', 'icon' => 'box-open'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <caption class="visually-hidden">Products, prices, shelf locations and stock levels</caption>
                    <thead>
                        <tr>
                            <th scope="col">Medicine/Product</th>
                            <th scope="col">Generic/Category </th>
                            <?php if($isMedicine): ?>
                                <th scope="col">Dose Type</th>
                            <?php endif; ?>
                            <th scope="col">Supplier</th>
                            <th scope="col">Shelf</th>
                            <th class="text-end" scope="col">Purchase</th>
                            <th class="text-end" scope="col">Sale</th>
                            <th class="text-end" scope="col">Re-order Level</th>
                            <th class="text-end" scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <th scope="row">
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $record)): ?>
                                        <a class="fw-semibold text-break text-decoration-none" href="<?php echo e(route('pharmacy.products.edit', ['product' => $record->id, 'type' => $record->type] + menuParameters())); ?>"><?php echo e($record->name); ?></a>
                                    <?php else: ?>
                                        <span class="fw-semibold text-break"><?php echo e($record->name); ?></span>
                                    <?php endif; ?>
                                    <small class="d-block fw-normal text-body-secondary mt-1">
                                        <span>ID / SKU: <?php echo e($record->primaryVariant?->sku ?? $record->code ?? '—'); ?></span>
                                        <?php if(filled($record->barcode)): ?>
                                            <span class="ms-2">Barcode: <?php echo e($record->barcode); ?></span>
                                        <?php endif; ?>
                                        <?php if($record->strength): ?>
                                            <span class="d-block"><?php echo e($record->strength); ?></span>
                                        <?php endif; ?>
                                    </small>
                                </th>
                                <td class="small text-break"><?php echo e($record->category?->name ?? '—'); ?></td>
                                <?php if($isMedicine): ?>
                                    <td class="small text-break"><?php echo e($record->dosage?->name ?? '—'); ?></td>
                                <?php endif; ?>
                                <td class="small text-break"><?php echo e($record->supplier?->name ?? '—'); ?></td>
                                <td class="small text-break"><?php echo e($record->shelf?->name ?? '—'); ?></td>
                                <td class="text-end text-nowrap"><?php echo e($record->purchase_price); ?></td>
                                <td class="text-end text-nowrap"><?php echo e($record->sale_price); ?></td>
                                <td class="text-end text-nowrap"><?php echo e($record->reorder_level); ?></td>
                                <td>
                                    <div class="d-flex flex-wrap justify-content-end align-items-center gap-2">
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewAny', \Modules\Pharmacy\Models\ProductVariant::class)): ?>
                                            <a class="btn btn-outline-secondary btn-sm text-nowrap" href="<?php echo e(route('pharmacy.variants.index', ['product_id' => $record->id] + menuParameters())); ?>" aria-label="Manage SKUs for <?php echo e($record->name); ?>"><i class="fa-solid fa-barcode me-1" aria-hidden="true"></i><?php echo e($record->type === 'optic' ? 'Variants' : 'SKUs'); ?></a>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewAny', \Modules\Pharmacy\Models\ProductUnit::class)): ?>
                                            <a class="btn btn-outline-secondary btn-sm text-nowrap" href="<?php echo e(route('pharmacy.product-units.index', ['product_id' => $record->id] + menuParameters())); ?>" aria-label="Manage units and packs for <?php echo e($record->name); ?>"><i class="fa-solid fa-boxes-stacked me-1" aria-hidden="true"></i>Units</a>
                                        <?php endif; ?>
                                        <?php echo $__env->make('core::partials.row-actions', ['model' => $record, 'routePrefix' => $routePrefix], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        <?php echo $__env->make('core::partials.pagination', ['paginator' => $records, 'filters' => $filters], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\products\index.blade.php ENDPATH**/ ?>