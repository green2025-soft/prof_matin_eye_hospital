<?php $__env->startSection('report-filters'); ?>
    <?php echo $__env->make('accounting::reports.month-filters', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-context'); ?>
    <span>As at <strong><?php echo e($periods['current']['to_date']); ?></strong></span>
    <span>Compared with the financial-year closing balance at <strong><?php echo e($periods['previous_fiscal']['to_date']); ?></strong></span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-summary'); ?>
    <div class="row row-cols-1 row-cols-sm-3 g-3 p-3 border-bottom small">
        <div class="col">Assets<strong class="d-block"><?php echo e(moneyDecimal((string) $assets)); ?></strong></div>
        <div class="col">Liabilities &amp; equity<strong class="d-block"><?php echo e(moneyDecimal((string) $liabilitiesAndEquity)); ?></strong></div>
        <div class="col">Difference<strong class="d-block"><?php echo e(moneyDecimal((string) $difference)); ?></strong></div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-table'); ?>
    <div class="table-responsive">
        <table class="table table-sm app-table app-report-table align-middle mb-0">
            <thead>
                <tr>
                    <th scope="col" rowspan="2">Particulars</th>
                    <th scope="col" rowspan="2" class="text-center">Notes</th>
                    <th scope="colgroup" colspan="2" class="text-center">Amount (BDT)</th>
                </tr>
                <tr>
                    <th scope="col" class="text-end"><?php echo e($periods['current']['to_date']); ?></th>
                    <th scope="col" class="text-end"><?php echo e($periods['previous_fiscal']['to_date']); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="table-light"><th scope="rowgroup" colspan="4"><?php echo e($section['label']); ?></th></tr>
                    <?php $__empty_1 = true; $__currentLoopData = $rows->where('account.type', $type); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="<?php echo e($row['note'] === null ? 'fw-semibold' : ''); ?>">
                            <th scope="row" class="ps-<?php echo e($row['account']->depth); ?>"><?php echo e($row['account']->code); ?> &ndash; <?php echo e($row['account']->name); ?></th>
                            <td class="text-center">
                                <?php if($row['note'] !== null): ?>
                                    <a class="link-primary" href="#balance-note-<?php echo e($row['account']->id); ?>" data-bs-toggle="modal"
                                        aria-label="Note <?php echo e($row['note']); ?>: <?php echo e($row['account']->name); ?>"><?php echo e($row['note']); ?></a>
                                <?php endif; ?>
                            </td>
                            <td class="text-end text-nowrap"><?php echo e(moneyDecimal((string) $row['current'])); ?></td>
                            <td class="text-end text-nowrap"><?php echo e(moneyDecimal((string) $row['comparison'])); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td class="text-body-secondary py-3" colspan="4">No <?php echo e($type); ?> ledger activity through this month.</td></tr>
                    <?php endif; ?>
                    <?php if($type === 'equity'): ?>
                        <tr>
                            <th scope="row" colspan="2" class="ps-2">Unclosed accumulated profit / loss</th>
                            <td class="text-end text-nowrap"><?php echo e(moneyDecimal((string) $earnings)); ?></td>
                            <td class="text-end text-nowrap"><?php echo e(moneyDecimal((string) $comparisonEarnings)); ?></td>
                        </tr>
                    <?php endif; ?>
                    <tr class="fw-semibold border-top">
                        <th scope="row" colspan="2">Total <?php echo e($section['label']); ?></th>
                        <td class="text-end text-nowrap"><?php echo e(moneyDecimal((string) $section['current'])); ?></td>
                        <td class="text-end text-nowrap"><?php echo e(moneyDecimal((string) $section['comparison'])); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
            <tfoot>
                <tr><th scope="row" colspan="2">Total liabilities &amp; equity</th><th class="text-end text-nowrap"><?php echo e(moneyDecimal((string) $liabilitiesAndEquity)); ?></th><th class="text-end text-nowrap"><?php echo e(moneyDecimal((string) $comparisonLiabilitiesAndEquity)); ?></th></tr>
                <tr><th scope="row" colspan="2">Difference</th><th class="text-end text-nowrap"><?php echo e(moneyDecimal((string) $difference)); ?></th><th class="text-end text-nowrap"><?php echo e(moneyDecimal((string) $comparisonDifference)); ?></th></tr>
            </tfoot>
        </table>
    </div>
    <p class="small text-body-secondary m-3">Balances include all unvoided ledger history through each stated date. Group totals include their subaccounts; unclosed earnings are shown separately within equity.</p>

    <?php $__currentLoopData = $rows->whereNotNull('note'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="modal fade d-print-none" id="balance-note-<?php echo e($row['account']->id); ?>" tabindex="-1"
            aria-labelledby="balance-note-title-<?php echo e($row['account']->id); ?>" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title fs-5" id="balance-note-title-<?php echo e($row['account']->id); ?>">Note <?php echo e($row['note']); ?>: <?php echo e($row['account']->name); ?></h2>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="small text-body-secondary"><?php echo e($row['account']->code); ?> &ndash; Ledger account breakdown. Amounts in BDT.</p>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead><tr><th scope="col">Account</th><th scope="col" class="text-end"><?php echo e($periods['current']['to_date']); ?></th><th scope="col" class="text-end"><?php echo e($periods['previous_fiscal']['to_date']); ?></th></tr></thead>
                                <tbody>
                                    <?php $__currentLoopData = $row['accounts']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <th scope="row"><?php echo e($account->code); ?> &ndash; <?php echo e($account->name); ?></th>
                                            <td class="text-end text-nowrap"><?php echo e(moneyDecimal((string) $account->current_amount)); ?></td>
                                            <td class="text-end text-nowrap"><?php echo e(moneyDecimal((string) $account->comparison_amount)); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                                <tfoot><tr><th scope="row">Total</th><th class="text-end"><?php echo e(moneyDecimal((string) $row['current'])); ?></th><th class="text-end"><?php echo e(moneyDecimal((string) $row['comparison'])); ?></th></tr></tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer"><button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button></div>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('accounting::reports.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Accounting\resources\views\reports\balance-sheet.blade.php ENDPATH**/ ?>