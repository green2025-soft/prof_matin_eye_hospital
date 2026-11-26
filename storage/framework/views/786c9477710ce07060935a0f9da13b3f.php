<?php $__env->startSection('report-page-class', 'app-report-wide'); ?>

<?php $__env->startSection('report-filters'); ?>
    <form class="p-3" action="<?php echo e(route('pharmacy.reports.supplier-dues')); ?>" method="GET" aria-label="Filter supplier dues">
        <input name="product_type" type="hidden" value="<?php echo e($filters['product_type']); ?>">
        <input name="per_page" type="hidden" value="<?php echo e($filters['per_page'] ?? config('pagination.default')); ?>">
        <div class="row g-2 align-items-end">
            <div class="col-md-7 col-lg-5">
                <label class="form-label small" for="supplier_id">Supplier</label>
                <select class="<?php echo e(inputClass('supplier_id', 'form-select')); ?>" id="supplier_id" name="supplier_id"
                    data-ajax-select data-url="<?php echo e(route('pharmacy.purchase-payments.suppliers', ['product_type' => $filters['product_type']])); ?>"
                    data-placeholder="Search supplier"
                    <?php echo e(inputAttrs('supplier_id')); ?>>
                    <option value=""></option>
                    <?php if($selectedSupplier !== null): ?>
                        <option value="<?php echo e($selectedSupplier->id); ?>" selected><?php echo e($selectedSupplier->name); ?></option>
                    <?php endif; ?>
                </select>
                <?php $__errorArgs = ['supplier_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="supplier_id-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-5 col-lg-4 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Filter</button>
                <a class="btn btn-outline-secondary" href="<?php echo e(route('pharmacy.reports.supplier-dues', ['product_type' => $filters['product_type']])); ?>">Reset</a>
            </div>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-context'); ?>
    <span>As of: <strong><?php echo e(now()->toDateString()); ?></strong></span>
    <span>Supplier: <?php echo e($selectedSupplier?->name ?? 'All suppliers with dues'); ?></span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-summary'); ?>
    <div class="app-report-summary row row-cols-3 g-3">
        <div class="col"><span>Total dues (BDT)</span><strong><?php echo e(moneyDecimal($summary->due)); ?></strong></div>
        <div class="col"><span>Suppliers</span><strong><?php echo e($summary->party_count); ?></strong></div>
        <div class="col"><span>Invoices</span><strong><?php echo e($summary->invoice_count); ?></strong></div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-table'); ?>
    <div class="table-responsive">
        <table class="table table-sm app-table app-report-table align-middle mb-0">
            <caption class="visually-hidden">Current supplier outstanding balances. Amounts in BDT.</caption>
            <thead>
                <tr>
                    <th scope="col">Supplier / ID</th>
                    <th scope="col">Contact</th>
                    <th class="text-end" scope="col">Invoices</th>
                    <th class="text-end" scope="col">Invoice amount</th>
                    <th class="text-end" scope="col">Returns</th>
                    <th class="text-end" scope="col">Paid</th>
                    <th class="text-end" scope="col">Refunds</th>
                    <th class="text-end" scope="col">Settlement discount</th>
                    <th class="text-end" scope="col">Due</th>
                    <?php if($canCreatePayment): ?><th class="text-end d-print-none" scope="col">Payment</th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php ($supplier = $suppliers->get($record->party_id)); ?>
                    <tr>
                        <th scope="row"><?php echo e($supplier?->name ?? 'Supplier #'.$record->party_id); ?><small class="d-block fw-normal"><?php echo e($record->party_id); ?></small></th>
                        <td><?php echo e($supplier?->contact_no ?? '--'); ?></td>
                        <td class="text-end"><?php echo e($record->invoice_count); ?></td>
                        <td class="text-end"><?php echo e(moneyDecimal($record->invoice_total)); ?></td>
                        <td class="text-end"><?php echo e(moneyDecimal($record->returned)); ?></td>
                        <td class="text-end"><?php echo e(moneyDecimal($record->paid)); ?></td>
                        <td class="text-end"><?php echo e(moneyDecimal($record->refunded)); ?></td>
                        <td class="text-end"><?php echo e(moneyDecimal($record->settlement_discount)); ?></td>
                        <td class="text-end fw-semibold"><?php echo e(moneyDecimal($record->due)); ?></td>
                        <?php if($canCreatePayment): ?>
                            <td class="text-end d-print-none"><a class="btn btn-outline-primary btn-sm text-nowrap" href="<?php echo e(route('pharmacy.purchase-payments.create', ['product_type' => $filters['product_type'], 'supplier_id' => $record->party_id])); ?>">Pay due</a></td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td class="text-center text-body-secondary py-4" colspan="9">No outstanding supplier balances match these filters.</td>
                        <?php if($canCreatePayment): ?><td class="d-print-none"></td><?php endif; ?>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('pharmacy::reports.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\reports\supplier-dues.blade.php ENDPATH**/ ?>