<?php $__env->startSection('report-filters'); ?>
    <form method="GET" action="<?php echo e(url()->current()); ?>" class="row g-3 align-items-end">
        <div class="col-12 col-md-4">
            <label class="form-label" for="from_date">From date</label>
            <div class="input-group" data-datepicker><input class="<?php echo e(inputClass('from_date')); ?>" id="from_date" name="from_date" type="text" value="<?php echo e(getValue('from_date', null, $filters['from_date'])); ?>" placeholder="YYYY-MM-DD" autocomplete="off" required data-input <?php echo e(inputAttrs('from_date')); ?>><button class="btn btn-outline-secondary" type="button" data-toggle aria-label="Choose from date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button></div>
            <?php $__errorArgs = ['from_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="from_date-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="col-12 col-md-4">
            <label class="form-label" for="to_date">To date</label>
            <div class="input-group" data-datepicker><input class="<?php echo e(inputClass('to_date')); ?>" id="to_date" name="to_date" type="text" value="<?php echo e(getValue('to_date', null, $filters['to_date'])); ?>" placeholder="YYYY-MM-DD" autocomplete="off" required data-input <?php echo e(inputAttrs('to_date')); ?>><button class="btn btn-outline-secondary" type="button" data-toggle aria-label="Choose to date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button></div>
            <?php $__errorArgs = ['to_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="to_date-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="col-12 col-md-4"><button class="btn btn-primary" type="submit">Show report</button></div>
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report-table'); ?>
    <?php echo $__env->make('tenantcore::reports.partials.day-table', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('tenantcore::reports.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Hospital\resources\views\reports\cash-balance.blade.php ENDPATH**/ ?>