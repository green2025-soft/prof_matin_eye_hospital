<?php $__env->startSection('title', ucfirst($filters['product_type'] ?? 'medicine').' Purchases'); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between gap-3 align-items-start mb-3">
        <?php echo $__env->make('core::partials.heading', ['title' => ucfirst($filters['product_type'] ?? 'medicine').' Purchases'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php if($canCreate): ?>
            <a class="btn btn-primary text-nowrap" href="<?php echo e(route($routePrefix.'.create', ['product_type' => $filters['product_type'] ?? 'medicine'])); ?>">
                <i class="fa-solid fa-plus me-2" aria-hidden="true"></i>New Purchase
            </a>
        <?php endif; ?>
    </div>

    <section class="app-card overflow-hidden">
        <?php echo $__env->make('pharmacy::entries.partials.filters', ['numberLabel' => 'Invoice No.'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="table-responsive">
            <table class="table app-table align-middle mb-0">
                <caption class="visually-hidden">Purchases and saved amounts</caption>
                <thead>
                    <tr>
                        <th scope="col">Number</th>
                        <th scope="col">Date</th>
                        <th scope="col">Supplier</th>
                        <th scope="col">Supplier invoice no.</th>
                        <th scope="col" class="text-end">Total</th>
                        <th scope="col" class="text-end">Paid</th>
                        <th scope="col" class="text-end">Due</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <a href="<?php echo e(route($routePrefix.'.show', [$resourceParameter => $record, 'product_type' => $record->product_type])); ?>"><?php echo e($record->number); ?></a>
                            </td>
                            <td class="text-nowrap"><?php echo e($record->entry_date->format('Y-m-d')); ?></td>
                            <td><?php echo e($record->supplier?->name ?? '—'); ?></td>
                            <td><?php echo e($record->supplier_invoice_no ?: '—'); ?></td>
                            <td class="text-end"><?php echo e(moneyDecimal($record->total)); ?></td>
                            <td class="text-end"><?php echo e(moneyDecimal($record->paid)); ?></td>
                            <td class="text-end"><?php echo e(moneyDecimal($record->voided_at ? 0 : $record->outstandingMinor())); ?></td>
                            <td>
                                <?php echo $__env->make('pharmacy::entries.partials.status', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <?php if($canUpdatePurchases && ! $record->voided_at): ?>
                                        <a class="btn btn-outline-primary btn-sm app-action-button" href="<?php echo e(route($routePrefix.'.edit', [$resourceParameter => $record, 'product_type' => $record->product_type])); ?>" aria-label="Edit <?php echo e($record->number); ?>" title="Edit">
                                            <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if($canDeletePurchases): ?>
                                        <form action="<?php echo e(route($routePrefix.'.destroy', [$resourceParameter => $record, 'product_type' => $record->product_type])); ?>" method="POST" data-confirm="Delete this purchase and reverse its stock and amounts?">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button class="btn btn-outline-danger btn-sm app-action-button" type="submit" aria-label="Delete <?php echo e($record->number); ?>" title="Delete">
                                                <i class="fa-solid fa-trash-can" aria-hidden="true"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="9" class="text-center text-body-secondary py-4">No purchases found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php echo $__env->make('core::partials.pagination', ['paginator' => $records, 'filters' => $filters, 'routePrefix' => $routePrefix], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\purchases\index.blade.php ENDPATH**/ ?>