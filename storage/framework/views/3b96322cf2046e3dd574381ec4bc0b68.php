<div class="col-sm-6 col-lg-2">
    <label class="form-label small" for="report_from">From Date</label>
    <div class="input-group" data-datepicker>
        <input class="<?php echo e(inputClass('from_date')); ?>" id="report_from" name="from_date" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD"
            value="<?php echo e(getValue('from_date', null, $filters['from_date'])); ?>" required data-input
            <?php echo e(inputAttrs('from_date', 'report_from')); ?>>
        <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="report_from" aria-label="Choose start date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
    </div>
    <?php $__errorArgs = ['from_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="report_from-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>
<div class="col-sm-6 col-lg-2">
    <label class="form-label small" for="report_to">To Date</label>
    <div class="input-group" data-datepicker>
        <input class="<?php echo e(inputClass('to_date')); ?>" id="report_to" name="to_date" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD"
            value="<?php echo e(getValue('to_date', null, $filters['to_date'])); ?>" required data-input
            <?php echo e(inputAttrs('to_date', 'report_to')); ?>>
        <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="report_to" aria-label="Choose end date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
    </div>
    <?php $__errorArgs = ['to_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="report_to-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\reports\date-range.blade.php ENDPATH**/ ?>