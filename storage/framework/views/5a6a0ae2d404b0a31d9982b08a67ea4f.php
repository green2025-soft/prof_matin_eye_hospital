<?php
    $showRoom = $sourceService === 'investigation';
    $showEye = $sourceService !== 'consultation';
    $columnCount = 3 + (int) $showRoom + (int) $showEye;
?>

<?php $__env->startSection('invoice-content'); ?>
    <div class="table-responsive">
        <table class="table app-clinical-invoice-table">
            <caption class="visually-hidden">Refunded <?php echo e(strtolower($sourceServiceLabel)); ?> items and their selected net values</caption>
            <thead>
                <tr>
                    <th class="app-clinical-invoice-number" scope="col">No.</th>
                    <th scope="col">Refunded <?php echo e($sourceServiceLabel); ?></th>
                    <?php if($showRoom): ?>
                        <th class="app-clinical-invoice-room" scope="col">Room</th>
                    <?php endif; ?>
                    <?php if($showEye): ?>
                        <th class="app-clinical-invoice-eye" scope="col">Eye</th>
                    <?php endif; ?>
                    <th class="app-clinical-invoice-amount text-end" scope="col">Selected value (Tk)</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $lines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($loop->iteration); ?></td>
                        <td class="text-break"><?php echo e($line['name']); ?></td>
                        <?php if($showRoom): ?>
                            <td><?php echo e(filled($line['room']) ? $line['room'] : '-'); ?></td>
                        <?php endif; ?>
                        <?php if($showEye): ?>
                            <td><?php echo e($line['eye'] ?? '-'); ?></td>
                        <?php endif; ?>
                        <td class="text-end text-nowrap"><?php echo e($line['amount']); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td class="text-center text-body-secondary" colspan="<?php echo e($columnCount); ?>">No refunded items</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="row g-4 app-clinical-invoice-summary">
        <?php echo $__env->make('hospital::invoices.notes', ['wordsLabel' => 'Refund value in words'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <section class="col-12 col-sm-6 app-clinical-invoice-totals" aria-labelledby="clinical-invoice-totals-title">
            <h2 class="app-clinical-invoice-section-title" id="clinical-invoice-totals-title">Refund Summary</h2>
            <dl class="mb-0">
                <div class="app-clinical-invoice-total"><dt>Sales Value</dt><dd><?php echo e($refundCreditAmount); ?></dd></div>
                <div><dt>Due Adjustment</dt><dd><?php echo e($refundDueAdjustment); ?></dd></div>
                <div class="app-clinical-invoice-refund-total"><dt>Cash Refund</dt><dd><?php echo e($refundAmount); ?></dd></div>
            </dl>
        </section>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('hospital::invoices.show', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Hospital\resources\views\refunds\invoice.blade.php ENDPATH**/ ?>