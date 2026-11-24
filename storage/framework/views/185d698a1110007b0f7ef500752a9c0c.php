<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle, 'description' => 'Register the reference and their contact details at the selected branch.'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <form class="app-card p-3 p-md-4" action="<?php echo e($formAction); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php if($formMethod !== 'POST'): ?>
            <?php echo method_field($formMethod); ?>
        <?php endif; ?>

        <section class="app-form-section" aria-labelledby="reference-details-heading">
            <h2 class="app-form-section-title" id="reference-details-heading">
                <span class="app-form-section-icon"><i class="fa-solid fa-address-card" aria-hidden="true"></i></span>Reference details
            </h2>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="name">Name <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="<?php echo e(inputClass('name')); ?>" id="name" name="name" type="text" value="<?php echo e(getValue('name', $reference)); ?>" maxlength="255" autocomplete="name" required autofocus <?php echo e(inputAttrs('name')); ?>>
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
                    <label class="form-label" for="designation">Designation</label>
                    <input class="<?php echo e(inputClass('designation')); ?>" id="designation" name="designation" type="text" value="<?php echo e(getValue('designation', $reference)); ?>" maxlength="255" autocomplete="organization-title" <?php echo e(inputAttrs('designation')); ?>>
                    <?php $__errorArgs = ['designation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="designation-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="reference_id">Reference ID</label>
                    <input class="form-control" id="reference_id" type="text" value="<?php echo e($reference->reference_id); ?>" placeholder="Auto generated" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="contact_no">Contact no. <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="<?php echo e(inputClass('contact_no')); ?>" id="contact_no" name="contact_no" type="tel" value="<?php echo e(getValue('contact_no', $reference)); ?>" maxlength="30" autocomplete="tel" required <?php echo e(inputAttrs('contact_no')); ?>>
                    <?php $__errorArgs = ['contact_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="contact_no-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-12">
                    <label class="form-label" for="address">Address</label>
                    <textarea class="<?php echo e(inputClass('address')); ?>" id="address" name="address" rows="2" maxlength="2000" autocomplete="street-address" <?php echo e(inputAttrs('address')); ?>><?php echo e(getValue('address', $reference)); ?></textarea>
                    <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="address-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </section>

        <?php if(in_array(true, $percentageFields, true)): ?>
            <section class="app-form-section" aria-labelledby="reference-percentages-heading">
                <h2 class="app-form-section-title" id="reference-percentages-heading">Commission percentages</h2>
                <div class="row g-3">
                    <?php if($percentageFields['consultation_percent']): ?>
                        <div class="col-md-4">
                            <label class="form-label" for="consultation_percent">Consultation % <span class="text-danger" aria-hidden="true">*</span></label>
                            <input class="<?php echo e(inputClass('consultation_percent')); ?>" id="consultation_percent" name="consultation_percent" type="number" inputmode="decimal"
                                value="<?php echo e(getValue('consultation_percent', $reference, $percentageDefaults['consultation_percent'])); ?>" min="0" max="100" step="0.01" required
                                <?php echo e(inputAttrs('consultation_percent')); ?>>
                            <?php $__errorArgs = ['consultation_percent'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="consultation_percent-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    <?php endif; ?>
                    <?php if($percentageFields['investigation_percent']): ?>
                        <div class="col-md-4">
                            <label class="form-label" for="investigation_percent">Investigation % <span class="text-danger" aria-hidden="true">*</span></label>
                            <input class="<?php echo e(inputClass('investigation_percent')); ?>" id="investigation_percent" name="investigation_percent" type="number" inputmode="decimal"
                                value="<?php echo e(getValue('investigation_percent', $reference, $percentageDefaults['investigation_percent'])); ?>" min="0" max="100" step="0.01" required
                                <?php echo e(inputAttrs('investigation_percent')); ?>>
                            <?php $__errorArgs = ['investigation_percent'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="investigation_percent-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    <?php endif; ?>
                    <?php if($percentageFields['operation_percent']): ?>
                        <div class="col-md-4">
                            <label class="form-label" for="operation_percent">Operation % <span class="text-danger" aria-hidden="true">*</span></label>
                            <input class="<?php echo e(inputClass('operation_percent')); ?>" id="operation_percent" name="operation_percent" type="number" inputmode="decimal"
                                value="<?php echo e(getValue('operation_percent', $reference, $percentageDefaults['operation_percent'])); ?>" min="0" max="100" step="0.01" required
                                <?php echo e(inputAttrs('operation_percent')); ?>>
                            <?php $__errorArgs = ['operation_percent'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="operation_percent-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        <?php endif; ?>

        <?php echo $__env->make('core::partials.form-actions', ['model' => $reference, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Hospital\resources\views\references\form.blade.php ENDPATH**/ ?>