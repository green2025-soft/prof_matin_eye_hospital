

<?php $__env->startSection('report-filters'); ?>
    <form class="p-3" action="<?php echo e(route('pharmacy.reports.expiry')); ?>" method="GET" aria-label="Filter medicine expiry">
        <input name="product_type" type="hidden" value="medicine">
        <input name="per_page" type="hidden" value="<?php echo e($filters['per_page'] ?? config('pagination.default')); ?>">
        <div class="row g-2 align-items-end">
            <?php echo $__env->make('pharmacy::reports.product-filter', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <div class="col-sm-6 col-lg-3">
                <label class="form-label small" for="expiry_before">Expires on / before</label>
                <div class="input-group" data-datepicker>
                    <input class="<?php echo e(inputClass('expiry_before')); ?>" id="expiry_before" name="expiry_before" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD"
                        value="<?php echo e(getValue('expiry_before', null, $filters['expiry_before'])); ?>" required data-input
                        <?php echo e(inputAttrs('expiry_before')); ?>>
                    <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="expiry_before" aria-label="Choose expiry limit"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
                </div>
                <?php $__errorArgs = ['expiry_before'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="expiry_before-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-lg-4 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Filter</button>
                <a class="btn btn-outline-secondary" href="<?php echo e(route('pharmacy.reports.expiry')); ?>">Reset</a>
            </div>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-context'); ?>
    <?php if(isset($filters['search'])): ?><span>Search: <?php echo e($filters['search']); ?></span><?php endif; ?>
    <span>Expires on / before: <strong><?php echo e($filters['expiry_before']); ?></strong></span>
    <span>Product: <?php echo e($selectedProduct?->product?->name ?? 'All medicines'); ?> <?php echo e($selectedProduct?->description()); ?></span>
    <span>Stock as of <?php echo e(now()->toDateString()); ?>. Quantities in Pcs.</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-table'); ?>
    <div class="table-responsive">
        <table class="table table-sm app-table app-report-table align-middle mb-0">
            <caption class="visually-hidden">Medicine lots with remaining stock and an expiry on or before the selected date.</caption>
            <thead>
                <tr>
                    <th scope="col">Medicine / SKU</th>
                    <th scope="col">Batch</th>
                    <th scope="col">Expiry</th>
                    <th class="text-end" scope="col">Quantity (Pcs)</th>
                    <th class="text-end" scope="col">Value</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <th scope="row"><?php echo e($record->variant->product->name); ?><small class="d-block fw-normal"><?php echo e($record->variant->description()); ?></small></th>
                        <td><?php echo e($record->batch_no ?: '--'); ?></td>
                        <td><?php echo e($record->expiry_date->format('Y-m-d')); ?></td>
                        <td class="text-end"><?php echo e($record->remaining_quantity); ?></td>
                        <td class="text-end"><?php echo e(moneyDecimal($record->remaining_cost)); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td class="text-center text-body-secondary py-4" colspan="5">No medicine stock expires within this period.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('pharmacy::reports.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\reports\expiry.blade.php ENDPATH**/ ?>