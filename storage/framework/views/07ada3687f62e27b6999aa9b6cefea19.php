<?php ($usingBirthDate = $ageInput === 'date_of_birth'); ?>

<section class="app-form-section" aria-labelledby="<?php echo e($fieldPrefix.'details_heading'); ?>">
    

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label" for="<?php echo e($fieldPrefix.'name'); ?>">Patient Name <span class="text-danger" aria-hidden="true">*</span></label>
            <input class="<?php echo e(inputClass('name')); ?>" id="<?php echo e($fieldPrefix.'name'); ?>" name="name" type="text" value="<?php echo e(getValue('name', $patient)); ?>" maxlength="255" autocomplete="name" required autofocus <?php echo e(inputAttrs('name', $fieldPrefix.'name')); ?>>
            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="<?php echo e($fieldPrefix.'name-error'); ?>"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="col-md-6">
            <label class="form-label" for="<?php echo e($fieldPrefix.'father_husband_name'); ?>">Father/Husband name</label>
            <input class="<?php echo e(inputClass('father_husband_name')); ?>" id="<?php echo e($fieldPrefix.'father_husband_name'); ?>" name="father_husband_name" type="text" value="<?php echo e(getValue('father_husband_name', $patient)); ?>" maxlength="255" <?php echo e(inputAttrs('father_husband_name', $fieldPrefix.'father_husband_name')); ?>>
            <?php $__errorArgs = ['father_husband_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="<?php echo e($fieldPrefix.'father_husband_name-error'); ?>"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <fieldset class="col-12 patient-age-fields">
            <legend class="form-label">Age <span class="text-danger" aria-hidden="true">*</span></legend>
            <div class="d-flex flex-wrap gap-3 mb-2">
                <div class="form-check">
                    <input class="form-check-input" id="<?php echo e($fieldPrefix.'age_input_age'); ?>" name="age_input" type="radio" value="age" <?php if($ageInput === 'age'): echo 'checked'; endif; ?>>
                    <label class="form-check-label" for="<?php echo e($fieldPrefix.'age_input_age'); ?>">Age</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" id="<?php echo e($fieldPrefix.'age_input_birth'); ?>" name="age_input" type="radio" value="date_of_birth" <?php if($usingBirthDate): echo 'checked'; endif; ?>>
                    <label class="form-check-label" for="<?php echo e($fieldPrefix.'age_input_birth'); ?>">Date of birth</label>
                </div>
            </div>
            <?php $__errorArgs = ['age_input'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <div class="row g-3 patient-age-parts" <?php if($usingBirthDate): ?> hidden <?php endif; ?>>
                <div class="col-4">
                    <label class="form-label" for="<?php echo e($fieldPrefix.'age'); ?>">Years</label>
                    <input class="<?php echo e(inputClass('age')); ?>" id="<?php echo e($fieldPrefix.'age'); ?>" name="age" type="number" inputmode="numeric"
                        value="<?php echo e(getValue('age', $patient)); ?>" min="0" max="150" step="1" <?php if($usingBirthDate): echo 'disabled'; endif; ?> <?php echo e(inputAttrs('age', $fieldPrefix.'age')); ?>>
                    <?php $__errorArgs = ['age'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="<?php echo e($fieldPrefix.'age-error'); ?>"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-4">
                    <label class="form-label" for="<?php echo e($fieldPrefix.'age_months'); ?>">Months</label>
                    <input class="<?php echo e(inputClass('age_months')); ?>" id="<?php echo e($fieldPrefix.'age_months'); ?>" name="age_months" type="number" inputmode="numeric"
                        value="<?php echo e(getValue('age_months', $patient)); ?>" min="0" max="11" step="1" <?php if($usingBirthDate): echo 'disabled'; endif; ?> <?php echo e(inputAttrs('age_months', $fieldPrefix.'age_months')); ?>>
                    <?php $__errorArgs = ['age_months'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="<?php echo e($fieldPrefix.'age_months-error'); ?>"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-4">
                    <label class="form-label" for="<?php echo e($fieldPrefix.'age_days'); ?>">Days</label>
                    <input class="<?php echo e(inputClass('age_days')); ?>" id="<?php echo e($fieldPrefix.'age_days'); ?>" name="age_days" type="number" inputmode="numeric"
                        value="<?php echo e(getValue('age_days', $patient)); ?>" min="0" max="30" step="1" <?php if($usingBirthDate): echo 'disabled'; endif; ?> <?php echo e(inputAttrs('age_days', $fieldPrefix.'age_days')); ?>>
                    <?php $__errorArgs = ['age_days'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="<?php echo e($fieldPrefix.'age_days-error'); ?>"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="patient-birth-date" <?php if(! $usingBirthDate): ?> hidden <?php endif; ?>>
                <label class="form-label" for="<?php echo e($fieldPrefix.'date_of_birth'); ?>">Date of birth</label>
                <div class="input-group" data-datepicker data-max-date="today" data-min-date="<?php echo e(now()->subYears(150)->toDateString()); ?>">
                    <input class="<?php echo e(inputClass('date_of_birth')); ?>" id="<?php echo e($fieldPrefix.'date_of_birth'); ?>" name="date_of_birth" type="text" inputmode="numeric"
                        value="<?php echo e($birthDate); ?>" placeholder="YYYY-MM-DD" autocomplete="off" data-input
                        <?php if($usingBirthDate): echo 'required'; endif; ?> <?php if(! $usingBirthDate): echo 'disabled'; endif; ?> <?php echo e(inputAttrs('date_of_birth', $fieldPrefix.'date_of_birth')); ?>>
                    <button class="btn btn-outline-secondary" type="button" data-toggle aria-label="Choose date of birth" <?php if(! $usingBirthDate): echo 'disabled'; endif; ?>>
                        <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
                    </button>
                </div>
                <?php $__errorArgs = ['date_of_birth'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="<?php echo e($fieldPrefix.'date_of_birth-error'); ?>"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </fieldset>
        <div class="col-md-6">
            <label class="form-label" for="<?php echo e($fieldPrefix.'gender'); ?>">Gender <span class="text-danger" aria-hidden="true">*</span></label>
            <select class="<?php echo e(inputClass('gender', 'form-select')); ?>" id="<?php echo e($fieldPrefix.'gender'); ?>" name="gender" required <?php echo e(inputAttrs('gender', $fieldPrefix.'gender')); ?>>
                <option value="">Select</option>
                <?php $__currentLoopData = $genders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($value); ?>" <?php echo e(getSelected($value, 'gender', $patient)); ?>><?php echo e($label); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="<?php echo e($fieldPrefix.'gender-error'); ?>"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="col-md-6">
            <label class="form-label" for="<?php echo e($fieldPrefix.'blood_group'); ?>">Blood group</label>
            <select class="<?php echo e(inputClass('blood_group', 'form-select')); ?>" id="<?php echo e($fieldPrefix.'blood_group'); ?>" name="blood_group" <?php echo e(inputAttrs('blood_group', $fieldPrefix.'blood_group')); ?>>
                <option value="">Select</option>
                <?php $__currentLoopData = $bloodGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($value); ?>" <?php echo e(getSelected($value, 'blood_group', $patient)); ?>><?php echo e($label); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = ['blood_group'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="<?php echo e($fieldPrefix.'blood_group-error'); ?>"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="col-md-6">
            <label class="form-label" for="<?php echo e($fieldPrefix.'mobile_no'); ?>">Mobile no.</label>
            <input class="<?php echo e(inputClass('mobile_no')); ?>" id="<?php echo e($fieldPrefix.'mobile_no'); ?>" name="mobile_no" type="tel" value="<?php echo e(getValue('mobile_no', $patient)); ?>" maxlength="30" autocomplete="tel" <?php echo e(inputAttrs('mobile_no', $fieldPrefix.'mobile_no')); ?>>
            <?php $__errorArgs = ['mobile_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="<?php echo e($fieldPrefix.'mobile_no-error'); ?>"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>
</section>

<section class="app-form-section" aria-labelledby="<?php echo e($fieldPrefix.'address_heading'); ?>">
    <h2 class="app-form-section-title" id="<?php echo e($fieldPrefix.'address_heading'); ?>">Address</h2>
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label" for="<?php echo e($fieldPrefix.'division_id'); ?>">Division</label>
            <select class="<?php echo e(inputClass('division_id', 'form-select')); ?>" id="<?php echo e($fieldPrefix.'division_id'); ?>" name="division_id"
                data-ajax-select data-url="<?php echo e(route('tenantcore.divisions.options')); ?>"
                data-value-field="id" data-label-field="name" data-placeholder="Select a division"
                <?php echo e(inputAttrs('division_id', $fieldPrefix.'division_id')); ?>>
                <option value=""></option>
                <?php if($selectedDivision !== null): ?>
                    <option value="<?php echo e($selectedDivision->id); ?>" selected><?php echo e($selectedDivision->name); ?></option>
                <?php endif; ?>
            </select>
            <?php $__errorArgs = ['division_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="<?php echo e($fieldPrefix.'division_id-error'); ?>"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="col-md-4">
            <label class="form-label" for="<?php echo e($fieldPrefix.'district_id'); ?>">District</label>
            <input type="hidden" name="district_id" value="">
            <select class="<?php echo e(inputClass('district_id', 'form-select')); ?>" id="<?php echo e($fieldPrefix.'district_id'); ?>" name="district_id"
                data-ajax-select data-url="<?php echo e(route('tenantcore.districts.options')); ?>"
                data-value-field="id" data-label-field="name" data-placeholder="Select a district" data-parent-select="<?php echo e($fieldPrefix.'division_id'); ?>" data-parent-field="division_id" <?php if($selectedDivision === null): echo 'disabled'; endif; ?>
                <?php echo e(inputAttrs('district_id', $fieldPrefix.'district_id')); ?>>
                <option value=""></option>
                <?php if($selectedDistrict !== null): ?>
                    <option value="<?php echo e($selectedDistrict->id); ?>" selected><?php echo e($selectedDistrict->name); ?></option>
                <?php endif; ?>
            </select>
            <?php $__errorArgs = ['district_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="<?php echo e($fieldPrefix.'district_id-error'); ?>"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="col-md-4">
            <label class="form-label" for="<?php echo e($fieldPrefix.'thana_id'); ?>">Thana</label>
            <input type="hidden" name="thana_id" value="">
            <select class="<?php echo e(inputClass('thana_id', 'form-select')); ?>" id="<?php echo e($fieldPrefix.'thana_id'); ?>" name="thana_id"
                data-ajax-select data-url="<?php echo e(route('tenantcore.thanas.options')); ?>"
                data-value-field="id" data-label-field="name" data-placeholder="Select a thana" data-parent-select="<?php echo e($fieldPrefix.'district_id'); ?>" data-parent-field="district_id" <?php if($selectedDistrict === null): echo 'disabled'; endif; ?>
                <?php echo e(inputAttrs('thana_id', $fieldPrefix.'thana_id')); ?>>
                <option value=""></option>
                <?php if($selectedThana !== null): ?>
                    <option value="<?php echo e($selectedThana->id); ?>" selected><?php echo e($selectedThana->name); ?></option>
                <?php endif; ?>
            </select>
            <?php $__errorArgs = ['thana_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="<?php echo e($fieldPrefix.'thana_id-error'); ?>"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="col-6">
            <label class="form-label" for="<?php echo e($fieldPrefix.'address'); ?>">Address</label>
            <textarea class="<?php echo e(inputClass('address')); ?>" id="<?php echo e($fieldPrefix.'address'); ?>" name="address" rows="2" maxlength="2000" autocomplete="street-address" <?php echo e(inputAttrs('address', $fieldPrefix.'address')); ?>><?php echo e(getValue('address', $patient)); ?></textarea>
            <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="<?php echo e($fieldPrefix.'address-error'); ?>"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="col-md-6">
        <label class="form-label" for="<?php echo e($fieldPrefix.'patient_id'); ?>">Patient ID</label>
        <input class="form-control" id="<?php echo e($fieldPrefix.'patient_id'); ?>" type="text" value="<?php echo e($patient->patient_id); ?>" placeholder="Auto generated" readonly>
    </div>
    </div>
</section>


<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Hospital\resources\views\patients\fields.blade.php ENDPATH**/ ?>