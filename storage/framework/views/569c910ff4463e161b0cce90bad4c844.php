<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle, 'description' => 'Set the branch location and its contact details.'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <form class="app-card p-3 p-md-4" action="<?php echo e($formAction); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php if($formMethod !== 'POST'): ?>
            <?php echo method_field($formMethod); ?>
        <?php endif; ?>
        <section class="app-form-section" aria-labelledby="branch-details-heading">
            <h2 class="app-form-section-title" id="branch-details-heading">
                <span class="app-form-section-icon"><i class="fa-solid fa-building" aria-hidden="true"></i></span>Branch details
            </h2>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="branch-name">Branch name <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="<?php echo e(inputClass('name')); ?>" id="branch-name" name="name" type="text"
                        value="<?php echo e(getValue('name', $branch)); ?>" maxlength="255" autocomplete="organization" required autofocus
                        <?php echo e(inputAttrs('name', 'branch-name')); ?>>
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback d-block" id="branch-name-error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-12">
                    <label class="form-label" for="branch-address">Address <span class="text-danger" aria-hidden="true">*</span></label>
                    <textarea class="<?php echo e(inputClass('address')); ?>" id="branch-address" name="address" rows="3"
                        maxlength="2000" autocomplete="street-address" required
                        <?php echo e(inputAttrs('address', 'branch-address')); ?>><?php echo e(getValue('address', $branch)); ?></textarea>
                    <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback d-block" id="branch-address-error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </section>
        <section class="app-form-section" aria-labelledby="branch-contact-heading">
            <h2 class="app-form-section-title" id="branch-contact-heading">Contact details</h2>
            <p class="app-form-section-note">These contact fields are optional.</p>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="branch-mobile">Mobile number</label>
                    <input class="<?php echo e(inputClass('mobile_no')); ?>" id="branch-mobile" name="mobile_no" type="tel"
                        value="<?php echo e(getValue('mobile_no', $branch)); ?>" maxlength="30" autocomplete="tel"
                        <?php echo e(inputAttrs('mobile_no', 'branch-mobile')); ?>>
                    <?php $__errorArgs = ['mobile_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback d-block" id="branch-mobile-error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="branch-telephone">Telephone number</label>
                    <input class="<?php echo e(inputClass('telephone_no')); ?>" id="branch-telephone" name="telephone_no" type="tel"
                        value="<?php echo e(getValue('telephone_no', $branch)); ?>" maxlength="30" autocomplete="tel"
                        <?php echo e(inputAttrs('telephone_no', 'branch-telephone')); ?>>
                    <?php $__errorArgs = ['telephone_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback d-block" id="branch-telephone-error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="branch-email">Email address</label>
                    <input class="<?php echo e(inputClass('email')); ?>" id="branch-email" name="email" type="email"
                        value="<?php echo e(getValue('email', $branch)); ?>" maxlength="255" autocomplete="email"
                        <?php echo e(inputAttrs('email', 'branch-email')); ?>>
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback d-block" id="branch-email-error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </section>
        <section class="app-form-section" aria-labelledby="branch-logo-heading">
            <h2 class="app-form-section-title" id="branch-logo-heading">Branch logo</h2>
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label" for="branch-logo">Logo</label>
                    <?php if($branch->logo_path): ?>
                        <img class="app-brand-preview img-thumbnail mb-2" src="<?php echo e($branch->logo_url); ?>" alt="Current branch logo" width="160" height="80">
                    <?php endif; ?>
                    <input class="<?php echo e(inputClass('logo')); ?>" id="branch-logo" name="logo" type="file"
                        accept=".png,.jpg,.jpeg,.webp,.gif,image/png,image/jpeg,image/webp,image/gif"
                        <?php echo e(inputAttrs('logo', 'branch-logo', 'branch-logo-help')); ?>>
                    <div class="form-text" id="branch-logo-help">Optional PNG, JPG, WebP, or GIF up to 2 MB. Leave blank to keep the current logo.</div>
                    <?php $__errorArgs = ['logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback d-block" id="branch-logo-error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </section>
        <?php echo $__env->make('core::partials.form-actions', ['model' => $branch, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\TenantCore\resources\views\branches\form.blade.php ENDPATH**/ ?>