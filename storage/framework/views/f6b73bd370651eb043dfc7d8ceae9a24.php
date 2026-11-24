<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <form class="app-card p-3 p-md-4" id="glass-prescription-form" action="<?php echo e($formAction); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php if($formMethod !== 'POST'): ?>
            <?php echo method_field($formMethod); ?>
        <?php endif; ?>

        <?php echo $__env->make('prescription::partials.identity', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <section class="app-form-section mb-3" aria-labelledby="glass-powers-heading">
            <h2 class="h6 mb-3" id="glass-powers-heading">Eye prescription</h2>
            <div class="row g-3">
                <div class="col-lg-6">
                    <div class="app-glass-eye-card">
                        <h3 class="h6 app-glass-eye-heading">Right eye (OD)</h3>
                        <div class="table-responsive">
                            <table class="table table-sm app-glass-eye-table align-middle mb-0">
                                <caption class="visually-hidden">Right eye (OD) distance and near vision powers</caption>
                                <thead>
                                    <tr>
                                        <th scope="col">Vision</th>
                                        <th scope="col">SPH</th>
                                        <th scope="col">CYL</th>
                                        <th scope="col">Axis</th>
                                        <th scope="col">V/A</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">DV</th>
                                        <td>
                                            <label class="visually-hidden" for="glass_r_sphere_dv">Right eye (OD) DV SPH</label>
                                            <input class="<?php echo e(inputClass('r_sphere_dv', 'form-control form-control-sm text-center')); ?>" id="glass_r_sphere_dv" name="r_sphere_dv" type="text" maxlength="30" value="<?php echo e(getValue('r_sphere_dv', $record)); ?>" <?php echo e(inputAttrs('r_sphere_dv', 'glass_r_sphere_dv')); ?>>
                                            <?php $__errorArgs = ['r_sphere_dv'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="glass_r_sphere_dv-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </td>
                                        <td>
                                            <label class="visually-hidden" for="glass_r_cylinder_dv">Right eye (OD) DV CYL</label>
                                            <input class="<?php echo e(inputClass('r_cylinder_dv', 'form-control form-control-sm text-center')); ?>" id="glass_r_cylinder_dv" name="r_cylinder_dv" type="text" maxlength="30" value="<?php echo e(getValue('r_cylinder_dv', $record)); ?>" <?php echo e(inputAttrs('r_cylinder_dv', 'glass_r_cylinder_dv')); ?>>
                                            <?php $__errorArgs = ['r_cylinder_dv'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="glass_r_cylinder_dv-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </td>
                                        <td>
                                            <label class="visually-hidden" for="glass_r_axis_dv">Right eye (OD) DV Axis</label>
                                            <input class="<?php echo e(inputClass('r_axis_dv', 'form-control form-control-sm text-center')); ?>" id="glass_r_axis_dv" name="r_axis_dv" type="text" maxlength="30" value="<?php echo e(getValue('r_axis_dv', $record)); ?>" <?php echo e(inputAttrs('r_axis_dv', 'glass_r_axis_dv')); ?>>
                                            <?php $__errorArgs = ['r_axis_dv'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="glass_r_axis_dv-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </td>
                                        <td>
                                            <label class="visually-hidden" for="glass_r_acuity_dv">Right eye (OD) DV V/A</label>
                                            <input class="<?php echo e(inputClass('r_acuity_dv', 'form-control form-control-sm text-center')); ?>" id="glass_r_acuity_dv" name="r_acuity_dv" type="text" maxlength="30" value="<?php echo e(getValue('r_acuity_dv', $record)); ?>" <?php echo e(inputAttrs('r_acuity_dv', 'glass_r_acuity_dv')); ?>>
                                            <?php $__errorArgs = ['r_acuity_dv'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="glass_r_acuity_dv-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">NV</th>
                                        <td>
                                            <label class="visually-hidden" for="glass_r_sphere_nv">Right eye (OD) NV SPH</label>
                                            <input class="<?php echo e(inputClass('r_sphere_nv', 'form-control form-control-sm text-center')); ?>" id="glass_r_sphere_nv" name="r_sphere_nv" type="text" maxlength="30" value="<?php echo e(getValue('r_sphere_nv', $record)); ?>" <?php echo e(inputAttrs('r_sphere_nv', 'glass_r_sphere_nv')); ?>>
                                            <?php $__errorArgs = ['r_sphere_nv'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="glass_r_sphere_nv-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </td>
                                        <td>
                                            <label class="visually-hidden" for="glass_r_cylinder_nv">Right eye (OD) NV CYL</label>
                                            <input class="<?php echo e(inputClass('r_cylinder_nv', 'form-control form-control-sm text-center')); ?>" id="glass_r_cylinder_nv" name="r_cylinder_nv" type="text" maxlength="30" value="<?php echo e(getValue('r_cylinder_nv', $record)); ?>" <?php echo e(inputAttrs('r_cylinder_nv', 'glass_r_cylinder_nv')); ?>>
                                            <?php $__errorArgs = ['r_cylinder_nv'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="glass_r_cylinder_nv-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </td>
                                        <td>
                                            <label class="visually-hidden" for="glass_r_axis_nv">Right eye (OD) NV Axis</label>
                                            <input class="<?php echo e(inputClass('r_axis_nv', 'form-control form-control-sm text-center')); ?>" id="glass_r_axis_nv" name="r_axis_nv" type="text" maxlength="30" value="<?php echo e(getValue('r_axis_nv', $record)); ?>" <?php echo e(inputAttrs('r_axis_nv', 'glass_r_axis_nv')); ?>>
                                            <?php $__errorArgs = ['r_axis_nv'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="glass_r_axis_nv-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </td>
                                        <td>
                                            <label class="visually-hidden" for="glass_r_acuity_nv">Right eye (OD) NV V/A</label>
                                            <input class="<?php echo e(inputClass('r_acuity_nv', 'form-control form-control-sm text-center')); ?>" id="glass_r_acuity_nv" name="r_acuity_nv" type="text" maxlength="30" value="<?php echo e(getValue('r_acuity_nv', $record)); ?>" <?php echo e(inputAttrs('r_acuity_nv', 'glass_r_acuity_nv')); ?>>
                                            <?php $__errorArgs = ['r_acuity_nv'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="glass_r_acuity_nv-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="app-glass-eye-card">
                        <h3 class="h6 app-glass-eye-heading">Left eye (OS)</h3>
                        <div class="table-responsive">
                            <table class="table table-sm app-glass-eye-table align-middle mb-0">
                                <caption class="visually-hidden">Left eye (OS) distance and near vision powers</caption>
                                <thead><tr><th scope="col">Vision</th><th scope="col">SPH</th><th scope="col">CYL</th><th scope="col">Axis</th><th scope="col">V/A</th></tr></thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">DV</th>
                                        <td>
                                            <label class="visually-hidden" for="glass_l_sphere_dv">Left eye (OS) DV SPH</label>
                                            <input class="<?php echo e(inputClass('l_sphere_dv', 'form-control form-control-sm text-center')); ?>" id="glass_l_sphere_dv" name="l_sphere_dv" type="text" maxlength="30" value="<?php echo e(getValue('l_sphere_dv', $record)); ?>" <?php echo e(inputAttrs('l_sphere_dv', 'glass_l_sphere_dv')); ?>>
                                            <?php $__errorArgs = ['l_sphere_dv'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="glass_l_sphere_dv-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </td>
                                        <td>
                                            <label class="visually-hidden" for="glass_l_cylinder_dv">Left eye (OS) DV CYL</label>
                                            <input class="<?php echo e(inputClass('l_cylinder_dv', 'form-control form-control-sm text-center')); ?>" id="glass_l_cylinder_dv" name="l_cylinder_dv" type="text" maxlength="30" value="<?php echo e(getValue('l_cylinder_dv', $record)); ?>" <?php echo e(inputAttrs('l_cylinder_dv', 'glass_l_cylinder_dv')); ?>>
                                            <?php $__errorArgs = ['l_cylinder_dv'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="glass_l_cylinder_dv-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </td>
                                        <td>
                                            <label class="visually-hidden" for="glass_l_axis_dv">Left eye (OS) DV Axis</label>
                                            <input class="<?php echo e(inputClass('l_axis_dv', 'form-control form-control-sm text-center')); ?>" id="glass_l_axis_dv" name="l_axis_dv" type="text" maxlength="30" value="<?php echo e(getValue('l_axis_dv', $record)); ?>" <?php echo e(inputAttrs('l_axis_dv', 'glass_l_axis_dv')); ?>>
                                            <?php $__errorArgs = ['l_axis_dv'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="glass_l_axis_dv-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </td>
                                        <td>
                                            <label class="visually-hidden" for="glass_l_acuity_dv">Left eye (OS) DV V/A</label>
                                            <input class="<?php echo e(inputClass('l_acuity_dv', 'form-control form-control-sm text-center')); ?>" id="glass_l_acuity_dv" name="l_acuity_dv" type="text" maxlength="30" value="<?php echo e(getValue('l_acuity_dv', $record)); ?>" <?php echo e(inputAttrs('l_acuity_dv', 'glass_l_acuity_dv')); ?>>
                                            <?php $__errorArgs = ['l_acuity_dv'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="glass_l_acuity_dv-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">NV</th>
                                        <td>
                                            <label class="visually-hidden" for="glass_l_sphere_nv">Left eye (OS) NV SPH</label>
                                            <input class="<?php echo e(inputClass('l_sphere_nv', 'form-control form-control-sm text-center')); ?>" id="glass_l_sphere_nv" name="l_sphere_nv" type="text" maxlength="30" value="<?php echo e(getValue('l_sphere_nv', $record)); ?>" <?php echo e(inputAttrs('l_sphere_nv', 'glass_l_sphere_nv')); ?>>
                                            <?php $__errorArgs = ['l_sphere_nv'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="glass_l_sphere_nv-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </td>
                                        <td>
                                            <label class="visually-hidden" for="glass_l_cylinder_nv">Left eye (OS) NV CYL</label>
                                            <input class="<?php echo e(inputClass('l_cylinder_nv', 'form-control form-control-sm text-center')); ?>" id="glass_l_cylinder_nv" name="l_cylinder_nv" type="text" maxlength="30" value="<?php echo e(getValue('l_cylinder_nv', $record)); ?>" <?php echo e(inputAttrs('l_cylinder_nv', 'glass_l_cylinder_nv')); ?>>
                                            <?php $__errorArgs = ['l_cylinder_nv'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="glass_l_cylinder_nv-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </td>
                                        <td>
                                            <label class="visually-hidden" for="glass_l_axis_nv">Left eye (OS) NV Axis</label>
                                            <input class="<?php echo e(inputClass('l_axis_nv', 'form-control form-control-sm text-center')); ?>" id="glass_l_axis_nv" name="l_axis_nv" type="text" maxlength="30" value="<?php echo e(getValue('l_axis_nv', $record)); ?>" <?php echo e(inputAttrs('l_axis_nv', 'glass_l_axis_nv')); ?>>
                                            <?php $__errorArgs = ['l_axis_nv'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="glass_l_axis_nv-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </td>
                                        <td>
                                            <label class="visually-hidden" for="glass_l_acuity_nv">Left eye (OS) NV V/A</label>
                                            <input class="<?php echo e(inputClass('l_acuity_nv', 'form-control form-control-sm text-center')); ?>" id="glass_l_acuity_nv" name="l_acuity_nv" type="text" maxlength="30" value="<?php echo e(getValue('l_acuity_nv', $record)); ?>" <?php echo e(inputAttrs('l_acuity_nv', 'glass_l_acuity_nv')); ?>>
                                            <?php $__errorArgs = ['l_acuity_nv'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="glass_l_acuity_nv-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="row g-3">
            <?php if($settings['glass_pd_enabled']): ?>
                <div class="col-sm-4 col-lg-3">
                    <label class="form-label" for="glass_pd">I.P.D (mm)</label>
                    <input class="<?php echo e(inputClass('pd')); ?>" id="glass_pd" name="pd" type="text" maxlength="30" value="<?php echo e(getValue('pd', $record)); ?>" <?php echo e(inputAttrs('pd', 'glass_pd')); ?>>
                    <?php $__errorArgs = ['pd'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="glass_pd-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            <?php endif; ?>
            <?php if($settings['glass_lens_options_enabled']): ?>
                <fieldset class="col-sm-8 col-lg-9">
                    <legend class="form-label">Glasses and use</legend>
                    <div class="d-flex flex-wrap gap-3 pt-1">
                        <?php $__currentLoopData = $lensOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="form-check">
                                <input class="<?php echo e(inputClass('lens_options', 'form-check-input')); ?>" id="glass_lens_<?php echo e($value); ?>" name="lens_options[]" type="checkbox" value="<?php echo e($value); ?>" <?php echo e(getChecked($value, 'lens_options', $record, [])); ?> <?php echo e(inputAttrs('lens_options', 'glass_lens_'.$value)); ?>>
                                <label class="form-check-label" for="glass_lens_<?php echo e($value); ?>"><?php echo e($label); ?></label>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php $__errorArgs = ['lens_options'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <?php $__errorArgs = ['lens_options.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </fieldset>
            <?php endif; ?>
            <?php if($settings['glass_disclaimer_enabled']): ?>
                <div class="col-12">
                    <label class="form-label" for="glass_disclaimer">Remarks</label>
                    <textarea class="<?php echo e(inputClass('disclaimer')); ?>" id="glass_disclaimer" name="disclaimer" rows="2" maxlength="2000" <?php echo e(inputAttrs('disclaimer', 'glass_disclaimer')); ?>><?php echo e(getValue('disclaimer', $record)); ?></textarea>
                    <?php $__errorArgs = ['disclaimer'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="glass_disclaimer-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="d-flex flex-wrap gap-2 mt-3">
            <button class="btn btn-primary" type="submit"><i class="fa-solid fa-floppy-disk me-2" aria-hidden="true"></i><?php echo e($submitLabel); ?> glass prescription</button>
            <a class="btn btn-warning" href="<?php echo e(route($routePrefix.'.index')); ?>">Cancel</a>
        </div>
    </form>

    <?php if($canCreatePatient): ?>
        <?php echo $__env->make('hospital::patients.modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Prescription\resources\views\glass-prescriptions\form.blade.php ENDPATH**/ ?>