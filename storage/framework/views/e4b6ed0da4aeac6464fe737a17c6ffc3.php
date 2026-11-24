<?php
    $productType = request()->attributes->get('pharmacy_product_type', 'medicine');
?>


<?php $__env->startSection('title', ucfirst($productType).' Damage / Wastage'); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => 'New '.ucfirst($productType).' Damage / Wastage'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <form class="app-card p-3 p-md-4" action="<?php echo e($formAction); ?>" method="POST" data-pharmacy-entry data-type="damage" data-vat-enabled="0">
        <?php echo csrf_field(); ?>
        <input id="pharmacy_product_type" name="product_type" type="hidden" value="<?php echo e($productType); ?>">

        <div class="row g-3 mb-3">
            <div class="col-sm-6 col-lg-3">
                <?php echo $__env->make('pharmacy::entries.partials.date-field', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
            <div class="col-lg-6">
                <label class="form-label" for="reason">Reason <span class="text-danger" aria-hidden="true">*</span></label>
                <input class="<?php echo e(inputClass('reason')); ?>" id="reason" name="reason" type="text" value="<?php echo e(getValue('reason', $entry)); ?>" maxlength="500" required <?php echo e(inputAttrs('reason')); ?>>
                <?php $__errorArgs = ['reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback d-block" id="reason-error"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
            <h2 class="h6 mb-0">Items</h2>
            <button class="btn btn-outline-primary btn-sm" type="button" data-pharmacy-add-line>
                <i class="fa-solid fa-plus me-1" aria-hidden="true"></i>Add item
            </button>
        </div>
        <?php $__errorArgs = ['lines'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="alert alert-danger"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <div data-pharmacy-lines>
            <?php $__currentLoopData = ($lineInputs ?: [['quantity' => 1]]); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $values): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make('pharmacy::wastage.item-line', [
                    'lineIndex' => $index,
                    'lineValues' => $values,
                    'variant' => $variants->get($values['variant_id'] ?? null),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <template data-pharmacy-line-template>
            <?php echo $__env->make('pharmacy::wastage.item-line', [
                'lineIndex' => '__INDEX__',
                'lineValues' => ['quantity' => 1],
                'variant' => null,
            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </template>

        <div class="row g-3 mt-1">
            <div class="col-lg-6">
                <?php echo $__env->make('pharmacy::entries.partials.notes-field', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>
        <?php echo $__env->make('pharmacy::entries.partials.form-actions', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\wastage\form.blade.php ENDPATH**/ ?>