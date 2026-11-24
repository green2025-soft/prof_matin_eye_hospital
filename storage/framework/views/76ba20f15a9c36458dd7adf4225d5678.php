<ul class="list-group list-group-flush mb-0" aria-label="<?php echo e($caption); ?>">
    <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <li class="list-group-item bg-transparent p-0">
            <a class="app-quick-link align-items-start" href="<?php echo e(route($showRoute, $record->id)); ?>">
                <span class="d-flex flex-column flex-grow-1 overflow-hidden">
                    <span class="fw-semibold text-break"><?php echo e($record->patient?->name ?? 'Patient unavailable'); ?></span>
                    <span class="small text-body-secondary text-break"><?php echo e($record->rx_number ?? '#'.$record->id); ?> · <?php echo e($record->patient?->patient_id); ?></span>
                    <span class="small text-body-secondary text-break"><?php echo e($record->doctor?->name ?? 'Doctor unavailable'); ?></span>
                </span>
                <span class="d-flex flex-column align-items-end gap-1 flex-shrink-0">
                    <time class="small text-body-secondary" datetime="<?php echo e($record->prescription_date->toDateString()); ?>"><?php echo e($record->prescription_date->format('d M Y')); ?></time>
                    <span class="small fw-semibold">View <i class="fa-solid fa-arrow-right ms-1" aria-hidden="true"></i></span>
                </span>
            </a>
        </li>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <li class="app-dashboard-empty">
            <i class="fa-solid fa-file-prescription" aria-hidden="true"></i>
            <h3>No prescriptions yet</h3>
            <p>Saved prescriptions will appear here.</p>
        </li>
    <?php endif; ?>
</ul>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules/Prescription\resources/views/dashboard/recent.blade.php ENDPATH**/ ?>