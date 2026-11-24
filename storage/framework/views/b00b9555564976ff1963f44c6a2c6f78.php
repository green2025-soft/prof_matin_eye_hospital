<div class="app-tags">
    <?php $__empty_1 = true; $__currentLoopData = $labels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <span class="app-tag"><?php echo e($label); ?></span>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <span class="small text-body-secondary"><?php echo e(($empty ?? '') !== '' ? $empty : '—'); ?></span>
    <?php endif; ?>
</div>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\app\Providers/../../resources/views/partials/tags.blade.php ENDPATH**/ ?>