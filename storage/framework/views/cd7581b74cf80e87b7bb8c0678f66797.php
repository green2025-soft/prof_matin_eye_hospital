

<?php $__env->startSection('report-filters'); ?>
    <form class="p-3" action="<?php echo e(route('pharmacy.reports.purchase-discounts')); ?>" method="GET" aria-label="Filter purchase discounts">
        <input name="product_type" type="hidden" value="<?php echo e($filters['product_type']); ?>">
        <input name="per_page" type="hidden" value="<?php echo e($filters['per_page'] ?? config('pagination.default')); ?>">
        <div class="row g-2 align-items-end">
            <?php echo $__env->make('pharmacy::reports.date-range', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <div class="col-lg-4 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Filter</button>
                <a class="btn btn-outline-secondary" href="<?php echo e(route('pharmacy.reports.purchase-discounts', ['product_type' => $filters['product_type']])); ?>">Reset</a>
            </div>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-context'); ?>
    <span>Period: <strong><?php echo e($filters['from_date']); ?></strong> to <strong><?php echo e($filters['to_date']); ?></strong></span>
    <span>Item, invoice and payment settlement discounts are shown separately.</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-table'); ?>
    <div class="table-responsive">
        <table class="table table-sm app-table app-report-table align-middle mb-0">
            <caption class="visually-hidden">Discounts on active purchase invoices within the selected period.</caption>
            <thead>
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col">Invoice / supplier</th>
                    <th class="text-end" scope="col">Item discounts</th>
                    <th class="text-end" scope="col">Invoice discount</th>
                    <th class="text-end" scope="col">Settlement discount</th>
                    <th class="text-end" scope="col">Invoice total</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($record->entry_date->format('Y-m-d')); ?></td>
                        <th scope="row"><?php echo e($record->number); ?><small class="d-block fw-normal"><?php echo e($record->supplier?->name); ?></small></th>
                        <td class="text-end"><?php echo e(moneyDecimal($record->line_discount ?? 0)); ?></td>
                        <td class="text-end"><?php echo e(moneyDecimal($record->discount - ($record->line_discount ?? 0))); ?></td>
                        <td class="text-end"><?php echo e(moneyDecimal($record->settlement_discount)); ?></td>
                        <td class="text-end"><?php echo e(moneyDecimal($record->total)); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td class="text-center text-body-secondary py-4" colspan="6">No purchases match this period.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('pharmacy::reports.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\reports\purchase-discounts.blade.php ENDPATH**/ ?>