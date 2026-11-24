<section class="app-card p-3 p-md-4 mb-4" aria-labelledby="permission-<?php echo e($recordField); ?>-heading">
    <h2 class="h6 mb-3" id="permission-<?php echo e($recordField); ?>-heading">
        <i class="fa-solid fa-<?php echo e($recordIcon); ?> me-2 text-body-secondary" aria-hidden="true"></i>Choose a <?php echo e($recordLabel); ?>

    </h2>
    <form action="<?php echo e($pickerAction); ?>" method="GET" data-permission-picker>
        <div class="row g-3 align-items-end">
            <div class="col-lg-5">
                <label class="form-label" for="<?php echo e($recordField); ?>-search">Find a <?php echo e($recordLabel); ?></label>
                <div class="input-group">
                    <input class="<?php echo e(inputClass($searchField)); ?>" id="<?php echo e($recordField); ?>-search" name="<?php echo e($searchField); ?>"
                        type="search" value="<?php echo e(getValue($searchField, null, $searchValue)); ?>"
                        placeholder="<?php echo e(ucfirst($recordLabel)); ?> name starts with..." maxlength="80"
                        <?php echo e(inputAttrs($searchField, $recordField.'-search', $recordField.'-search-help')); ?>>
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fa-solid fa-magnifying-glass me-2" aria-hidden="true"></i>Search
                    </button>
                </div>
                <?php $__errorArgs = [$searchField];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback d-block" id="<?php echo e($recordField); ?>-search-error"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-lg-5">
                <label class="form-label" for="permission-<?php echo e($recordField); ?>"><?php echo e(ucfirst($recordLabel)); ?></label>
                <select class="<?php echo e(inputClass($recordField, 'form-select')); ?>" id="permission-<?php echo e($recordField); ?>"
                    name="<?php echo e($recordField); ?>" data-permission-record-select
                    <?php echo e(inputAttrs($recordField, 'permission-'.$recordField)); ?>>
                    <option value="">Select a <?php echo e($recordLabel); ?></option>
                    <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($record->id); ?>" <?php echo e(getSelected($record->id, $recordField, null, $selectedRecord?->id)); ?>><?php echo e($record->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = [$recordField];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback d-block" id="permission-<?php echo e($recordField); ?>-error"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-lg-2">
                <button class="btn btn-primary w-100" type="submit">
                    <i class="fa-solid fa-arrow-right me-2" aria-hidden="true"></i>Load
                </button>
            </div>
        </div>
        <p class="form-text mb-0 mt-2" id="<?php echo e($recordField); ?>-search-help">
            <?php if($hasMoreRecords): ?>
                More <?php echo e($recordLabel); ?>s match this search. Narrow the name to find the <?php echo e($recordLabel); ?> you need.
            <?php else: ?>
                Search by the start of a <?php echo e($recordLabel); ?> name, then select a <?php echo e($recordLabel); ?> to review its permissions.
            <?php endif; ?>
        </p>
    </form>
</section>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\resources\views\permissions\picker.blade.php ENDPATH**/ ?>