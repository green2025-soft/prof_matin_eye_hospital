<?php $__env->startSection('report-filters'); ?>
    <?php echo $__env->make('accounting::reports.month-filters', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-context'); ?>
    <span>Period: <strong><?php echo e($period['from_date']); ?></strong> to <strong><?php echo e($period['to_date']); ?></strong></span>
    <span>Closing balances as at <?php echo e($period['to_date']); ?></span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-summary'); ?>
    <div class="row row-cols-2 row-cols-lg-4 g-3 p-3 border-bottom small">
        <div class="col">Period debit<strong class="d-block"><?php echo e(moneyDecimal((string) $periodDebit)); ?></strong></div>
        <div class="col">Period credit<strong class="d-block"><?php echo e(moneyDecimal((string) $periodCredit)); ?></strong></div>
        <div class="col">Closing debit<strong class="d-block"><?php echo e(moneyDecimal((string) $totalDebit)); ?></strong></div>
        <div class="col">Closing credit<strong class="d-block"><?php echo e(moneyDecimal((string) $totalCredit)); ?></strong></div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-table'); ?>
    <div class="table-responsive">
        <table class="table table-sm app-table app-report-table align-middle mb-0">
            <thead><tr><th>Code</th><th>Account</th><th class="text-end">Opening Dr</th><th class="text-end">Opening Cr</th><th class="text-end">Period Dr</th><th class="text-end">Period Cr</th><th class="text-end">Closing Dr</th><th class="text-end">Closing Cr</th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($account->code); ?></td><th scope="row"><?php echo e($account->name); ?></th>
                        <td class="text-end"><?php echo e(moneyDecimal($account->opening_debit)); ?></td><td class="text-end"><?php echo e(moneyDecimal($account->opening_credit)); ?></td>
                        <td class="text-end"><?php echo e(moneyDecimal($account->period_debit ?? 0)); ?></td><td class="text-end"><?php echo e(moneyDecimal($account->period_credit ?? 0)); ?></td>
                        <td class="text-end"><?php echo e(moneyDecimal($account->closing_debit)); ?></td><td class="text-end"><?php echo e(moneyDecimal($account->closing_credit)); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td class="text-center text-body-secondary py-4" colspan="8">No ledger activity through this date.</td></tr>
                <?php endif; ?>
            </tbody>
            <tfoot><tr><th colspan="2">Total</th><th class="text-end"><?php echo e(moneyDecimal((string) $openingDebit)); ?></th><th class="text-end"><?php echo e(moneyDecimal((string) $openingCredit)); ?></th><th class="text-end"><?php echo e(moneyDecimal((string) $periodDebit)); ?></th><th class="text-end"><?php echo e(moneyDecimal((string) $periodCredit)); ?></th><th class="text-end"><?php echo e(moneyDecimal((string) $totalDebit)); ?></th><th class="text-end"><?php echo e(moneyDecimal((string) $totalCredit)); ?></th></tr></tfoot>
        </table>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('accounting::reports.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Accounting\resources\views\reports\trial-balance.blade.php ENDPATH**/ ?>