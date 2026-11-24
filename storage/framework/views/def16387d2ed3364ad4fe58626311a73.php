<?php $__env->startSection('report-account-filter'); ?>
    <div class="col-lg-4">
        <label class="form-label small" for="account_head_id">Ledger account</label>
        <select class="<?php echo e(inputClass('account_head_id', 'form-select')); ?>" id="account_head_id" name="account_head_id"
            data-ajax-select data-url="<?php echo e(route('accounting.ledgers.options')); ?>" data-placeholder="Select ledger account">
            <option value=""></option>
            <?php if($selectedAccount !== null): ?>
                <option value="<?php echo e($selectedAccount->id); ?>" selected><?php echo e($selectedAccount->code); ?> · <?php echo e($selectedAccount->name); ?></option>
            <?php endif; ?>
        </select>
        <?php $__errorArgs = ['account_head_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-extra-filters'); ?>
    <input type="hidden" name="per_page" value="<?php echo e($filters['per_page'] ?? config('pagination.default')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-filters'); ?>
    <?php echo $__env->make('accounting::reports.filters', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-context'); ?>
    <span>Period: <strong><?php echo e($filters['from_date']); ?></strong> to <strong><?php echo e($filters['to_date']); ?></strong></span>
    <span>Account: <?php echo e($selectedAccount?->code); ?> <?php echo e($selectedAccount?->name ?? 'Select an account'); ?></span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-summary'); ?>
    <?php if($selectedAccount !== null): ?>
        <div class="row row-cols-2 row-cols-lg-4 g-3 p-3 border-bottom small">
            <div class="col">Opening balance<strong class="d-block"><?php echo e(moneyDecimal((string) $opening->abs())); ?> <?php echo e($opening->isNegative() ? 'Cr' : 'Dr'); ?></strong></div>
            <div class="col">Period debit<strong class="d-block"><?php echo e(moneyDecimal((string) $periodDebit)); ?></strong></div>
            <div class="col">Period credit<strong class="d-block"><?php echo e(moneyDecimal((string) $periodCredit)); ?></strong></div>
            <div class="col">Closing balance<strong class="d-block"><?php echo e(moneyDecimal((string) $closing->abs())); ?> <?php echo e($closing->isNegative() ? 'Cr' : 'Dr'); ?></strong></div>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-table'); ?>
    <?php if($records === null): ?>
        <p class="text-center text-body-secondary p-4 mb-0">Select a ledger account to view its transactions.</p>
    <?php else: ?>
        <?php ($running = $pageOpening); ?>
        <div class="table-responsive">
            <table class="table table-sm app-table app-report-table align-middle mb-0">
                <thead><tr><th>Date</th><th>Voucher</th><th>Reference / narration</th><th class="text-end">Debit</th><th class="text-end">Credit</th><th class="text-end">Balance</th></tr></thead>
                <tbody>
                    <tr class="table-light"><th colspan="5"><?php echo e($records->currentPage() > 1 ? 'Brought forward from previous page' : 'Opening balance'); ?></th><td class="text-end fw-semibold"><?php echo e(moneyDecimal((string) $running->abs())); ?> <?php echo e($running->isNegative() ? 'Cr' : 'Dr'); ?></td></tr>
                    <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php ($entry = $line->journalEntry); ?>
                        <?php ($running = $running->plus($line->debit)->minus($line->credit)); ?>
                        <tr>
                            <td class="text-nowrap"><?php echo e($entry->entry_date->format('d-m-Y')); ?></td>
                            <td>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view', $entry)): ?>
                                    <a href="<?php echo e(route('accounting.journal-entries.show', $entry)); ?>"><?php echo e($entry->number); ?></a>
                                <?php else: ?>
                                    <?php echo e($entry->number); ?>

                                <?php endif; ?>
                                <small class="d-block text-body-secondary"><?php echo e($entry->voucherType->name); ?></small>
                            </td>
                            <td><?php echo e($entry->reference); ?><small class="d-block text-body-secondary"><?php echo e($line->remarks ?: $entry->narration); ?></small></td>
                            <td class="text-end text-nowrap"><?php echo e(moneyDecimal($line->debit)); ?></td><td class="text-end text-nowrap"><?php echo e(moneyDecimal($line->credit)); ?></td>
                            <td class="text-end text-nowrap"><?php echo e(moneyDecimal((string) $running->abs())); ?> <?php echo e($running->isNegative() ? 'Cr' : 'Dr'); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td class="text-center text-body-secondary py-4" colspan="6">No transactions in this period.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-pagination'); ?>
    <?php if($records !== null): ?>
        <?php echo $__env->make('core::partials.pagination', ['paginator' => $records, 'filters' => $filters], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('accounting::reports.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Accounting\resources\views\reports\ledger.blade.php ENDPATH**/ ?>