{
    "results": [
        <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doctor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            {
                "id": <?php echo json_encode($doctor->id, 15, 512) ?>,
                "doctor_id": <?php echo json_encode($doctor->doctor_id, 15, 512) ?>,
                "name": <?php echo json_encode($doctor->name, 15, 512) ?>,
                "consultation_fee": <?php echo json_encode($doctor->consultation_fee, 15, 512) ?>,
                "follow_up_fee": <?php echo json_encode($doctor->follow_up_fee, 15, 512) ?>,
                "text": <?php echo json_encode(($doctor->doctor_id ?: $doctor->id).' — '.$doctor->name, 15, 512) ?>
            }<?php if (! ($loop->last)): ?>,<?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    ],
    "pagination": {
        "more": <?php echo json_encode($records->hasMorePages(), 15, 512) ?>
    }
}
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Hospital\resources\views\selectors\doctors.blade.php ENDPATH**/ ?>