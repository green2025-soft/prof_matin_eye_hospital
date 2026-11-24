<div class="app-prescription-preset-search position-relative d-flex align-items-center justify-content-center gap-2 mx-auto">
    <?php echo $__env->make('prescription::prescriptions.picker-search', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php if($canCreateOptions): ?>
        <button class="btn btn-outline-primary btn-sm prescription-preset-save" type="button" hidden
            data-picker="#<?php echo e($pickerField); ?>-picker"
            data-url="<?php echo e(route('prescription.options.store')); ?>" data-section="<?php echo e($pickerField); ?>">
            <i class="fa-solid fa-circle-plus" aria-hidden="true"></i> Create
        </button>
    <?php endif; ?>
</div>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules/Prescription\resources/views/prescriptions/preset-search.blade.php ENDPATH**/ ?>