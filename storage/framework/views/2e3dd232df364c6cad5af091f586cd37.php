<label class="form-label" for="entry_date">Date <span class="text-danger" aria-hidden="true">*</span></label>
<div class="input-group" data-datepicker>
    <input class="<?php echo e(inputClass('entry_date')); ?>" id="entry_date" name="entry_date" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="<?php echo e(getValue('entry_date', null, $entry->exists ? $entry->entry_date->toDateString() : now()->toDateString())); ?>" required data-input <?php echo e(inputAttrs('entry_date')); ?>>
    <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="entry_date" aria-label="Choose entry date" title="Choose entry date">
        <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
    </button>
</div>
<?php $__errorArgs = ['entry_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
    <div class="invalid-feedback d-block" id="entry_date-error"><?php echo e($message); ?></div>
<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules/Pharmacy\resources/views/entries/partials/date-field.blade.php ENDPATH**/ ?>