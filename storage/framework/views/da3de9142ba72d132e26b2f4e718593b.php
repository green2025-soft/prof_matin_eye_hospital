<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <div class="app-print-hidden">
        
        <div class="d-flex flex-wrap justify-content-end gap-2 mb-3">
            <a class="btn btn-outline-secondary btn-sm" href="<?php echo e($backUrl); ?>"><i class="fa-solid fa-arrow-left me-2" aria-hidden="true"></i>Back</a>
            <button class="btn btn-primary btn-sm" type="button" data-print><i class="fa-solid fa-print me-2" aria-hidden="true"></i>Print</button>
        </div>
    </div>

    <article class="app-card app-invoice-document app-clinical-invoice" aria-labelledby="clinical-invoice-title">
        <header class="app-clinical-invoice-header">
            <div class="app-clinical-invoice-brand">
                <?php if($logoUrl): ?>
                    <img class="app-clinical-invoice-logo" src="<?php echo e($logoUrl); ?>" alt="" width="110" height="110">
                <?php endif; ?>
                <div class="app-clinical-invoice-company-details">
                    <h2 class="app-clinical-invoice-company"><?php echo e($company['name']); ?></h2>
                    <?php if($company['address'] ?? ''): ?>
                        <p class="app-clinical-invoice-address invoice-body-font"><b><?php echo e($company['address']); ?></b></p>
                    <?php endif; ?>
                    <?php if($company['phone'] ?? ''): ?>
                    <div class="app-clinical-invoice-contact-line invoice-body-font">
                        <strong>HOTLINE : </strong><?php echo e($company['phone']); ?>

                    </div>
                    <?php endif; ?>
                    
                    <?php if($company['website'] ?? ''): ?>
                    <div class="app-clinical-invoice-contact-line invoice-body-font">
                        <strong>Web : </strong><?php echo e($company['website']); ?>

                    </div>
                    <?php endif; ?>
                    <?php if($company['tax_id'] ?? ''): ?>
                    <div class="app-clinical-invoice-contact-line invoice-body-font">
                        <strong>Tax ID : </strong><?php echo e($company['tax_id']); ?>

                    </div>
                    <?php endif; ?>
                </div>
                <div class="app-clinical-invoice-document-mark" aria-label="Invoice number <?php echo e($invoiceNumber); ?>">
                    <canvas class="app-clinical-invoice-qr"
                        data-qr-value="<?php echo e($company['name']); ?>&#10;<?php echo e($invoiceNumber); ?>&#10;<?php echo e($patient?->name ?? ''); ?>&#10;<?php echo e($patient?->patient_id ?? ''); ?>&#10;<?php echo e($patient?->mobile_no ?? ''); ?>"
                        role="img"
                        aria-label="Invoice QR code for <?php echo e($invoiceNumber); ?>">
                    </canvas>
                </div>
            </div>
            <div class="app-clinical-invoice-title-row text-center">
                <u><?php echo e($documentTitle); ?></u>
            </div>
        </header>
        <div class="invoice-patient-box invoice-body-font">
            <div class="info-row">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td style="width: 105px;">
                                <div class="info-label">Invoice No.</div>
                            </td>
                            <td>
                                <div class="info-value">:<b> <?php echo e($invoiceNumber); ?></b></div>
                            </td>
                            <td style="width: 75px;">
                                <div class="info-label">Date</div>
                            </td>
                            <td>
                                <div class="info-value">: <?php echo e($entryDate); ?></div>
                            </td>
                            <td style="width: 70px;">
                                <div class="info-label">Time</div>
                            </td>
                            <td>
                                <div class="info-value">: <?php echo e($entryTime); ?></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="info-label">P.Name</div>
                            </td>
                            <td colspan="3">
                                <div class="info-value">: <b><?php echo e($patient?->name ?? '-'); ?></b></div>
                            </td>

                            <td>
                                <div class="info-label">Age</div>
                            </td>
                            <td>
                                <div class="info-value">: <?php echo e($patient?->ageLabel() ?? '-'); ?></div>
                            </td>
                        </tr>
                        <tr>
                            <td><div class="info-label">Mobile</div></td>
                            <td><div class="info-value">: <?php echo e($patient?->mobile_no ?? '-'); ?></div></td>
                            <td><div class="info-label">P.ID</div></td>
                            <td><div class="info-value">: <?php echo e($patient?->patient_id ?? '-'); ?></div></td>
                            <td><div class="info-label">Gender</div></td>
                            <td><div class="info-value">: <?php echo e($patientGenderLabel); ?></div></td>
                        </tr>
                        <?php if(!isset($isDoctor)): ?>
                        <tr>
                            <td>
                                <div class="info-label">Ref. By</div>
                            </td>
                            <td colspan="5">
                                <div class="info-value">:  <?php echo e($doctor?->name ?? '-'); ?></div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>



<?php echo $__env->yieldContent('invoice-content'); ?>
        <footer class="app-clinical-invoice-footer">
            <div class="app-clinical-invoice-signature"><span>Authorized signature</span></div>
            <div class="app-clinical-invoice-printed">Printed on: <?php echo e($printedAt); ?></div>
            <div class="app-clinical-invoice-signature"><span>Prepared by: <?php echo e($printedBy); ?></span></div>
        </footer>
    </article>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules/Hospital\resources/views/invoices/show.blade.php ENDPATH**/ ?>