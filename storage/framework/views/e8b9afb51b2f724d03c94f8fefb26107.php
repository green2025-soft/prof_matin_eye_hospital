<?php $__env->startSection('report-filters'); ?>
    <?php echo $__env->make('accounting::reports.month-filters', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-context'); ?>
    <span>Month: <strong><?php echo e($periods['current']['label']); ?></strong></span>
    <span><?php echo e($periods['fiscal']['label']); ?>: <?php echo e($periods['fiscal']['from_date']); ?> to <?php echo e($periods['fiscal']['to_date']); ?></span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-summary'); ?>
    <div class="row row-cols-1 row-cols-sm-3 g-3 p-3 border-bottom small">
        <div class="col">Current month receipts<strong class="d-block"><?php echo e(moneyDecimal((string) $totals['current']['receipts'])); ?></strong></div>
        <div class="col">Current month payments<strong class="d-block"><?php echo e(moneyDecimal((string) $totals['current']['payments'])); ?></strong></div>
        <div class="col">Reconciliation difference<strong class="d-block"><?php echo e(moneyDecimal((string) $totals['current']['difference'])); ?></strong></div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-table'); ?>
    <?php if($cashAccounts->isEmpty()): ?>
        <p class="p-3 mb-0 text-body-secondary">Assign Cash in hand or Cash at bank report roles in Chart of Accounts to include your cash ledgers.</p>
    <?php endif; ?>
    <div class="table-responsive">
        <table class="table table-sm app-table app-report-table align-middle mb-0">
            <caption class="visually-hidden">Opening cash, receipts, payments and closing cash, compared with the financial year.</caption>
            <thead><tr><th scope="col">Particulars</th><th class="text-end" scope="col">Current month<br><?php echo e($periods['current']['label']); ?></th><th class="text-end" scope="col">For the period<br><?php echo e($periods['fiscal']['label']); ?></th></tr></thead>
            <tbody>
                <tr class="table-light"><th colspan="3" scope="rowgroup">Receipts</th></tr>
                <tr><th colspan="3" scope="rowgroup">Opening balance</th></tr>
                <?php echo $__env->make('accounting::reports.cash-balance-rows', ['balanceField' => 'opening'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <tr class="fw-semibold"><th scope="row">Total opening balance</th><td class="text-end"><?php echo e(moneyDecimal((string) $balances['current']['opening'])); ?></td><td class="text-end"><?php echo e(moneyDecimal((string) $balances['fiscal']['opening'])); ?></td></tr>
                <tr><th colspan="3" scope="rowgroup">Receipts during the period</th></tr>
                <?php $__empty_1 = true; $__currentLoopData = collect($groups['receipts'])->sortBy('account.code'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr><th class="fw-normal" scope="row"><?php echo e($row['account']->code); ?> - <?php echo e($row['account']->name); ?></th><td class="text-end"><?php echo e(moneyDecimal((string) $row['current'])); ?></td><td class="text-end"><?php echo e(moneyDecimal((string) $row['fiscal'])); ?></td></tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td class="text-body-secondary" colspan="3">No receipts.</td></tr>
                <?php endif; ?>
                <tr class="table-light fw-semibold"><th scope="row">Total receipts</th><td class="text-end"><?php echo e(moneyDecimal((string) $totals['current']['total_receipts'])); ?></td><td class="text-end"><?php echo e(moneyDecimal((string) $totals['fiscal']['total_receipts'])); ?></td></tr>
                <tr class="table-light"><th colspan="3" scope="rowgroup">Payments</th></tr>
                <?php $__empty_1 = true; $__currentLoopData = collect($groups['payments'])->sortBy('account.code'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr><th class="fw-normal" scope="row"><?php echo e($row['account']->code); ?> - <?php echo e($row['account']->name); ?></th><td class="text-end"><?php echo e(moneyDecimal((string) $row['current'])); ?></td><td class="text-end"><?php echo e(moneyDecimal((string) $row['fiscal'])); ?></td></tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td class="text-body-secondary" colspan="3">No payments.</td></tr>
                <?php endif; ?>
                <tr><th colspan="3" scope="rowgroup">Closing balance</th></tr>
                <?php echo $__env->make('accounting::reports.cash-balance-rows', ['balanceField' => 'closing'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <tr class="fw-semibold"><th scope="row">Total closing balance</th><td class="text-end"><?php echo e(moneyDecimal((string) $balances['current']['closing'])); ?></td><td class="text-end"><?php echo e(moneyDecimal((string) $balances['fiscal']['closing'])); ?></td></tr>
                <tr class="table-light fw-semibold"><th scope="row">Total payments</th><td class="text-end"><?php echo e(moneyDecimal((string) $totals['current']['total_payments'])); ?></td><td class="text-end"><?php echo e(moneyDecimal((string) $totals['fiscal']['total_payments'])); ?></td></tr>
            </tbody>
            <tfoot><tr><th scope="row">Reconciliation difference</th><td class="text-end"><?php echo e(moneyDecimal((string) $totals['current']['difference'])); ?></td><td class="text-end"><?php echo e(moneyDecimal((string) $totals['fiscal']['difference'])); ?></td></tr></tfoot>
        </table>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('accounting::reports.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Accounting\resources\views\reports\receipts-payments.blade.php ENDPATH**/ ?>