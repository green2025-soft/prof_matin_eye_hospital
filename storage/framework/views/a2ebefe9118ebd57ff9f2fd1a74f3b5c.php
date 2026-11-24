<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle, 'description' => 'Profile, professional details, and consultation fees.'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="d-flex flex-wrap gap-2 mb-4">
        <a class="btn btn-outline-secondary btn-sm" href="<?php echo e(route($routePrefix.'.index')); ?>"><i class="fa-solid fa-arrow-left me-2" aria-hidden="true"></i>Doctors</a>
        <?php if(isset($permissions['doctors.update']) && auth()->user()->can('update', $doctor)): ?>
            <a class="btn btn-primary btn-sm" href="<?php echo e(route($routePrefix.'.edit', $doctor)); ?>"><i class="fa-solid fa-pen-to-square me-2" aria-hidden="true"></i>Edit</a>
        <?php endif; ?>
    </div>

    <div class="row g-3 align-items-start">
        <div class="col-lg-4">
            <section class="app-card p-3 p-md-4" aria-labelledby="doctor-profile-heading">
                <?php if($doctor->picture_url): ?>
                    <img class="rounded object-fit-cover mb-3" src="<?php echo e($doctor->picture_url); ?>" alt="Picture of <?php echo e($doctor->name); ?>" width="120" height="120">
                <?php else: ?>
                    <span class="app-mark app-mark-large mb-3" aria-hidden="true"><i class="fa-solid fa-user-doctor"></i></span>
                <?php endif; ?>
                <h2 class="h5 text-break mb-1" id="doctor-profile-heading"><?php echo e($doctor->name); ?></h2>
                <p class="small text-break mb-3" lang="bn"><span class="text-body-secondary">ডাক্তার নাম:</span> <?php echo e($doctor->bn_name ?? '-'); ?></p>

                <dl class="row gy-2 small mb-0">
                    <dt class="col-sm-4">Doctor ID</dt>
                    <dd class="col-sm-8 text-break mb-0"><?php echo e($doctor->doctor_id ?? '-'); ?></dd>
                    <dt class="col-sm-4">Designation</dt>
                    <dd class="col-sm-8 text-break mb-0"><?php echo e($doctor->designation ?? '-'); ?></dd>
                    <dt class="col-sm-4">Mobile no.</dt>
                    <dd class="col-sm-8 text-break mb-0"><?php echo e($doctor->mobile_no); ?></dd>
                    <dt class="col-sm-4">Email</dt>
                    <dd class="col-sm-8 text-break mb-0"><?php echo e($doctor->email); ?></dd>
                    <dt class="col-sm-4">Gender</dt>
                    <dd class="col-sm-8 mb-0"><?php echo e($genderLabel); ?></dd>
                    <dt class="col-sm-4">Blood group</dt>
                    <dd class="col-sm-8 mb-0"><?php echo e($bloodGroupLabel); ?></dd>
                </dl>
                <div class="border-top mt-3 pt-3">
                    <h3 class="h6">Address</h3>
                    <p class="small text-break mb-0"><?php echo e($doctor->address ?? '-'); ?></p>
                </div>
            </section>
        </div>

        <div class="col-lg-8">
            <section class="app-card p-3 p-md-4 mb-3" aria-labelledby="doctor-professional-heading">
                <h2 class="h6 mb-3" id="doctor-professional-heading">Professional details</h2>
                <dl class="row gy-2 small mb-0">
                    <dt class="col-sm-4">Department</dt>
                    <dd class="col-sm-8 text-break mb-0"><?php echo e($doctor->department?->name ?? '-'); ?></dd>
                    <dt class="col-sm-4">Specialist</dt>
                    <dd class="col-sm-8 text-break mb-0"><?php echo e($doctor->specialist ?? '-'); ?></dd>
                    <dt class="col-sm-4" lang="bn">স্পেশালিস্ট</dt>
                    <dd class="col-sm-8 text-break mb-0" lang="bn"><?php echo e($doctor->bn_specialist ?? '-'); ?></dd>
                    <dt class="col-sm-4">BMDC registration</dt>
                    <dd class="col-sm-8 text-break mb-0"><?php echo e($doctor->bmdc_reg ?? '-'); ?></dd>
                    <dt class="col-sm-4" lang="bn">বিএমডিসি রেজি</dt>
                    <dd class="col-sm-8 text-break mb-0" lang="bn"><?php echo e($doctor->bn_bmdc_reg ?? '-'); ?></dd>
                </dl>

                <div class="row g-3 border-top mt-3 pt-3">
                    <div class="col-md-6">
                        <h3 class="small fw-semibold text-body-secondary">Education / degree</h3>
                        <p class="small text-break mb-0"><?php echo e($doctor->education_degree ?? '-'); ?></p>
                    </div>
                    <div class="col-md-6" lang="bn">
                        <h3 class="small fw-semibold text-body-secondary">শিক্ষা/ডিগ্রী</h3>
                        <p class="small text-break mb-0"><?php echo e($doctor->bn_education_degree ?? '-'); ?></p>
                    </div>
                    <div class="col-md-6">
                        <h3 class="small fw-semibold text-body-secondary">Current employment</h3>
                        <p class="small text-break mb-0"><?php echo e($doctor->current_employment ?? '-'); ?></p>
                    </div>
                    <div class="col-md-6" lang="bn">
                        <h3 class="small fw-semibold text-body-secondary">বর্তমান চাকরি</h3>
                        <p class="small text-break mb-0"><?php echo e($doctor->bn_current_employment ?? '-'); ?></p>
                    </div>
                </div>
            </section>

            <section class="app-card p-3 p-md-4" aria-labelledby="doctor-fees-heading">
                <h2 class="h6 mb-3" id="doctor-fees-heading">Fees</h2>
                <dl class="row g-3 mb-0">
                    <div class="col-sm-6">
                        <dt class="small text-body-secondary mb-1">Consultation fee</dt>
                        <dd class="fw-semibold mb-0"><?php echo e($doctor->consultation_fee); ?></dd>
                    </div>
                    <div class="col-sm-6">
                        <dt class="small text-body-secondary mb-1">Follow-up fee</dt>
                        <dd class="fw-semibold mb-0"><?php echo e($doctor->follow_up_fee); ?></dd>
                    </div>
                </dl>
                <div class="border-top mt-3 pt-3">
                    <h3 class="h6">Pad footer text</h3>
                    <p class="small text-break mb-0"><?php echo e($doctor->pad_footer_text ?? '-'); ?></p>
                </div>
            </section>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Hospital\resources\views\doctors\show.blade.php ENDPATH**/ ?>