<?php if($details->isEmpty()): ?>
    <p class="app-prescription-text mb-0"><?php echo e(trim((string) $text)); ?></p>
<?php else: ?>
    <?php
        $detailsByName = $details->keyBy('name');
    ?>
    <?php $__currentLoopData = str($text)->explode("\n"); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $name = trim($line);
            $detail = $detailsByName->get($name);
            $duration = '';
            if (($detail?->duration_unit ?? null) === 'long_ago') {
                $duration = 'Long time ago';
            } elseif (filled($detail?->duration ?? null)) {
                $duration = trim('for '.$detail->duration.' '.($detail->duration_unit ?? ''));
            }
        ?>
        <?php if($name === '') continue; ?>
        <p class="app-prescription-text mb-0"><?php echo e(trim($name.' '.$duration)); ?></p>
        <?php if(filled($detail?->notes ?? null)): ?>
            <p class="app-prescription-text mb-0"><?php echo e(trim($detail->notes)); ?></p>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Prescription\resources\views\prints\clinical-lines.blade.php ENDPATH**/ ?>