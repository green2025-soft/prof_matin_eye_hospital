<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle, 'description' => 'Set the doctor profile, professional details, fees, and sign-in access.'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <form class="app-card p-3 p-md-4" action="<?php echo e($formAction); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php if($formMethod !== 'POST'): ?>
            <?php echo method_field($formMethod); ?>
        <?php endif; ?>

        <?php if($doctor->exists && $doctor->doctor_id !== null): ?>
            <p class="small text-body-secondary mb-3">Doctor ID: <strong class="text-body"><?php echo e($doctor->doctor_id); ?></strong></p>
        <?php endif; ?>

        <section aria-labelledby="doctor-details-heading">
            <h2 class="visually-hidden" id="doctor-details-heading">Doctor Details</h2>
            <div class="row g-3">
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="name">Doctor Name <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="<?php echo e(inputClass('name')); ?>" id="name" name="name" type="text" value="<?php echo e(getValue('name', $doctor)); ?>" maxlength="255" autocomplete="name" required autofocus <?php echo e(inputAttrs('name')); ?>>
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="name-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="bn_name" lang="bn">ডাক্তার নাম</label>
                    <input class="<?php echo e(inputClass('bn_name')); ?>" id="bn_name" name="bn_name" type="text" value="<?php echo e(getValue('bn_name', $doctor)); ?>" maxlength="255" lang="bn" <?php echo e(inputAttrs('bn_name')); ?>>
                    <?php $__errorArgs = ['bn_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="bn_name-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="mobile_no">Mobile No. <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="<?php echo e(inputClass('mobile_no')); ?>" id="mobile_no" name="mobile_no" type="tel" value="<?php echo e(getValue('mobile_no', $doctor)); ?>" maxlength="30" autocomplete="tel" required <?php echo e(inputAttrs('mobile_no')); ?>>
                    <?php $__errorArgs = ['mobile_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="mobile_no-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="department_id">Department <span class="text-danger" aria-hidden="true">*</span></label>
                    <select class="<?php echo e(inputClass('department_id', 'form-select')); ?>" id="department_id" name="department_id"
                        data-ajax-select data-url="<?php echo e(route('hospital.departments.options')); ?>"
                        data-value-field="id" data-label-field="name" data-placeholder="Select Department" required
                        <?php echo e(inputAttrs('department_id')); ?>>
                        <option value=""></option>
                        <?php if($selectedDepartment !== null): ?>
                            <option value="<?php echo e($selectedDepartment->id); ?>" selected><?php echo e($selectedDepartment->name); ?></option>
                        <?php endif; ?>
                    </select>
                    <?php $__errorArgs = ['department_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="department_id-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="designation">Designation</label>
                    <input class="<?php echo e(inputClass('designation')); ?>" id="designation" name="designation" type="text" value="<?php echo e(getValue('designation', $doctor)); ?>" maxlength="255" <?php echo e(inputAttrs('designation')); ?>>
                    <?php $__errorArgs = ['designation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="designation-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="specialist">Specialist</label>
                    <input class="<?php echo e(inputClass('specialist')); ?>" id="specialist" name="specialist" type="text" value="<?php echo e(getValue('specialist', $doctor)); ?>" maxlength="255" <?php echo e(inputAttrs('specialist')); ?>>
                    <?php $__errorArgs = ['specialist'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="specialist-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="bn_specialist" lang="bn">স্পেশালিস্ট</label>
                    <input class="<?php echo e(inputClass('bn_specialist')); ?>" id="bn_specialist" name="bn_specialist" type="text" value="<?php echo e(getValue('bn_specialist', $doctor)); ?>" maxlength="255" lang="bn" <?php echo e(inputAttrs('bn_specialist')); ?>>
                    <?php $__errorArgs = ['bn_specialist'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="bn_specialist-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <label class="form-label mb-0" for="picture">Picture</label>
                        <?php if($doctor->picture_url): ?>
                            <img class="app-table-avatar object-fit-cover" src="<?php echo e($doctor->picture_url); ?>" alt="Current doctor picture" width="34" height="34">
                        <?php endif; ?>
                    </div>
                    <input class="<?php echo e(inputClass('picture')); ?>" id="picture" name="picture" type="file" accept=".png,.jpg,.jpeg,.webp,.gif,image/png,image/jpeg,image/webp,image/gif" <?php echo e(inputAttrs('picture', null, 'picture-help')); ?>>
                    <div class="form-text" id="picture-help">PNG, JPG, WebP, or GIF up to 2 MB.</div>
                    <?php $__errorArgs = ['picture'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="picture-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="bmdc_reg">BMDC Registration</label>
                    <input class="<?php echo e(inputClass('bmdc_reg')); ?>" id="bmdc_reg" name="bmdc_reg" type="text" value="<?php echo e(getValue('bmdc_reg', $doctor)); ?>" maxlength="100" <?php echo e(inputAttrs('bmdc_reg')); ?>>
                    <?php $__errorArgs = ['bmdc_reg'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="bmdc_reg-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="bn_bmdc_reg" lang="bn">বিএমডিসি রেজি</label>
                    <input class="<?php echo e(inputClass('bn_bmdc_reg')); ?>" id="bn_bmdc_reg" name="bn_bmdc_reg" type="text" value="<?php echo e(getValue('bn_bmdc_reg', $doctor)); ?>" maxlength="100" lang="bn" <?php echo e(inputAttrs('bn_bmdc_reg')); ?>>
                    <?php $__errorArgs = ['bn_bmdc_reg'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="bn_bmdc_reg-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="gender">Gender <span class="text-danger" aria-hidden="true">*</span></label>
                    <select class="<?php echo e(inputClass('gender', 'form-select')); ?>" id="gender" name="gender" required <?php echo e(inputAttrs('gender')); ?>>
                        <option value="">Select</option>
                        <?php $__currentLoopData = $genders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value); ?>" <?php echo e(getSelected($value, 'gender', $doctor)); ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="gender-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="blood_group">Blood Group</label>
                    <select class="<?php echo e(inputClass('blood_group', 'form-select')); ?>" id="blood_group" name="blood_group" <?php echo e(inputAttrs('blood_group')); ?>>
                        <option value="">Select</option>
                        <?php $__currentLoopData = $bloodGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value); ?>" <?php echo e(getSelected($value, 'blood_group', $doctor)); ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['blood_group'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="blood_group-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="education_degree">Education / Degree</label>
                    <textarea class="<?php echo e(inputClass('education_degree')); ?>" id="education_degree" name="education_degree" rows="2" maxlength="2000" <?php echo e(inputAttrs('education_degree')); ?>><?php echo e(getValue('education_degree', $doctor)); ?></textarea>
                    <?php $__errorArgs = ['education_degree'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="education_degree-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="bn_education_degree" lang="bn">শিক্ষা/ডিগ্রী</label>
                    <textarea class="<?php echo e(inputClass('bn_education_degree')); ?>" id="bn_education_degree" name="bn_education_degree" rows="2" maxlength="2000" lang="bn" <?php echo e(inputAttrs('bn_education_degree')); ?>><?php echo e(getValue('bn_education_degree', $doctor)); ?></textarea>
                    <?php $__errorArgs = ['bn_education_degree'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="bn_education_degree-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="current_employment">Current Employment</label>
                    <textarea class="<?php echo e(inputClass('current_employment')); ?>" id="current_employment" name="current_employment" rows="2" maxlength="2000" <?php echo e(inputAttrs('current_employment')); ?>><?php echo e(getValue('current_employment', $doctor)); ?></textarea>
                    <?php $__errorArgs = ['current_employment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="current_employment-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="bn_current_employment" lang="bn">বর্তমান কর্মরত</label>
                    <textarea class="<?php echo e(inputClass('bn_current_employment')); ?>" id="bn_current_employment" name="bn_current_employment" rows="2" maxlength="2000" lang="bn" <?php echo e(inputAttrs('bn_current_employment')); ?>><?php echo e(getValue('bn_current_employment', $doctor)); ?></textarea>
                    <?php $__errorArgs = ['bn_current_employment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="bn_current_employment-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6">
                    <label class="form-label" for="address">Address</label>
                    <textarea class="<?php echo e(inputClass('address')); ?>" id="address" name="address" rows="2" maxlength="2000" autocomplete="street-address" <?php echo e(inputAttrs('address')); ?>><?php echo e(getValue('address', $doctor)); ?></textarea>
                    <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="address-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6">
                    <label class="form-label" for="pad_footer_text">Pad Footer Text</label>
                    <textarea class="<?php echo e(inputClass('pad_footer_text')); ?>" id="pad_footer_text" name="pad_footer_text" rows="2" maxlength="4000" <?php echo e(inputAttrs('pad_footer_text')); ?>><?php echo e(getValue('pad_footer_text', $doctor)); ?></textarea>
                    <?php $__errorArgs = ['pad_footer_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="pad_footer_text-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="consultation_fee">Consultation Fee <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="<?php echo e(inputClass('consultation_fee')); ?>" id="consultation_fee" name="consultation_fee" type="text" inputmode="decimal" value="<?php echo e(getValue('consultation_fee', $doctor, '0.00')); ?>" maxlength="13" required <?php echo e(inputAttrs('consultation_fee')); ?>>
                    <?php $__errorArgs = ['consultation_fee'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="consultation_fee-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="follow_up_fee">Follow-Up Fee <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="<?php echo e(inputClass('follow_up_fee')); ?>" id="follow_up_fee" name="follow_up_fee" type="text" inputmode="decimal" value="<?php echo e(getValue('follow_up_fee', $doctor, '0.00')); ?>" maxlength="13" required <?php echo e(inputAttrs('follow_up_fee')); ?>>
                    <?php $__errorArgs = ['follow_up_fee'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="follow_up_fee-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="email">Email Address <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="<?php echo e(inputClass('email')); ?>" id="email" name="email" type="email" value="<?php echo e(getValue('email', $doctor)); ?>" maxlength="255" autocomplete="email" required <?php echo e(inputAttrs('email')); ?>>
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="email-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="password"><?php echo e($doctor->exists ? 'New Password' : 'Password'); ?> <?php if(! $doctor->exists): ?><span class="text-danger" aria-hidden="true">*</span><?php endif; ?></label>
                    <div class="app-input-wrap">
                        <input class="<?php echo e(inputClass('password')); ?> app-password-input" id="password" name="password" type="password" minlength="<?php echo e($passwordMinLength); ?>" maxlength="4096" autocomplete="new-password" <?php if(! $doctor->exists): echo 'required'; endif; ?> <?php echo e(inputAttrs('password')); ?>>
                        <button class="app-password-toggle" type="button" data-password-toggle aria-controls="password" aria-label="Show password" aria-pressed="false" title="Show password"><i class="fa-solid fa-eye" aria-hidden="true"></i></button>
                    </div>
                    <?php if($doctor->exists): ?>
                        <div class="form-text">Leave blank to keep the current password.</div>
                    <?php endif; ?>
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="password-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </section>

        <?php echo $__env->make('core::partials.form-actions', ['model' => $doctor, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Hospital\resources\views\doctors\form.blade.php ENDPATH**/ ?>