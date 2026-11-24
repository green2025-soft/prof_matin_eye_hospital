<?php
    $noteValue = getValue($noteField, $record);
?>

<section class="app-prescription-note mb-3">
    <div class="app-prescription-section-heading d-flex align-items-center gap-2">
        <h3 class="mb-0"><?php echo e($noteLabel); ?></h3>
        <button class="btn btn-outline-primary btn-sm" type="button"
            data-bs-toggle="modal" data-bs-target="#prescription-<?php echo e($noteField); ?>-modal"
            aria-label="Add or edit <?php echo e(strtolower($noteLabel)); ?>">
            <i class="fa-solid fa-plus" aria-hidden="true"></i>
        </button>
    </div>
    <ul class="app-prescription-section-preview" id="<?php echo e($noteField); ?>-preview"></ul>
</section>

<?php $__env->startPush('prescription-modals'); ?>
    <div class="modal fade app-prescription-section-modal prescription-note-modal"
        id="prescription-<?php echo e($noteField); ?>-modal" tabindex="-1"
        aria-labelledby="<?php echo e($noteField); ?>-modal-heading" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title h5" id="<?php echo e($noteField); ?>-modal-heading"><?php echo e($noteLabel); ?></h2>
                    <?php echo $__env->make('prescription::prescriptions.preset-search', ['pickerField' => $noteField], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php echo $__env->make('prescription::prescriptions.preset-picker', [
                        'pickerField' => $noteField,
                        'pickerValue' => $noteValue,
                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php echo $__env->make('prescription::prescriptions.clinical-items', ['pickerField' => $noteField], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopPush(); ?>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules/Prescription\resources/views/prescriptions/note-field.blade.php ENDPATH**/ ?>