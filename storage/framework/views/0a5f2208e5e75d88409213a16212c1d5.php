<?php $__env->startSection('prescription-content'); ?>
    <section class="app-glass-print-body" aria-labelledby="glass-print-heading">
        
        <div class="col-10 mx-auto mb-3">
            <img class="img-fluid w-100" src="<?php echo e(asset('glass-prescription-axis.svg')); ?>" width="720" height="210"
                alt="Right eye (R.E.) and left eye (L.E.) axis diagrams">
        </div>
        <div class="row g-2 flex-nowrap">
            <div class="col-6">
                <div class="table-responsive">
                    <table class="table table-bordered app-glass-print-table align-middle">
                        <caption class="caption-top text-center fw-semibold text-body py-1">Right (R.E.)</caption>
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
                                <td><?php echo e($record->r_sphere_dv ?? '--'); ?></td>
                                <td><?php echo e($record->r_cylinder_dv ?? '--'); ?></td>
                                <td><?php echo e($record->r_axis_dv ?? '--'); ?></td>
                                <td><?php echo e($record->r_acuity_dv ?? '--'); ?></td>
                            </tr>
                            <tr>
                                <th scope="row">NV</th>
                                <td><?php echo e($record->r_sphere_nv ?? '--'); ?></td>
                                <td><?php echo e($record->r_cylinder_nv ?? '--'); ?></td>
                                <td><?php echo e($record->r_axis_nv ?? '--'); ?></td>
                                <td><?php echo e($record->r_acuity_nv ?? '--'); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-6">
                <div class="table-responsive">
                    <table class="table table-bordered app-glass-print-table align-middle">
                        <caption class="caption-top text-center fw-semibold text-body py-1">Left (L.E.)</caption>
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
                                <td><?php echo e($record->l_sphere_dv ?? '--'); ?></td>
                                <td><?php echo e($record->l_cylinder_dv ?? '--'); ?></td>
                                <td><?php echo e($record->l_axis_dv ?? '--'); ?></td>
                                <td><?php echo e($record->l_acuity_dv ?? '--'); ?></td>
                            </tr>
                            <tr>
                                <th scope="row">NV</th>
                                <td><?php echo e($record->l_sphere_nv ?? '--'); ?></td>
                                <td><?php echo e($record->l_cylinder_nv ?? '--'); ?></td>
                                <td><?php echo e($record->l_axis_nv ?? '--'); ?></td>
                                <td><?php echo e($record->l_acuity_nv ?? '--'); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php if($settings['glass_pd_enabled'] && filled($record->pd)): ?>
            <p class="app-glass-print-pd"><strong>I.P.D:</strong> <?php echo e($record->pd); ?> mm</p>
        <?php endif; ?>
        <?php if($settings['glass_lens_options_enabled'] && ! empty($record->lens_options)): ?>
            <section aria-labelledby="glass-print-lens-heading">
                <h3 class="h6" id="glass-print-lens-heading">Glasses and Use</h3>
                <ul class="app-prescription-lens-list list-unstyled d-flex flex-wrap column-gap-3 row-gap-1">
                    <?php $__currentLoopData = $record->lens_options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><i class="fa-regular fa-square-check"></i> <?php echo e($lensOptions[$option] ?? $option); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </section>
        <?php endif; ?>
        <?php if($settings['glass_disclaimer_enabled'] && filled($record->disclaimer)): ?>
            <section class="app-prescription-advice" aria-labelledby="glass-print-disclaimer-heading">
                <h3 class="h6" id="glass-print-disclaimer-heading">Remarks</h3>
                <ul class="mb-0 ps-3">
                    <?php $__currentLoopData = preg_split('/\R/', $record->disclaimer); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $remark): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(blank($remark)) continue; ?>
                        <li><?php echo e(trim($remark)); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </section>
        <?php endif; ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('prescription::prints.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Prescription\resources\views\glass-prescriptions\show.blade.php ENDPATH**/ ?>