<form class="p-3" action="<?php echo e(url()->current()); ?>" method="GET">
    <div class="row g-3 align-items-end">
        <div class="col-sm-6 col-lg-3">
            <label class="form-label" for="report-month">Report month</label>
            <div class="input-group" data-datepicker data-format="Y-m" data-alt-format="F Y">
                <input class="<?php echo e(inputClass('month')); ?>" id="report-month" name="month" type="text"
                    value="<?php echo e(getValue('month', null, $filters['month'])); ?>" placeholder="YYYY-MM"
                    autocomplete="off" required data-input <?php echo e(inputAttrs('month', 'report-month')); ?>>
                <button class="btn btn-outline-secondary" type="button" data-toggle aria-label="Choose report month">
                    <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
                </button>
            </div>
            <?php $__errorArgs = ['month'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="col-sm-6 col-lg-3">
            <label class="form-label" for="report-depth">Detail level</label>
            <select class="<?php echo e(inputClass('depth', 'form-select')); ?>" id="report-depth" name="depth" required>
                <?php $__currentLoopData = [2 => 'Group', 3 => 'Subgroup', 4 => 'Account group', 5 => 'Ledger']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($value); ?>" <?php echo e(getSelected($value, 'depth', null, $filters['depth'])); ?>><?php echo e($label); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = ['depth'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="col-sm-auto d-flex gap-2">
            <button class="btn btn-primary" type="submit">Filter</button>
            <a class="btn btn-outline-secondary" href="<?php echo e(url()->current()); ?>">Reset</a>
        </div>
    </div>
</form>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Accounting\resources\views\reports\month-filters.blade.php ENDPATH**/ ?>