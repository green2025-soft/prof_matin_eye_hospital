<?php $__env->startSection('title', ucfirst($filters['product_type'] ?? 'medicine').' Opening Stock'); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between gap-3 align-items-start mb-3">
        <?php echo $__env->make('core::partials.heading', ['title' => ucfirst($filters['product_type'] ?? 'medicine').' Opening Stock'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php if($canCreate): ?>
            <a class="btn btn-primary text-nowrap" href="<?php echo e(route($routePrefix.'.create', ['product_type' => $filters['product_type'] ?? 'medicine'])); ?>">
                <i class="fa-solid fa-plus me-2" aria-hidden="true"></i>New Opening Stock
            </a>
        <?php endif; ?>
    </div>

    <section class="app-card overflow-hidden">
        <?php echo $__env->make('pharmacy::entries.partials.filters', ['numberLabel' => 'Entry No.'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="table-responsive">
            <table class="table app-table align-middle mb-0">
                <caption class="visually-hidden">Opening Stock and saved amounts</caption>
                <thead>
                    <tr>
                        <th scope="col">Number</th>
                        <th scope="col">Date</th>
                        <th scope="col" class="text-end">Stock Value</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <a href="<?php echo e(route($routePrefix.'.show', [$resourceParameter => $record, 'product_type' => $record->product_type])); ?>"><?php echo e($record->number); ?></a>
                            </td>
                            <td class="text-nowrap"><?php echo e($record->entry_date->format('Y-m-d')); ?></td>
                            <td class="text-end"><?php echo e(moneyDecimal($record->cost)); ?></td>
                            <td>
                                <?php echo $__env->make('pharmacy::entries.partials.status', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="text-center text-body-secondary py-4">No opening stock found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php echo $__env->make('core::partials.pagination', ['paginator' => $records, 'filters' => $filters, 'routePrefix' => $routePrefix], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\opening-stock\index.blade.php ENDPATH**/ ?>