<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $hasClinicalFields = $settings['general_chief_complaints_enabled']
            || $settings['general_history_enabled']
            || $settings['general_examination_enabled']
            || $settings['general_diagnosis_enabled']
            || $settings['general_investigations_enabled'];
        $medicineRows = $hasOldMedicines ? $medicineInputs : $record->medicines;
    ?>

    <div class="app-prescription-workspace d-flex flex-column">
        <div class="app-page-heading flex-shrink-0 mb-0">
            <h1 class="h4 mb-0"><?php echo e($pageTitle); ?></h1>
            <div class="d-flex flex-wrap gap-2">

                <a class="btn btn-outline-success " href="<?php echo e(route($routePrefix.'.index')); ?>">
                    <i class="fa-solid fa-clock-rotate-left me-2" aria-hidden="true"></i>Prescription List
                </a>
            </div>
        </div>

        <form class="d-flex flex-column" id="prescription-form" action="<?php echo e($formAction); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php if($formMethod !== 'POST'): ?>
                <?php echo method_field($formMethod); ?>
            <?php endif; ?>

            <div class="app-card app-prescription-editor d-flex flex-column overflow-hidden">
                <header class="app-prescription-editor-header d-flex flex-shrink-0 align-items-center
                    justify-content-between gap-3">
                    <div>
                        <h2><?php echo e(request()->attributes->get('application_name')); ?></h2>
                        <?php if(filled(request()->attributes->get('branch_name'))): ?>
                            <p><?php echo e(request()->attributes->get('branch_name')); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="app-prescription-editor-title d-flex flex-shrink-0 align-items-center">
                        <i class="fa-solid fa-prescription d-none d-sm-inline" aria-hidden="true"></i>
                    </div>
                </header>

                <div class="app-prescription-editor-identity flex-shrink-0">
                    <?php echo $__env->make('prescription::partials.identity', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>

                <div class="nav nav-tabs flex-shrink-0 px-3 d-lg-none" role="tablist"
                    aria-label="Prescription sections">
                    <button class="nav-link active" id="prescription-treatment-tab" type="button" role="tab"
                        data-bs-toggle="tab" data-bs-target="#prescription-treatment-pane"
                        aria-controls="prescription-treatment-pane" aria-selected="true">Rx &amp; advice</button>
                    <?php if($hasClinicalFields): ?>
                        <button class="nav-link" id="prescription-notes-tab" type="button" role="tab"
                            data-bs-toggle="tab" data-bs-target="#prescription-notes-pane"
                            aria-controls="prescription-notes-pane" aria-selected="false">Clinical notes</button>
                    <?php endif; ?>
                </div>

                <div class="app-prescription-editor-body tab-content row g-0 flex-nowrap flex-grow-1">
                    <?php if($hasClinicalFields): ?>
                        <aside class="app-prescription-editor-notes tab-pane col-12 col-lg d-lg-block overflow-auto"
                            id="prescription-notes-pane"
                            role="tabpanel" aria-labelledby="prescription-notes-tab" tabindex="0">
                            <?php if($settings['general_chief_complaints_enabled']): ?>
                                <?php echo $__env->make('prescription::prescriptions.note-field', [
                                    'noteField' => 'chief_complaints',
                                    'noteLabel' => 'C/C',
                                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php endif; ?>
                            <?php if($settings['general_history_enabled']): ?>
                                <?php echo $__env->make('prescription::prescriptions.history-field', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php endif; ?>
                            <?php if($settings['general_examination_enabled']): ?>
                                <?php echo $__env->make('prescription::prescriptions.examination-field', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php endif; ?>
                             <?php if($settings['general_investigations_enabled']): ?>
                                <?php echo $__env->make('prescription::prescriptions.investigations-field', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php endif; ?>
                            <?php if($settings['general_diagnosis_enabled']): ?>
                                <?php echo $__env->make('prescription::prescriptions.note-field', [
                                    'noteField' => 'diagnosis',
                                    'noteLabel' => 'Diagnosis',
                                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php endif; ?>

                        </aside>
                    <?php endif; ?>

                    <section class="app-prescription-editor-treatment tab-pane show active col-12 col-lg d-lg-flex
                        flex-column position-relative overflow-auto"
                        id="prescription-treatment-pane" role="tabpanel"
                        aria-labelledby="prescription-treatment-tab" tabindex="0">
                        <img class="app-prescription-watermark" src="<?php echo e(asset('prescription-bg.png')); ?>"
                            width="500" height="500" alt="">


                        <div class="row g-2 align-items-start mb-3">
                            <div class="col-4 col-md-4">
                                <label class="form-label" for="treatment_title"><b>Title</b></label>
                                <input class="<?php echo e(inputClass('treatment_title')); ?>" id="treatment_title" type="text"
                                    name="treatment_title" maxlength="255" required
                                    value="<?php echo e(getValue('treatment_title', $record, 'Treatment Plan')); ?>"
                                    <?php echo e(inputAttrs('treatment_title', 'treatment_title')); ?>>
                                <?php $__errorArgs = ['treatment_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block" id="treatment_title-error"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-8 col-md-8">
                                <label class="form-label" for="treatment_value"><b>Value</b></label>
                                <textarea class="<?php echo e(inputClass('treatment_value')); ?>" id="treatment_value"
                                    name="treatment_value" rows="1"
                                    <?php echo e(inputAttrs('treatment_value', 'treatment_value')); ?>><?php echo e(getValue('treatment_value', $record)); ?></textarea>
                                <?php $__errorArgs = ['treatment_value'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block" id="treatment_value-error"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="app-prescription-section-heading d-flex align-items-center gap-2">
                            <h2 class="app-prescription-rx mb-0"><i class="fa-solid fa-prescription" aria-hidden="true"></i></h2>
                            <button class="btn btn-outline-primary btn-sm" type="button"
                                data-bs-toggle="modal" data-bs-target="#prescription-rx-modal"
                                aria-keyshortcuts="F2" title="Medicines (F2)"
                                aria-label="Add or edit medicines">
                                <i class="fa-solid fa-plus" aria-hidden="true"></i>
                            </button>
                            <span class="small text-body-secondary ms-auto" id="prescription-medicine-count"
                                aria-live="polite" aria-atomic="true"></span>
                        </div>
                         <ol class="app-prescription-rx-preview list-unstyled mb-0" id="prescription-medicine-preview"></ol>
                        <p class="small text-body-secondary" id="prescription-medicine-empty">No medicines added.</p>





                        <div class="app-prescription-editor-advice">
                            <?php if($settings['general_advice_enabled']): ?>
                                <?php echo $__env->make('prescription::prescriptions.note-field', [
                                    'noteField' => 'advice',
                                    'noteLabel' => 'Advice',
                                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php endif; ?>
                            <?php if($settings['general_follow_up_enabled']): ?>
                                <?php echo $__env->make('prescription::prescriptions.follow-up-field', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php endif; ?>
                        </div>
                        <span class="app-prescription-pad-signature align-self-end pt-3 mt-auto">Signature</span>
                    </section>
                </div>
            </div>

            <div class="app-prescription-editor-actions d-flex flex-shrink-0 flex-wrap justify-content-end gap-2">
                <a class="btn btn-warning" href="<?php echo e(route($routePrefix.'.index')); ?>">Cancel</a>
                <button class="btn btn-primary px-4" type="submit">
                    <i class="fa-solid fa-floppy-disk me-2" aria-hidden="true"></i><?php echo e($submitLabel); ?> prescription
                </button>
            </div>
        </form>
    </div>

    <?php echo $__env->yieldPushContent('prescription-modals'); ?>
    <?php echo $__env->make('prescription::prescriptions.rx-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php if($canCreatePatient): ?>
        <?php echo $__env->make('hospital::patients.modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>

    <template id="prescription-medicine-template">
        <?php echo $__env->make('prescription::prescriptions.medicine-row', ['medicineIndex' => '__INDEX__', 'medicine' => []], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </template>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules/Prescription\resources/views/prescriptions/form.blade.php ENDPATH**/ ?>