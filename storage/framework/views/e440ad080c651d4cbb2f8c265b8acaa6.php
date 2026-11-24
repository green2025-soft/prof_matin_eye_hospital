<div class="app-clinical-invoice-total">
    <dt>Invoice total</dt>
    <dd><?php echo e(moneyDecimal($entry->total)); ?></dd>
</div>
<div>
    <dt>Paid</dt>
    <dd><?php echo e(moneyDecimal($entry->paid)); ?></dd>
</div>
<?php if($entry->returned > 0): ?>
    <div>
        <dt>Returned value</dt>
        <dd><?php echo e(moneyDecimal($entry->returned)); ?></dd>
    </div>
<?php endif; ?>
<?php if($entry->refunded > 0): ?>
    <div>
        <dt>Cash refunded</dt>
        <dd><?php echo e(moneyDecimal($entry->refunded)); ?></dd>
    </div>
<?php endif; ?>
<?php if($entry->settlement_discount > 0): ?>
    <div>
        <dt>Settlement discount</dt>
        <dd><?php echo e(moneyDecimal($entry->settlement_discount)); ?></dd>
    </div>
<?php endif; ?>
<div class="app-clinical-invoice-refund-total">
    <dt>Due</dt>
    <dd><?php echo e(moneyDecimal($outstandingMinor)); ?></dd>
</div>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules/Pharmacy\resources/views/entries/partials/invoice-totals.blade.php ENDPATH**/ ?>