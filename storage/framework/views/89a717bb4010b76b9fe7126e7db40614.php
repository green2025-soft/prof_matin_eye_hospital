{
    "results": [
        <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reference): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            {
                "id": <?php echo json_encode($reference->id, 15, 512) ?>,
                "reference_id": <?php echo json_encode($reference->reference_id, 15, 512) ?>,
                "name": <?php echo json_encode($reference->name, 15, 512) ?>,
                "contact_no": <?php echo json_encode($reference->contact_no, 15, 512) ?>,
                "text": <?php echo json_encode($reference->reference_id.' — '.$reference->name.' ('.$reference->contact_no.')', 15, 512) ?>
            }<?php if (! ($loop->last)): ?>,<?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    ],
    "pagination": {
        "more": <?php echo json_encode($records->hasMorePages(), 15, 512) ?>
    }
}
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Hospital\resources\views\selectors\references.blade.php ENDPATH**/ ?>