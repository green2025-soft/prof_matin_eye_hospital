<?php
    $prefix = '';
    if ($prescription->follow_up_interval !== null) {
        $unit = $prescription->follow_up_interval_unit === 'months' ? 'মাস' : 'দিন';
        $prefix = $prescription->follow_up_interval.' '.$unit.' পর';
    } elseif ($prescription->follow_up_date !== null) {
        $prefix = $prescription->follow_up_date->format('d-m-Y').' তারিখে';
    }
    $followUpLines = collect(preg_split('/\r?\n/', (string) $prescription->follow_up_notes))
        ->filter('filled');
?>

<?php if(filled($prefix) || $followUpLines->isNotEmpty()): ?>
    <section>
        <h3>Follow-up</h3>
        <div class="mb-1">
            <?php $__empty_1 = true; $__currentLoopData = $followUpLines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <p class="app-prescription-text mb-0"><?php echo e(trim($prefix.' '.trim($line))); ?></p>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="app-prescription-text mb-0"><?php echo e($prefix); ?></p>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Prescription\resources\views\prints\follow-up.blade.php ENDPATH**/ ?>