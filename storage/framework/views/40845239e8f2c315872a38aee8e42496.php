<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php ($featuresText = implode("\n", array_keys(array_filter($plan->features ?? [])))); ?>

    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle, 'description' => 'Define the price and access included in this plan.'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <form class="app-card p-3 p-md-4" action="<?php echo e($formAction); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php if($formMethod !== 'POST'): ?> <?php echo method_field($formMethod); ?> <?php endif; ?>
        <section class="app-form-section" aria-labelledby="plan-details-heading">
            <h2 class="app-form-section-title" id="plan-details-heading">Plan details</h2>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="name">Name <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="<?php echo e(inputClass('name')); ?>" id="name" name="name" type="text" value="<?php echo e(getValue('name', $plan)); ?>" maxlength="255" required autofocus <?php echo e(inputAttrs('name')); ?>>
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="name-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="slug">Slug <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="<?php echo e(inputClass('slug')); ?>" id="slug" name="slug" type="text" value="<?php echo e(getValue('slug', $plan)); ?>" maxlength="80" required <?php echo e(inputAttrs('slug')); ?>>
                    <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="slug-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-12">
                    <label class="form-label" for="description">Description</label>
                    <textarea class="<?php echo e(inputClass('description')); ?>" id="description" name="description" rows="3" maxlength="5000" <?php echo e(inputAttrs('description')); ?>><?php echo e(getValue('description', $plan)); ?></textarea>
                    <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="description-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </section>
        <section class="app-form-section" aria-labelledby="plan-pricing-heading">
            <h2 class="app-form-section-title" id="plan-pricing-heading">Pricing</h2>
            <p class="app-form-section-note">New assignments use this price. Existing subscription pricing stays unchanged.</p>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label" for="price">Price <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="<?php echo e(inputClass('price')); ?>" id="price" name="price" type="text" inputmode="decimal" value="<?php echo e(getValue('price', null, $price)); ?>" maxlength="13" required <?php echo e(inputAttrs('price')); ?>>
                    <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="price-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="currency">Currency <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="<?php echo e(inputClass('currency')); ?> text-uppercase" id="currency" name="currency" type="text" value="<?php echo e(getValue('currency', $plan, $currency)); ?>" minlength="3" maxlength="3" required <?php echo e(inputAttrs('currency')); ?>>
                    <?php $__errorArgs = ['currency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="currency-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="billing_interval">Billing interval <span class="text-danger" aria-hidden="true">*</span></label>
                    <select class="<?php echo e(inputClass('billing_interval', 'form-select')); ?>" id="billing_interval" name="billing_interval" required <?php echo e(inputAttrs('billing_interval')); ?>>
                        <?php $__currentLoopData = $intervals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($value); ?>" <?php echo e(getSelected($value, 'billing_interval', $plan)); ?>><?php echo e($label); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['billing_interval'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="billing_interval-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </section>
        <section class="app-form-section" aria-labelledby="plan-access-heading">
            <h2 class="app-form-section-title" id="plan-access-heading">Access and limits</h2>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="trial_days">Trial days</label>
                    <input class="<?php echo e(inputClass('trial_days')); ?>" id="trial_days" name="trial_days" type="number" value="<?php echo e(getValue('trial_days', $plan, 0)); ?>" min="0" max="365" required <?php echo e(inputAttrs('trial_days')); ?>>
                    <?php $__errorArgs = ['trial_days'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="trial_days-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="user_limit">User limit</label>
                    <input class="<?php echo e(inputClass('user_limit')); ?>" id="user_limit" name="user_limit" type="number" value="<?php echo e(getValue('user_limit', $plan)); ?>" min="1" max="1000000" <?php echo e(inputAttrs('user_limit', null, 'user-limit-help')); ?>>
                    <p class="form-text" id="user-limit-help">Leave blank for unlimited users.</p>
                    <?php $__errorArgs = ['user_limit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="user_limit-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-12">
                    <label class="form-label" for="features">Feature keys</label>
                    <textarea class="<?php echo e(inputClass('features')); ?>" id="features" name="features" rows="4" maxlength="5000" <?php echo e(inputAttrs('features', null, 'features-help')); ?>><?php echo e(getValue('features', null, $featuresText)); ?></textarea>
                    <p class="form-text" id="features-help">One lowercase feature key per line. Use letters, numbers, dots, underscores, or hyphens; start each key with a letter.</p>
                    <?php $__errorArgs = ['features'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="features-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-12">
                    <input name="is_active" type="hidden" value="0">
                    <label class="form-check d-flex align-items-center gap-2"><input class="<?php echo e(inputClass('is_active', 'form-check-input')); ?>" id="is_active" name="is_active" type="checkbox" value="1" <?php echo e(getChecked(1, 'is_active', $plan, true)); ?> <?php echo e(inputAttrs('is_active')); ?>>Active and available for assignment</label>
                    <?php $__errorArgs = ['is_active'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="is_active-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </section>
        <?php echo $__env->make('core::partials.form-actions', ['model' => $plan, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\resources\views\plans\form.blade.php ENDPATH**/ ?>