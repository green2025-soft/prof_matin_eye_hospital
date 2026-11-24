<form class="p-3 border-bottom" method="GET" action="<?php echo e(route($routePrefix.'.index')); ?>">
    <input name="product_type" type="hidden" value="<?php echo e($filters['product_type'] ?? 'medicine'); ?>">
    <input name="per_page" type="hidden" value="<?php echo e($filters['per_page'] ?? config('pagination.default')); ?>">
    <div class="row g-2 align-items-end">
        <div class="col-sm-6 col-lg-3">
            <label class="form-label small" for="search"><?php echo e($numberLabel); ?></label>
            <input class="form-control" id="search" name="search" value="<?php echo e($filters['search'] ?? ''); ?>" maxlength="80">
        </div>
        <div class="col-sm-6 col-lg-2">
            <label class="form-label small" for="from_date">From Date</label>
            <div class="input-group" data-datepicker>
                <input class="<?php echo e(inputClass('from_date')); ?>" id="from_date" name="from_date" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="<?php echo e(getValue('from_date', null, $filters['from_date'] ?? '')); ?>" data-input <?php echo e(inputAttrs('from_date')); ?>>
                <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="from_date" aria-label="Choose start date" title="Choose start date">
                    <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
                </button>
            </div>
            <?php $__errorArgs = ['from_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback d-block" id="from_date-error"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="col-sm-6 col-lg-2">
            <label class="form-label small" for="to_date">To Date</label>
            <div class="input-group" data-datepicker>
                <input class="<?php echo e(inputClass('to_date')); ?>" id="to_date" name="to_date" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="<?php echo e(getValue('to_date', null, $filters['to_date'] ?? '')); ?>" data-input <?php echo e(inputAttrs('to_date')); ?>>
                <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="to_date" aria-label="Choose end date" title="Choose end date">
                    <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
                </button>
            </div>
            <?php $__errorArgs = ['to_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback d-block" id="to_date-error"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="col-lg-3 d-flex gap-2">
            <button class="btn btn-primary" type="submit">
                Filter
            </button>
            <a class="btn btn-outline-secondary" href="<?php echo e(route($routePrefix.'.index', ['product_type' => $filters['product_type'] ?? 'medicine', 'per_page' => $filters['per_page'] ?? config('pagination.default')])); ?>">
                Reset
            </a>
        </div>
    </div>
</form>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules/Pharmacy\resources/views/entries/partials/filters.blade.php ENDPATH**/ ?>