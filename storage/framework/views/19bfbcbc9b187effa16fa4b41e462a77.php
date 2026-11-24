<?php
    $followUpDate = getValue('follow_up_date', null, $record->follow_up_date?->format('Y-m-d'));
    $followUpInterval = getValue('follow_up_interval', $record);
    $followUpUnit = getValue('follow_up_interval_unit', $record) ?? 'days';
?>

<section class="app-prescription-note mb-3">
    <div class="app-prescription-section-heading d-flex align-items-center gap-2">
        <h3 class="mb-0">Follow-up</h3>
        <button class="btn btn-outline-primary btn-sm" type="button"
            data-bs-toggle="modal" data-bs-target="#prescription-follow-up-modal"
            aria-label="Add or edit follow-up">
            <i class="fa-solid fa-plus" aria-hidden="true"></i>
        </button>
    </div>
    <ul class="app-prescription-section-preview" id="follow-up-preview"></ul>
</section>

<?php $__env->startPush('prescription-modals'); ?>
    <div class="modal fade app-prescription-section-modal" id="prescription-follow-up-modal"
        tabindex="-1" aria-labelledby="follow-up-modal-heading" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title h5" id="follow-up-modal-heading">Follow-up</h2>
                    <?php echo $__env->make('prescription::prescriptions.preset-search', ['pickerField' => 'follow_up_notes'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label class="visually-hidden" for="prescription-follow-up-interval">Come after</label>
                            <div class="input-group">
                                <input id="prescription-follow-up-interval" name="follow_up_interval"
                                    form="prescription-form" type="number" min="1" max="3650" step="1"
                                    class="<?php echo e(inputClass('follow_up_interval')); ?>"
                                    value="<?php echo e($followUpInterval); ?>" inputmode="numeric"
                                    <?php echo e(inputAttrs('follow_up_interval', 'prescription-follow-up-interval')); ?>>
                                <label class="visually-hidden" for="prescription-follow-up-unit">Interval unit</label>
                                <select id="prescription-follow-up-unit" name="follow_up_interval_unit"
                                    form="prescription-form" class="<?php echo e(inputClass('follow_up_interval_unit', 'form-select')); ?>"
                                    <?php echo e(inputAttrs('follow_up_interval_unit', 'prescription-follow-up-unit')); ?>>
                                    <option value="days" <?php if($followUpUnit === 'days'): echo 'selected'; endif; ?>>দিন</option>
                                    <option value="months" <?php if($followUpUnit === 'months'): echo 'selected'; endif; ?>>মাস</option>
                                </select>
                                <span class="input-group-text">পর</span>
                            </div>
                            <?php $__errorArgs = ['follow_up_interval'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block" id="prescription-follow-up-interval-error">
                                    <?php echo e($message); ?>

                                </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <?php $__errorArgs = ['follow_up_interval_unit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block" id="prescription-follow-up-unit-error">
                                    <?php echo e($message); ?>

                                </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-sm-6">
                            <label class="visually-hidden" for="follow_up_date">Follow-up date</label>
                            <div class="input-group" data-datepicker>
                                <input class="<?php echo e(inputClass('follow_up_date')); ?>" id="follow_up_date"
                                    name="follow_up_date" form="prescription-form" value="<?php echo e($followUpDate); ?>"
                                    inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD"
                                    data-input <?php echo e(inputAttrs('follow_up_date')); ?>>
                                <button class="btn btn-outline-secondary" type="button" data-toggle
                                    aria-controls="follow_up_date" aria-label="Choose follow-up date">
                                    <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
                                </button>
                            </div>
                            <?php $__errorArgs = ['follow_up_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block" id="follow_up_date-error"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <?php echo $__env->make('prescription::prescriptions.preset-picker', [
                        'pickerField' => 'follow_up_notes',
                        'pickerValue' => getValue('follow_up_notes', $record),
                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopPush(); ?>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Prescription\resources\views\prescriptions\follow-up-field.blade.php ENDPATH**/ ?>