

<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', [
        'title' => $pageTitle,
        'description' => $isTenantUser
            ? ($account->exists ? 'Update user details and access.' : 'Create a user for this workspace.')
            : ($account->exists ? 'Update account details and access.' : 'Create an account for this workspace.'),
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <form class="app-card p-3 p-md-4" action="<?php echo e($formAction); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php if($formMethod !== 'POST'): ?>
            <?php echo method_field($formMethod); ?>
        <?php endif; ?>
        <section class="app-form-section" aria-labelledby="account-details-heading">
            <h2 class="app-form-section-title" id="account-details-heading">
                <span class="app-form-section-icon">
                    <i class="fa-solid fa-user" aria-hidden="true"></i>
                </span><?php echo e($isTenantUser ? 'User details' : 'Account details'); ?>

            </h2>
            <p class="app-form-section-note"><?php echo e($isTenantUser ? 'The name and email used to identify this user.' : 'The name and email used to identify this account.'); ?></p>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="name">Full name <span class="text-danger" aria-hidden="true">*</span></label>
                    <div class="app-input-wrap">
                        <input class="<?php echo e(inputClass('name')); ?>" id="name" name="name" type="text" value="<?php echo e(getValue('name', $account)); ?>" maxlength="255" autocomplete="name" required autofocus <?php echo e(inputAttrs('name')); ?>>
                    </div>
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback d-block" id="name-error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="email">Email address <span class="text-danger" aria-hidden="true">*</span></label>
                    <div class="app-input-wrap">
                        <input class="<?php echo e(inputClass('email')); ?>" id="email" name="email" type="email" value="<?php echo e(getValue('email', $account)); ?>" maxlength="255" autocomplete="email" required <?php echo e(inputAttrs('email')); ?>>
                    </div>
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
                <?php if($hasBranches): ?>
                    <div class="col-md-6">
                        <label class="form-label" for="branch-id">Branch</label>
                        <select class="<?php echo e(inputClass('branch_id', 'form-select')); ?>" id="branch-id" name="branch_id"
                            <?php echo e(inputAttrs('branch_id', 'branch-id')); ?>>
                            <option value="">None</option>
                            <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($branch->id); ?>" <?php echo e(getSelected($branch->id, 'branch_id', $account)); ?>><?php echo e($branch->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['branch_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback d-block" id="branch-id-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                <?php endif; ?>
                <?php if($isTenantUser): ?>
                    <div class="col-md-6">
                        <label class="form-label" for="redirect-url">Redirect URL</label>
                        <input class="<?php echo e(inputClass('redirect_url')); ?>" id="redirect-url" name="redirect_url" type="text"
                            value="<?php echo e(getValue('redirect_url', $account)); ?>" maxlength="2048" placeholder="/branches"
                            autocomplete="off" <?php echo e(inputAttrs('redirect_url', 'redirect-url', 'redirect-url-help')); ?>>
                        <div class="form-text" id="redirect-url-help">After sign-in open this page in this workspace; leave blank for Dashboard.</div>
                        <?php $__errorArgs = ['redirect_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback d-block" id="redirect-url-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <section class="app-form-section" aria-labelledby="account-security-heading">


            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="password"><?php echo e($account->exists ? 'New password' : 'Password'); ?> <?php if(! $account->exists): ?><span class="text-danger" aria-hidden="true">*</span><?php endif; ?></label>
                    <div class="app-input-wrap">
                        <input class="<?php echo e(inputClass('password')); ?> app-password-input" id="password" name="password" type="password" minlength="<?php echo e(config('auth.password_min_length', 5)); ?>" maxlength="4096" autocomplete="new-password" <?php if(! $account->exists): echo 'required'; endif; ?> <?php echo e(inputAttrs('password')); ?>>
                        <button class="app-password-toggle" type="button" data-password-toggle aria-controls="password" aria-label="Show password" aria-pressed="false" title="Show password"><i class="fa-solid fa-eye" aria-hidden="true"></i></button>
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
                <div class="col-md-6">
                    <label class="form-label" for="password_confirmation">Confirm password <?php if(! $account->exists): ?><span class="text-danger" aria-hidden="true">*</span><?php endif; ?></label>
                    <div class="app-input-wrap">
                        <input class="<?php echo e(inputClass('password_confirmation')); ?> app-password-input" id="password_confirmation" name="password_confirmation" type="password" minlength="<?php echo e(config('auth.password_min_length', 5)); ?>" maxlength="4096" autocomplete="new-password" <?php if(! $account->exists): echo 'required'; endif; ?> <?php echo e(inputAttrs('password_confirmation')); ?>>
                        <button class="app-password-toggle" type="button" data-password-toggle aria-controls="password_confirmation" aria-label="Show password" aria-pressed="false" title="Show password"><i class="fa-solid fa-eye" aria-hidden="true"></i></button>
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
            </div>
        </section>
        <?php if($canAssignRoles): ?>
            <section class="app-form-section" aria-labelledby="account-access-heading">

                <fieldset <?php echo e(inputAttrs('roles', null, 'roles-help')); ?>>

                    <input name="roles_present" type="hidden" value="1">
                    <div class="row g-2">
                        <?php $__empty_1 = true; $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="col-sm-6 col-xl-4">
                                <label class="app-check-option d-flex align-items-start gap-2 h-100" for="roles-<?php echo e($role->id); ?>">
                                    <input class="<?php echo e(inputClass('roles', 'form-check-input')); ?>" id="roles-<?php echo e($role->id); ?>" name="roles[]" type="checkbox" value="<?php echo e($role->id); ?>" <?php echo e(getChecked($role->id, 'roles', null, $selectedRoles)); ?> <?php echo e(inputAttrs('roles')); ?>>
                                    <span class="small text-break"><?php echo e($role->name); ?></span>
                                </label>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="col-12"><p class="small text-body-secondary border rounded p-3 mb-0">No options are available.</p></div>
                        <?php endif; ?>
                    </div>
                    <?php $__errorArgs = ['roles'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback d-block" id="roles-error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </fieldset>
            </section>
        <?php endif; ?>
        <?php echo $__env->make('core::partials.form-actions', ['model' => $account, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel, 'canSubmit' => true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\resources\views\accounts\form.blade.php ENDPATH**/ ?>