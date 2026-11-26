<?php $__env->startSection('title', $documentTitle.' '.$referenceNumber); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-print-none">
        
        <div class="mb-3 text-end">
            <button class="btn btn-primary btn-sm" type="button" data-print
                data-print-config="<?php echo e(htmlJson($printConfiguration)); ?>">
                <i class="fa-solid fa-print me-2" aria-hidden="true"></i>Print
            </button>
            <?php if($canEdit): ?>
                <a class="btn btn-warning btn-sm" href="<?php echo e($editUrl); ?>"><i class="fa-solid fa-pen-to-square me-2" aria-hidden="true"></i>Edit</a>
            <?php endif; ?>
            <?php echo $__env->yieldContent('prescription-actions'); ?>
            <a class="btn btn-success btn-sm" href="<?php echo e($backUrl); ?>"><i class="fa-solid fa-arrow-left me-2" aria-hidden="true"></i>Back</a>
        </div>
    </div>

    <article class="<?php echo \Illuminate\Support\Arr::toCssClasses([
        'app-card app-invoice-document app-prescription-document',
        'app-prescription-paper-a5' => $paperSize === 'a5',
        'app-prescription-paper-custom' => $paperSize === 'custom',
    ]); ?>">

        <?php if($showHeader): ?>
            <div class="app-prescription-doctor">

                <div  lang="bn">
                    <h2><?php echo e($doctor?->bn_name); ?></h2>
                    <p class="app-prescription-text"><?php echo e($doctor?->bn_education_degree); ?></p>
                    <p><?php echo e($doctor?->bn_specialist); ?></p>
                    <p class="app-prescription-text"><?php echo e($doctor?->bn_current_employment); ?></p>
                    <?php if(filled($doctor?->bn_bmdc_reg)): ?><p>বিএমডিসি রেজি: <?php echo e($doctor->bn_bmdc_reg); ?></p><?php endif; ?>
                </div>
                <div class="app-prescription-doctor-bangla">
                    <h2><?php echo e($doctor?->name); ?></h2>
                    <p class="app-prescription-text"><?php echo e($doctor?->education_degree); ?></p>
                    <p><?php echo e($doctor?->designation); ?></p>
                    <p><?php echo e($doctor?->specialist); ?></p>
                    <p class="app-prescription-text"><?php echo e($doctor?->current_employment); ?></p>
                    <?php if(filled($doctor?->bmdc_reg)): ?><p>BMDC Reg: <?php echo e($doctor->bmdc_reg); ?></p><?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="app-prescription-identity">
            <div class="app-prescription-patient-barcode d-none">
                <?php if(filled($patient?->patient_id)): ?>
                    <svg class="app-prescription-barcode" data-patient-id="<?php echo e($patient->patient_id); ?>"
                        role="img" aria-label="Patient ID barcode <?php echo e($patient->patient_id); ?>"></svg>
                <?php endif; ?>
            </div>
            
        </div>

        

        <div class="app-prescription-patien">
            <div class="row gx-0">
                <div class="col-6">
                    <div><b>Pt. Name:</b> <?php echo e($patient?->name); ?></div>
                    <div><b>Age:</b> <?php echo e($patientAge); ?> <b> Sex:</b><?php echo e(ucfirst($patient?->gender ?? '')); ?></div>
                    <div><b>Mobile:</b> <?php echo e($patient?->mobile_no); ?> </div>
                </div>
                <div class="col-6 text-end">
                    <div><b>Patient ID:</b> <?php echo e($patient?->patient_id); ?></div>
                    <div><b><i class="fa-solid fa-prescription" aria-hidden="true"></i></b>  <?php echo e($referenceNumber); ?></div>
                    <div><b>Date:</b> <?php echo e(date('d-m-Y', strtotime($prescription->prescription_date))); ?></div>

                </div>
            </div>
        </div>

        <?php echo $__env->yieldContent('prescription-content'); ?>

        <footer class="app-prescription-footer">

        </footer>
         <?php if($showHeader): ?>
                <header class="app-prescription-letterhead">
                    <?php if($logoUrl): ?>
                        <img style="margin-left: -65px; margin-top: -50px" class="app-prescription-logo" src="<?php echo e($logoUrl); ?>" alt="">
                    <?php endif; ?>

                    <div>
                        <h2><?php echo e($company['name']); ?></h2>
                        <p style="width: 290px"><i class="fa-solid fa-location-dot"></i> <?php echo $company['address']; ?></p>
                        <p class="mb-0"><i class="fa-solid fa-phone"></i> <?php echo e($company['phone']); ?></p>
                        <p><i class="fa-solid fa-envelope"></i> <?php echo e($company['email']); ?></p>
                    </div>
                </header>
            <?php endif; ?>
    </article>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Prescription\resources\views\prints\layout.blade.php ENDPATH**/ ?>