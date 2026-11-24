<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <span class="app-auth-form-icon d-inline-flex align-items-center justify-content-center"><i class="fa-solid fa-key" aria-hidden="true"></i></span>
    <h2 class="h3 fw-semibold mb-2"><?php echo e($pageTitle); ?></h2>
    <p class="text-body-secondary mb-4"><?php echo e($description); ?></p>

    <form action="<?php echo e($formAction); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php if($token !== null): ?>
            <input type="hidden" name="token" value="<?php echo e($token); ?>">
        <?php endif; ?>
        <?php if($tenantPassword && count($accountTypes) > 1): ?>
            <?php if($token !== null): ?>
                <input type="hidden" name="account_type" value="<?php echo e($accountType); ?>">
                <p class="small text-body-secondary"><?php echo e($accountTypes[$accountType]['label']); ?> account</p>
            <?php else: ?>
                <div class="mb-3">
                    <label class="form-label" for="account_type">Account type</label>
                    <select class="<?php echo e(inputClass('account_type', 'form-select')); ?>" id="account_type" name="account_type" required <?php echo e(inputAttrs('account_type')); ?>>
                        <?php $__currentLoopData = $accountTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $details): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($type); ?>" <?php echo e(getSelected($type, 'account_type', null, $accountType)); ?>><?php echo e($details['label']); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['account_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback d-block" id="account_type-error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        <div class="mb-3">
            <label class="form-label" for="email">Email address</label>
            <input class="<?php echo e(inputClass('email')); ?>" id="email" name="email" type="email" value="<?php echo e(getValue('email', null, $email)); ?>" maxlength="255" autocomplete="email" required autofocus <?php echo e(inputAttrs('email')); ?>>
            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback" id="email-error"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <?php if($token !== null): ?>
            <div class="mb-3">
                <label class="form-label" for="password">New password</label>
                <div class="app-input-wrap">
                    <input class="<?php echo e(inputClass('password')); ?> app-password-input" id="password" name="password" type="password" minlength="<?php echo e($passwordMinimum); ?>" maxlength="4096" autocomplete="new-password" required <?php echo e(inputAttrs('password', null, 'password-help')); ?>>
                    <button class="app-password-toggle" type="button" data-password-toggle aria-controls="password" aria-label="Show password" aria-pressed="false"><i class="fa-solid fa-eye" aria-hidden="true"></i></button>
                </div>
                <p class="form-text" id="password-help">Use at least <?php echo e($passwordMinimum); ?> characters.</p>
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback d-block" id="password-error"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="mb-4">
                <label class="form-label" for="password_confirmation">Confirm password</label>
                <div class="app-input-wrap">
                    <input class="<?php echo e(inputClass('password_confirmation')); ?> app-password-input" id="password_confirmation" name="password_confirmation" type="password" minlength="<?php echo e($passwordMinimum); ?>" maxlength="4096" autocomplete="new-password" required <?php echo e(inputAttrs('password_confirmation')); ?>>
                    <button class="app-password-toggle" type="button" data-password-toggle aria-controls="password_confirmation" aria-label="Show password" aria-pressed="false"><i class="fa-solid fa-eye" aria-hidden="true"></i></button>
                </div>
                <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback d-block" id="password_confirmation-error"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        <?php endif; ?>
        <button class="btn btn-primary w-100" type="submit"><?php echo e($submitLabel); ?></button>
        <p class="text-center small mt-4 mb-0"><a href="<?php echo e($loginUrl); ?>">Back to sign in</a></p>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\resources\views\auth\password.blade.php ENDPATH**/ ?>