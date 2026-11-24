<?php if(filled($text)): ?>
    <section class="<?php echo e($className ?? ''); ?>">
        <h3><?php echo e($heading); ?></h3>
        <div class="mb-1">
            <?php echo $__env->make('prescription::prints.clinical-lines', ['text' => $text, 'details' => $details], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </section>
<?php endif; ?>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Prescription\resources\views\prints\selected-notes.blade.php ENDPATH**/ ?>