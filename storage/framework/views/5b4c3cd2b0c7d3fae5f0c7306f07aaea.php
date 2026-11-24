<?php $__env->startSection('title', 'Sign in'); ?>

<?php $__env->startSection('content'); ?>
    <div class="app-auth-heading mb-4 text-center">
        <h2>Welcome</h2>
        
    </div>
    <form action="<?php echo e(route('auth.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php if($tenantLogin && count($accountTypes) > 1): ?>
            <div class="mb-3">
                <label class="form-label" for="account_type">Sign in as</label>
                <select class="<?php echo e(inputClass('account_type', 'form-select')); ?>" id="account_type" name="account_type" required
                    <?php echo e(inputAttrs('account_type')); ?>>
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
        <div class="mb-3">
            <label class="form-label" for="email">Email address <span class="text-danger" aria-hidden="true">*</span></label>
            <input class="<?php echo e(inputClass('email')); ?>" id="email" name="email" type="email"
                value="<?php echo e(getValue('email')); ?>" maxlength="255" autocomplete="username"
                placeholder="you@example.com" required autofocus <?php echo e(inputAttrs('email')); ?>>
            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback d-block" id="email-error"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="mb-3">
            <label class="form-label" for="password">Password <span class="text-danger" aria-hidden="true">*</span></label>
            <div class="app-input-wrap">
                <input class="<?php echo e(inputClass('password')); ?> app-password-input" id="password" name="password" type="password"
                    minlength="<?php echo e(config('auth.password_min_length')); ?>" maxlength="4096" autocomplete="current-password"
                    placeholder="Enter your password" required <?php echo e(inputAttrs('password')); ?>>
                <button class="app-password-toggle" type="button" data-password-toggle aria-controls="password"
                    aria-label="Show password" aria-pressed="false" title="Show password">
                    <i class="fa-solid fa-eye" aria-hidden="true"></i>
                </button>
            </div>
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
        <div class="app-auth-options d-flex flex-wrap align-items-center justify-content-between row-gap-1 column-gap-3 mb-3">
            <div class="form-check d-flex align-items-center gap-2 m-0 p-0">
                <input class="form-check-input float-none flex-shrink-0 m-0" id="remember" name="remember" type="checkbox" value="1" <?php echo e(getChecked(1, 'remember', null, 0)); ?>>
                <label class="form-check-label d-inline-flex align-items-center" for="remember">Keep me signed in</label>
            </div>
            <a class="small d-inline-flex align-items-center" href="<?php echo e(route('auth.password.request', $tenantLogin ? ['account_type' => getValue('account_type', null, $accountType)] : [])); ?>">Forgot password?</a>
        </div>
        <button class="btn btn-primary w-100" type="submit">
            Sign in<i class="fa-solid fa-arrow-right ms-2" aria-hidden="true"></i>
        </button>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\app\Providers/../../resources/views/auth/login.blade.php ENDPATH**/ ?>