<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle, 'description' => 'Set the investigation details and rates.'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <form class="app-card p-3 p-md-4" action="<?php echo e($formAction); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php if($formMethod !== 'POST'): ?>
            <?php echo method_field($formMethod); ?>
        <?php endif; ?>

        <section class="app-form-section" aria-labelledby="investigation-form-heading">
            <h2 class="app-form-section-title" id="investigation-form-heading">
                <span class="app-form-section-icon">
                    <i class="fa-solid fa-flask" aria-hidden="true"></i>
                </span>Investigation details
            </h2>

            <div class="row g-3">
                 <div class="col-sm-6">
                    <label class="form-label" for="name">Name <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="<?php echo e(inputClass('name')); ?>" id="name" name="name" type="text" value="<?php echo e(getValue('name', $investigation)); ?>"  maxlength="255" autocomplete="off" required autofocus <?php echo e(inputAttrs('name')); ?>>
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="name-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                 </div>
                <div class="col-sm-6">
                    <label class="form-label" for="category_id">Category <span class="text-danger" aria-hidden="true">*</span></label>
                    <select class="<?php echo e(inputClass('category_id', 'form-select')); ?>" id="category_id" name="category_id"
                        data-ajax-select data-url="<?php echo e(route('hospital.investigation-categories.options')); ?>"
                        data-value-field="id" data-label-field="name" data-placeholder="Select a category" required
                        <?php echo e(inputAttrs('category_id')); ?>>
                        <option value=""></option>
                        <?php if($selectedCategory !== null): ?>
                            <option value="<?php echo e($selectedCategory->id); ?>" selected><?php echo e($selectedCategory->name); ?></option>
                        <?php endif; ?>
                    </select>
                    <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="category_id-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6">
                    <label class="form-label" for="single_rate">Single rate <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="<?php echo e(inputClass('single_rate')); ?>" id="single_rate" name="single_rate" type="text" inputmode="decimal" value="<?php echo e(getValue('single_rate', $investigation, '0.00')); ?>" maxlength="13" required <?php echo e(inputAttrs('single_rate')); ?>>
                    <?php $__errorArgs = ['single_rate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="single_rate-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6">
                    <label class="form-label" for="both_rate">Both rate <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="<?php echo e(inputClass('both_rate')); ?>" id="both_rate" name="both_rate" type="text" inputmode="decimal" value="<?php echo e(getValue('both_rate', $investigation, '0.00')); ?>" maxlength="13" required <?php echo e(inputAttrs('both_rate')); ?>>
                    <?php $__errorArgs = ['both_rate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="both_rate-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6">
                    <label class="form-label" for="room_no">Room no.</label>
                    <input class="<?php echo e(inputClass('room_no')); ?>" id="room_no" name="room_no" type="text" value="<?php echo e(getValue('room_no', $investigation)); ?>" maxlength="50" autocomplete="off" <?php echo e(inputAttrs('room_no')); ?>>
                    <?php $__errorArgs = ['room_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="room_no-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6">
                    <label class="form-label" for="ordering">Ordering <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="<?php echo e(inputClass('ordering')); ?>" id="ordering" name="ordering" type="number" inputmode="numeric" value="<?php echo e(getValue('ordering', $investigation, 0)); ?>" min="0" max="65535" step="1" required <?php echo e(inputAttrs('ordering')); ?>>
                    <?php $__errorArgs = ['ordering'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="ordering-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </section>

        <?php echo $__env->make('core::partials.form-actions', ['model' => $investigation, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Hospital\resources\views\investigations\form.blade.php ENDPATH**/ ?>