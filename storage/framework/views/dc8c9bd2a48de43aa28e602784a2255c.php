{
    "results": [
        <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $serviceLabel = ucfirst($invoice->service);
                $labelParts = array_filter(
                    [$invoice->invoice_no, $invoice->patient_name, $invoice->mobile_no],
                    fn ($value): bool => filled($value),
                );
                $labelParts[] = $serviceLabel;
                $option = [
                    ...(array) $invoice,
                    'id' => $invoice->service.':'.$invoice->source_id,
                    'text' => implode(' — ', $labelParts),
                    'service_label' => $serviceLabel,
                ];
            ?>
            <?php echo json_encode($option, 15, 512) ?><?php if (! ($loop->last)): ?>,<?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    ],
    "pagination": {
        "more": <?php echo json_encode($records->hasMorePages(), 15, 512) ?>
    }
}
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Hospital\resources\views\selectors\invoices.blade.php ENDPATH**/ ?>