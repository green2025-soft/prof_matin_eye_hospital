<?php if($entry->paymentType): ?>
    <dl class="app-clinical-invoice-meta mb-0">
        <div>
            <dt>Payment Type</dt>
            <dd><?php echo e($entry->paymentType->name); ?></dd>
        </div>
    </dl>
<?php endif; ?>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\entries\partials\invoice-payment-meta.blade.php ENDPATH**/ ?>