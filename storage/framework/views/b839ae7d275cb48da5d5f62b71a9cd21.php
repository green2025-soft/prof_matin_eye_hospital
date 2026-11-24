{
    "results": [
        <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo $__env->make('hospital::selectors.patient', ['birthDate' => $patient->date_of_birth?->toDateString()], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php if (! ($loop->last)): ?>,<?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    ],
    "pagination": {
        "more": <?php echo json_encode($records->hasMorePages(), 15, 512) ?>
    }
}
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Hospital\resources\views\selectors\patients.blade.php ENDPATH**/ ?>