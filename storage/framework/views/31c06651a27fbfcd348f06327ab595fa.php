<?php $__env->startSection('report-extra-filters'); ?>
    <input type="hidden" name="per_page" value="<?php echo e($filters['per_page'] ?? config('pagination.default')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-filters'); ?>
    <?php echo $__env->make('accounting::reports.filters', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-context'); ?>
    <span>Period: <strong><?php echo e($filters['from_date']); ?></strong> to <strong><?php echo e($filters['to_date']); ?></strong></span>
    <span>Cash and bank movements, including transfers between these accounts.</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-summary'); ?>
    <div class="row row-cols-2 row-cols-lg-4 g-3 p-3 border-bottom small">
        <div class="col">Opening cash<strong class="d-block"><?php echo e(moneyDecimal((string) $opening['cash'])); ?></strong></div>
        <div class="col">Opening bank<strong class="d-block"><?php echo e(moneyDecimal((string) $opening['bank'])); ?></strong></div>
        <div class="col">Closing cash<strong class="d-block"><?php echo e(moneyDecimal((string) $closing['cash'])); ?></strong></div>
        <div class="col">Closing bank<strong class="d-block"><?php echo e(moneyDecimal((string) $closing['bank'])); ?></strong></div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-table'); ?>
    <?php
        $running = $pageOpening;
    ?>
    <div class="table-responsive">
        <table class="table table-sm app-table app-report-table align-middle mb-0">
            <thead>
                <tr><th>Date</th><th>Account</th><th>Particulars</th><th>Voucher</th><th class="text-end">Debit</th><th class="text-end">Credit</th><th class="text-end">Cash</th><th class="text-end">Bank</th></tr>
            </thead>
            <tbody>
                <tr class="table-light">
                    <th colspan="6"><?php echo e($records->currentPage() > 1 ? 'Brought forward from previous page' : 'Opening balance'); ?></th>
                    <td class="text-end fw-semibold"><?php echo e(moneyDecimal((string) $running['cash'])); ?></td>
                    <td class="text-end fw-semibold"><?php echo e(moneyDecimal((string) $running['bank'])); ?></td>
                </tr>
                <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $entry = $line->journalEntry;
                        $account = $cashAccounts->get($line->account_head_id);
                        $running[$account->cash_kind] = $running[$account->cash_kind]->plus($line->debit)->minus($line->credit);
                    ?>
                    <tr>
                        <td class="text-nowrap"><?php echo e($entry->entry_date->format('d-m-Y')); ?></td>
                        <th scope="row"><?php echo e($account->code); ?><small class="d-block fw-normal"><?php echo e($account->name); ?></small></th>
                        <td><?php echo e($line->remarks ?: $entry->narration); ?><small class="d-block text-body-secondary"><?php echo e($entry->reference); ?></small></td>
                        <td class="text-nowrap"><?php echo e($entry->number); ?></td>
                        <td class="text-end text-nowrap"><?php echo e(moneyDecimal($line->debit)); ?></td>
                        <td class="text-end text-nowrap"><?php echo e(moneyDecimal($line->credit)); ?></td>
                        <td class="text-end text-nowrap"><?php echo e(moneyDecimal((string) $running['cash'])); ?></td>
                        <td class="text-end text-nowrap"><?php echo e(moneyDecimal((string) $running['bank'])); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td class="text-center text-body-secondary py-4" colspan="8">No cash or bank transactions in this period.</td></tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4">Period totals / closing balance</th>
                    <th class="text-end text-nowrap"><?php echo e(moneyDecimal((string) $periodDebit)); ?></th>
                    <th class="text-end text-nowrap"><?php echo e(moneyDecimal((string) $periodCredit)); ?></th>
                    <th class="text-end text-nowrap"><?php echo e(moneyDecimal((string) $closing['cash'])); ?></th>
                    <th class="text-end text-nowrap"><?php echo e(moneyDecimal((string) $closing['bank'])); ?></th>
                </tr>
            </tfoot>
        </table>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-pagination'); ?>
    <?php echo $__env->make('core::partials.pagination', ['paginator' => $records, 'filters' => $filters], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('accounting::reports.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Accounting\resources\views\reports\cash-bank-book.blade.php ENDPATH**/ ?>