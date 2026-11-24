<label class="form-label" for="notes">Notes</label>
<textarea class="<?php echo e(inputClass('notes')); ?>" id="notes" name="notes" rows="2" maxlength="2000" <?php echo e(inputAttrs('notes')); ?>><?php echo e(getValue('notes', $entry)); ?></textarea>
<?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
    <div class="invalid-feedback d-block" id="notes-error"><?php echo e($message); ?></div>
<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\entries\partials\notes-field.blade.php ENDPATH**/ ?>