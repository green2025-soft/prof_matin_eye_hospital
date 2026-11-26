<div class="modal fade app-prescription-section-modal" id="prescription-rx-modal"
    tabindex="-1" aria-labelledby="prescription-rx-modal-heading" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title h5" id="prescription-rx-modal-heading"><i class="fa-solid fa-prescription" aria-hidden="true"></i></h2>
                <div class="app-prescription-rx-search position-relative mx-auto">
                    <label class="visually-hidden" for="prescription-medicine-search">Search / Add medicine</label>
                    <select id="prescription-medicine-search" multiple
                        data-url="<?php echo e(route('pharmacy.products.options', ['type' => 'medicine'])); ?>"></select>
                </div>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="app-prescription-rx-options d-flex flex-wrap align-content-start gap-2 overflow-auto" id="prescription-rx-options"
                    aria-label="Medicine suggestions">
                    <p class="small text-body-secondary mb-0">Search or select a medicine above.</p>
                </div>
                <?php $__errorArgs = ['medicines'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback d-block mb-3"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <ol class="app-prescription-editor-medicines list-unstyled mb-0" id="prescription-medicines">
                    <?php $__currentLoopData = $medicineRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $medicineIndex => $medicine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo $__env->make('prescription::prescriptions.medicine-row', [
                            'medicineIndex' => $medicineIndex,
                            'medicine' => $medicine,
                        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ol>
            </div>
            <div class="modal-footer justify-content-between py-2">
                <small class="text-body-secondary">F2 Search · F4 Dose/Unit · F8 Done</small>
                <button class="btn btn-primary btn-sm" type="button" data-bs-dismiss="modal"
                    aria-keyshortcuts="F8" title="Close medicines (F8)">Done</button>
            </div>
        </div>
    </div>
    <div class="app-prescription-unit-palette bg-white border rounded shadow-sm" id="prescription-unit-palette"
        role="menu" aria-label="Choose a unit" hidden
        data-url="<?php echo e(route('prescription.options.selector', ['section' => 'medicine_unit'])); ?>">
        <button class="dropdown-item prescription-unit-choice" type="button" role="menuitem" value="">—</button>
        <?php $__currentLoopData = ['টা', 'চামচ', 'চা চামচ', 'বার', 'টি', 'স্প্রে', 'লাগাবেন', 'ফোটা', 'স্টিক', '±২']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unitChoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <button class="dropdown-item prescription-unit-choice" type="button" role="menuitem"
                value="<?php echo e($unitChoice); ?>"><?php echo e($unitChoice); ?></button>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Prescription\resources\views\prescriptions\rx-modal.blade.php ENDPATH**/ ?>