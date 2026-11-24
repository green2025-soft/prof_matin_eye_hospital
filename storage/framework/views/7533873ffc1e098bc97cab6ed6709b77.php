

<?php $__env->startSection('report-page-class', 'app-report-wide'); ?>

<?php $__env->startSection('report-filters'); ?>
    <form class="p-3" action="<?php echo e(route('pharmacy.reports.customer-dues')); ?>" method="GET" aria-label="Filter customer dues">
        <input name="product_type" type="hidden" value="<?php echo e($filters['product_type']); ?>">
        <input name="per_page" type="hidden" value="<?php echo e($filters['per_page'] ?? config('pagination.default')); ?>">
        <div class="row g-2 align-items-end">
            <div class="col-md-7 col-lg-5">
                <label class="form-label small" for="customer_id">Customer</label>
                <select class="<?php echo e(inputClass('customer_id', 'form-select')); ?>" id="customer_id" name="customer_id"
                    data-ajax-select data-url="<?php echo e(route('pharmacy.sale-payments.customers', ['product_type' => $filters['product_type']])); ?>"
                    data-label-field="text"
                    data-placeholder="Patient ID, name or mobile"
                    <?php echo e(inputAttrs('customer_id')); ?>>
                    <option value=""></option>
                    <?php if($selectedCustomer !== null): ?>
                        <option value="<?php echo e($selectedCustomer['id']); ?>" selected><?php echo e($selectedCustomer['text']); ?></option>
                    <?php endif; ?>
                </select>
                <?php $__errorArgs = ['customer_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="customer_id-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-5 col-lg-4 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Filter</button>
                <a class="btn btn-outline-secondary" href="<?php echo e(route('pharmacy.reports.customer-dues', ['product_type' => $filters['product_type']])); ?>">Reset</a>
            </div>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-context'); ?>
    <span>As of: <strong><?php echo e(now()->toDateString()); ?></strong></span>
    <span>Customer: <?php echo e($selectedCustomer['text'] ?? 'All customers with dues'); ?></span>
    <span>All current outstanding invoices, including earlier periods.</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-summary'); ?>
    <div class="app-report-summary row row-cols-3 g-3">
        <div class="col"><span>Total dues (BDT)</span><strong><?php echo e(moneyDecimal($summary->due)); ?></strong></div>
        <div class="col"><span>Customers</span><strong><?php echo e($summary->party_count); ?></strong></div>
        <div class="col"><span>Invoices</span><strong><?php echo e($summary->invoice_count); ?></strong></div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-table'); ?>
    <div class="table-responsive">
        <table class="table table-sm app-table app-report-table align-middle mb-0">
            <caption class="visually-hidden">Current customer outstanding balances. Amounts in BDT.</caption>
            <thead>
                <tr>
                    <th scope="col">Customer / ID</th>
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
                    <?php ($customer = $customers[$record->party_id] ?? null); ?>
                    <tr>
                        <th scope="row"><?php echo e($customer['name'] ?? 'Customer #'.$record->party_id); ?><small class="d-block fw-normal"><?php echo e($customer['patient_id'] ?? $record->party_id); ?></small></th>
                        <td><?php echo e($customer['mobile_no'] ?? '--'); ?></td>
                        <td class="text-end"><?php echo e($record->invoice_count); ?></td>
                        <td class="text-end"><?php echo e(moneyDecimal($record->invoice_total)); ?></td>
                        <td class="text-end"><?php echo e(moneyDecimal($record->returned)); ?></td>
                        <td class="text-end"><?php echo e(moneyDecimal($record->paid)); ?></td>
                        <td class="text-end"><?php echo e(moneyDecimal($record->refunded)); ?></td>
                        <td class="text-end"><?php echo e(moneyDecimal($record->settlement_discount)); ?></td>
                        <td class="text-end fw-semibold"><?php echo e(moneyDecimal($record->due)); ?></td>
                        <?php if($canCreatePayment): ?>
                            <td class="text-end d-print-none"><a class="btn btn-outline-primary btn-sm text-nowrap" href="<?php echo e(route('pharmacy.sale-payments.create', ['product_type' => $filters['product_type'], 'customer_id' => $record->party_id])); ?>">Collect due</a></td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td class="text-center text-body-secondary py-4" colspan="9">No outstanding customer balances match these filters.</td>
                        <?php if($canCreatePayment): ?><td class="d-print-none"></td><?php endif; ?>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('pharmacy::reports.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\reports\customer-dues.blade.php ENDPATH**/ ?>