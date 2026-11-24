<div class="col-6 col-lg-3">
    <label class="form-label small" for="line_<?php echo e($lineIndex); ?>_batch">Batch no.</label>
    <input class="<?php echo e(inputClass('lines.'.$lineIndex.'.batch_no', 'form-control')); ?>" id="line_<?php echo e($lineIndex); ?>_batch" name="lines[<?php echo e($lineIndex); ?>][batch_no]" value="<?php echo e($lineValues['batch_no'] ?? ''); ?>" maxlength="80" <?php echo e(inputAttrs('lines.'.$lineIndex.'.batch_no', 'line_'.$lineIndex.'_batch')); ?>>
    <?php $__errorArgs = ['lines.'.$lineIndex.'.batch_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="invalid-feedback d-block" id="line_<?php echo e($lineIndex); ?>_batch-error">
            <?php echo e($message); ?>

        </div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>
<div class="col-6 col-lg-3">
    <label class="form-label small" for="line_<?php echo e($lineIndex); ?>_expiry">Expiry date</label>
    <div class="input-group" data-datepicker>
        <input class="<?php echo e(inputClass('lines.'.$lineIndex.'.expiry_date', 'form-control')); ?>" id="line_<?php echo e($lineIndex); ?>_expiry" name="lines[<?php echo e($lineIndex); ?>][expiry_date]" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="<?php echo e($lineValues['expiry_date'] ?? ''); ?>" data-input <?php echo e(inputAttrs('lines.'.$lineIndex.'.expiry_date', 'line_'.$lineIndex.'_expiry')); ?>>
        <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="line_<?php echo e($lineIndex); ?>_expiry" aria-label="Choose expiry date" title="Choose expiry date">
            <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
        </button>
    </div>
    <?php $__errorArgs = ['lines.'.$lineIndex.'.expiry_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="invalid-feedback d-block" id="line_<?php echo e($lineIndex); ?>_expiry-error">
            <?php echo e($message); ?>

        </div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\entries\partials\stock-receiving-fields.blade.php ENDPATH**/ ?>