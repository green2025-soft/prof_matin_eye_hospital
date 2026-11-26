<?php $__env->startSection('prescription-actions'); ?>
    <?php if($canCreate): ?>
        <a class="btn btn-info text-danger btn-sm" href="<?php echo e($copyUrl); ?>"><i class="fa-solid fa-copy me-2"></i>Use as new</a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('prescription-content'); ?>
    <div class="app-prescription-body">
        <aside class="app-prescription-clinical">
            <?php if($chiefComplaints !== null): ?>
                <?php echo $__env->make('prescription::prints.selected-notes', [
                    'heading' => 'C/C :',
                    'className' => '',
                    'text' => $prescription->chief_complaints,
                    'details' => $chiefComplaints,
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
            <?php if($historyNotes !== null): ?>
                <?php echo $__env->make('prescription::prints.history', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
            <?php if($examinationNotes !== null): ?>
                <?php echo $__env->make('prescription::prints.examination', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
            <?php if($investigationNotes->isNotEmpty()): ?>
                <section class="">
                    <h3>Investigation :</h3>
                    <ul>
                        <?php $__currentLoopData = $investigationNotes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $investigation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($investigation->name); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </section>
            <?php endif; ?>
            <?php if($diagnosisNotes !== null): ?>
                <?php echo $__env->make('prescription::prints.selected-notes', [
                    'heading' => 'Diagnosis :',
                    'className' => '',
                    'text' => $prescription->diagnosis,
                    'details' => $diagnosisNotes,
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
        </aside>

        <div class="app-prescription-treatment">
            <dl class="row g-1 mb-2">
                <dt class="col-4 text-break">
                    <?php echo e(filled($prescription->treatment_title) ? $prescription->treatment_title : 'Treatment Plan'); ?>

                </dt>
                <dd class="col-8 app-prescription-text mb-0"><?php echo e($prescription->treatment_value?':':''); ?> <?php echo e($prescription->treatment_value); ?></dd>
            </dl>
            
            <h5><i class="fa-solid fa-prescription" aria-hidden="true"></i></h5>
            <ol class="app-prescription-medicine-list">

                <?php $__empty_1 = true; $__currentLoopData = $medicines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $medicine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <li>
                        <h3>
                            <?php echo e($medicine->medicine_name); ?>

                            <?php if(filled($medicine->generic?->name)): ?>
                                <span class="fw-normal">(<?php echo e($medicine->generic->name); ?>)</span>
                            <?php endif; ?>
                        </h3>
                        <p class="app-prescription-text"><?php echo e($medicine->frequency); ?> <?php echo e($medicine->dose?$medicine->dose.' করে, ':''); ?> <?php echo e($medicine->instructions?$medicine->instructions.',':''); ?> <?php echo e($medicine->duration); ?>। </p>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <li class="list-unstyled text-body-secondary">No medicines prescribed.</li>
                <?php endif; ?>
            </ol>

            <?php if($adviceNotes !== null): ?>
                <?php echo $__env->make('prescription::prints.selected-notes', [
                    'heading' => 'Advice',
                    'text' => $prescription->advice,
                    'details' => $adviceNotes,
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
            <?php if($showFollowUp): ?>
                <?php echo $__env->make('prescription::prints.follow-up', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('prescription::prints.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Prescription\resources\views\prescriptions\show.blade.php ENDPATH**/ ?>