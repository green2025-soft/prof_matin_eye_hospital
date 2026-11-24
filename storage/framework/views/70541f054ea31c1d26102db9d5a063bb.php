<?php if($creditAmount !== '0.00'): ?>
    <h3 class="app-clinical-invoice-section-title mt-3">Refund adjustments</h3>
    <dl class="mb-0">
        <div><dt>Refunded value</dt><dd><?php echo e($creditAmount); ?></dd></div>
        <div><dt>Due adjusted</dt><dd><?php echo e($dueAdjusted); ?></dd></div>
        <div><dt>Cash refunded</dt><dd><?php echo e($cashRefunded); ?></dd></div>
    </dl>
<?php endif; ?>
<?php if($collectedAmount !== '0.00'): ?>
    <h3 class="app-clinical-invoice-section-title mt-3">Due collections</h3>
    <dl class="mb-0">
        <div><dt>Received amount</dt><dd><?php echo e($collectedAmount); ?></dd></div>
    </dl>
<?php endif; ?>
<?php if($hasAdjustments): ?>
    <h3 class="app-clinical-invoice-section-title mt-3">Current balance</h3>
    <dl class="mb-0">
        <div class="app-clinical-invoice-total"><dt>Current payable</dt><dd><?php echo e($remainingPayable); ?></dd></div>
        <div><dt>Net paid</dt><dd><?php echo e($remainingPaid); ?></dd></div>
        <div><dt>Current due</dt><dd><?php echo e($remainingDue); ?></dd></div>
    </dl>
<?php endif; ?>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Hospital\resources\views\invoices\balance.blade.php ENDPATH**/ ?>