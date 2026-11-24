<?php $__env->startSection('report-filters'); ?>
    <?php echo $__env->make('accounting::reports.month-filters', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-context'); ?>
    <span>Month: <strong><?php echo e($periods['current']['label']); ?></strong></span>
    <span><?php echo e($periods['fiscal']['label']); ?>: <?php echo e($periods['fiscal']['from_date']); ?> to <?php echo e($periods['fiscal']['to_date']); ?></span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-summary'); ?>
    <div class="row row-cols-1 row-cols-sm-3 g-3 p-3 border-bottom small">
        <div class="col">Current month net cash<strong class="d-block"><?php echo e(moneyDecimal((string) $totals['current']['net_change'])); ?></strong></div>
        <div class="col">Financial year net cash<strong class="d-block"><?php echo e(moneyDecimal((string) $totals['fiscal']['net_change'])); ?></strong></div>
        <div class="col">Reconciliation difference<strong class="d-block"><?php echo e(moneyDecimal((string) $totals['current']['difference'])); ?></strong></div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-table'); ?>
    <?php if($cashAccounts->isEmpty()): ?>
        <p class="p-3 mb-0 text-body-secondary">Assign Cash in hand or Cash at bank report roles in Chart of Accounts to include your cash ledgers.</p>
    <?php endif; ?>
    <div class="table-responsive">
        <table class="table table-sm app-table app-report-table align-middle mb-0">
            <caption class="visually-hidden">Cash flows from operating, investing and financing activities, compared with the financial year.</caption>
            <thead><tr><th scope="col">Particulars</th><th class="text-end" scope="col">Current month<br><?php echo e($periods['current']['label']); ?></th><th class="text-end" scope="col">For the period<br><?php echo e($periods['fiscal']['label']); ?></th></tr></thead>
            <tbody>
                <?php $__currentLoopData = [
                    'operating' => 'Operating activities',
                    'investing' => 'Investing activities',
                    'financing' => 'Financing activities',
                    'unclassified' => 'Unclassified cash movements — review required',
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($activity === 'unclassified' && $groups[$activity] === []) continue; ?>
                    <tr class="table-light"><th colspan="3" scope="rowgroup"><?php echo e($label); ?></th></tr>
                    <?php $__empty_1 = true; $__currentLoopData = collect($groups[$activity])->sortBy('account.code'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <th class="fw-normal" scope="row">
                                <?php if($row['account'] !== null): ?>
                                    <?php echo e($row['account']->code); ?> - <?php echo e($row['account']->name); ?>

                                <?php else: ?>
                                    Allocation required: voucher <?php echo e($row['entry']->number); ?>

                                <?php endif; ?>
                            </th>
                            <td class="text-end text-nowrap"><?php echo e(moneyDecimal((string) $row['current'])); ?></td>
                            <td class="text-end text-nowrap"><?php echo e(moneyDecimal((string) $row['fiscal'])); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td class="text-body-secondary" colspan="3">No cash movement.</td></tr>
                    <?php endif; ?>
                    <tr class="fw-semibold"><th scope="row">Net cash from <?php echo e(strtolower($label)); ?></th><td class="text-end"><?php echo e(moneyDecimal((string) $totals['current'][$activity])); ?></td><td class="text-end"><?php echo e(moneyDecimal((string) $totals['fiscal'][$activity])); ?></td></tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <tr class="table-light fw-semibold"><th scope="row">Net increase / (decrease) in cash & bank</th><td class="text-end"><?php echo e(moneyDecimal((string) $totals['current']['net_change'])); ?></td><td class="text-end"><?php echo e(moneyDecimal((string) $totals['fiscal']['net_change'])); ?></td></tr>
                <tr class="table-light"><th colspan="3" scope="rowgroup">Opening cash & bank</th></tr>
                <?php echo $__env->make('accounting::reports.cash-balance-rows', ['balanceField' => 'opening'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <tr class="fw-semibold"><th scope="row">Total opening cash & bank</th><td class="text-end"><?php echo e(moneyDecimal((string) $balances['current']['opening'])); ?></td><td class="text-end"><?php echo e(moneyDecimal((string) $balances['fiscal']['opening'])); ?></td></tr>
                <tr class="table-light"><th colspan="3" scope="rowgroup">Closing cash & bank</th></tr>
                <?php echo $__env->make('accounting::reports.cash-balance-rows', ['balanceField' => 'closing'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <tr class="fw-semibold"><th scope="row">Total closing cash & bank</th><td class="text-end"><?php echo e(moneyDecimal((string) $balances['current']['closing'])); ?></td><td class="text-end"><?php echo e(moneyDecimal((string) $balances['fiscal']['closing'])); ?></td></tr>
            </tbody>
            <tfoot><tr><th scope="row">Reconciliation difference</th><td class="text-end"><?php echo e(moneyDecimal((string) $totals['current']['difference'])); ?></td><td class="text-end"><?php echo e(moneyDecimal((string) $totals['fiscal']['difference'])); ?></td></tr></tfoot>
        </table>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('accounting::reports.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Accounting\resources\views\reports\cash-flow.blade.php ENDPATH**/ ?>