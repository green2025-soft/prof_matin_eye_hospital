<div class="col-12 col-sm-6 app-clinical-invoice-notes">
    <?php echo $__env->yieldContent('invoice-notes'); ?>
    <?php if($refundCause !== null): ?>
        <h2 class="app-clinical-invoice-section-title">Refund cause</h2>
        <p class="app-clinical-invoice-note"><?php echo e($refundCause); ?></p>
    <?php endif; ?>
    <?php if(filled($remarks)): ?>
        <h2 class="app-clinical-invoice-section-title">Remarks</h2>
        <p class="app-clinical-invoice-note"><?php echo e($remarks); ?></p>
    <?php endif; ?>
</div>

<div class="col-12 col-sm-6 app-clinical-invoice-payment-stamp">
    <span><?php echo e($status); ?></span>
</div>

<div class="col-12 app-clinical-invoice-words">
    <h2 class="app-clinical-invoice-section-title"><?php echo e($wordsLabel); ?></h2>
    <p class="app-clinical-invoice-note mb-0"><?php echo e($amountWords); ?></p>
</div>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Hospital\resources\views\invoices\notes.blade.php ENDPATH**/ ?>