<?php $__env->startSection('report-filters'); ?>
    <?php echo $__env->make('accounting::reports.month-filters', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-context'); ?>
    <span>Month ended: <strong><?php echo e($periods['current']['to_date']); ?></strong></span>
    <span>Financial year begins 1 July. Opening balances precede each period.</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-table'); ?>
    <div class="table-responsive">
        <table class="table table-sm app-table app-report-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Particulars</th>
                    <?php $__currentLoopData = $periods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $period): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <th class="text-end"><?php echo e($period['label']); ?><small class="d-block fw-normal"><?php echo e($period['from_date']); ?> to <?php echo e($period['to_date']); ?></small></th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tr>
            </thead>
            <tbody>
                <tr class="table-light">
                    <th>Opening equity</th>
                    <?php $__currentLoopData = $periods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $period): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <td class="text-end fw-semibold"><?php echo e(moneyDecimal((string) $balances[$key]['opening'])); ?></td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tr>
                <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <th scope="row"><?php echo e($account->code); ?> · <?php echo e($account->name); ?></th>
                        <?php $__currentLoopData = $periods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $period): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <td class="text-end"><?php echo e(moneyDecimal((string) $balances[$key]['movements']->get($account->id, 0))); ?></td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <th scope="row">Change in unclosed income over expenditure</th>
                    <?php $__currentLoopData = $periods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $period): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <td class="text-end"><?php echo e(moneyDecimal((string) $balances[$key]['unclosed'])); ?></td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th>Closing equity</th>
                    <?php $__currentLoopData = $periods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $period): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <th class="text-end"><?php echo e(moneyDecimal((string) $balances[$key]['closing'])); ?></th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tr>
            </tfoot>
        </table>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('accounting::reports.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Accounting\resources\views\reports\changes-in-equity.blade.php ENDPATH**/ ?>