

<?php $__env->startSection('report-filters'); ?>
    <form class="p-3" action="<?php echo e(route('pharmacy.reports.movements')); ?>" method="GET" aria-label="Filter stock movements">
        <input name="product_type" type="hidden" value="<?php echo e($filters['product_type']); ?>">
        <input name="per_page" type="hidden" value="<?php echo e($filters['per_page'] ?? config('pagination.default')); ?>">
        <div class="row g-2 align-items-end">
            <?php echo $__env->make('pharmacy::reports.product-filter', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->make('pharmacy::reports.date-range', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <div class="col-lg-4 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Filter</button>
                <a class="btn btn-outline-secondary" href="<?php echo e(route('pharmacy.reports.movements', ['product_type' => $filters['product_type']])); ?>">Reset</a>
            </div>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-context'); ?>
    <?php if(isset($filters['search'])): ?><span>Search: <?php echo e($filters['search']); ?></span><?php endif; ?>
    <span>Period: <strong><?php echo e($filters['from_date']); ?></strong> to <strong><?php echo e($filters['to_date']); ?></strong></span>
    <span>Product: <?php echo e($selectedProduct?->product?->name ?? 'All products'); ?> <?php echo e($selectedProduct?->description()); ?></span>
    <span>Quantities in Pcs. Reversals retain their original entry number.</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-summary'); ?>
    <div class="app-report-summary row row-cols-2 row-cols-md-4 g-3">
        <div class="col"><span class="d-block">Opening (Pcs)</span><strong class="d-block"><?php echo e($summary->opening_quantity); ?></strong></div>
        <div class="col"><span class="d-block">In (Pcs)</span><strong class="d-block"><?php echo e($summary->in_quantity); ?></strong></div>
        <div class="col"><span class="d-block">Out (Pcs)</span><strong class="d-block"><?php echo e($summary->out_quantity); ?></strong></div>
        <div class="col"><span class="d-block">Closing (Pcs)</span><strong class="d-block"><?php echo e($summary->closing_quantity); ?></strong></div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-table'); ?>
    <div class="table-responsive">
        <table class="table table-sm app-table app-report-table align-middle mb-0">
            <caption class="visually-hidden">Stock receipts, issues, returns, adjustments and reversals. Quantities in Pcs.</caption>
            <thead>
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col">Entry</th>
                    <th scope="col">Movement</th>
                    <th scope="col">Product / SKU</th>
                    <th class="text-end" scope="col">In</th>
                    <th class="text-end" scope="col">Out</th>
                    <th class="text-end" scope="col">Value</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($record->movement_date->format('Y-m-d')); ?></td>
                        <td><?php echo e($record->stockEntry?->number ?? '--'); ?></td>
                        <td>
                            <?php echo e(ucfirst(str_replace('_', ' ', $record->type))); ?>

                            <?php if($record->is_reversal): ?><small class="d-block">Reversal</small><?php endif; ?>
                        </td>
                        <th scope="row"><?php echo e($record->variant->product->name); ?><small class="d-block fw-normal"><?php echo e($record->variant->description()); ?></small></th>
                        <td class="text-end"><?php echo e(max(0, $record->quantity)); ?></td>
                        <td class="text-end"><?php echo e(abs(min(0, $record->quantity))); ?></td>
                        <td class="text-end"><?php echo e(moneyDecimal($record->cost)); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td class="text-center text-body-secondary py-4" colspan="7">No stock movements match this period.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('pharmacy::reports.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules/Pharmacy\resources/views/reports/movements.blade.php ENDPATH**/ ?>