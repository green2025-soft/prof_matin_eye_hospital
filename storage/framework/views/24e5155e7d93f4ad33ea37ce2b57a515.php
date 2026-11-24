<?php $__env->startSection('title', ucfirst(request()->attributes->get('pharmacy_product_type', 'medicine')).' Sales Return'); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => ($entry->exists ? 'Edit ' : 'New ').ucfirst(request()->attributes->get('pharmacy_product_type', 'medicine')).' Sales Return'.($entry->exists ? ' — '.$entry->number : '')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php if (! ($entry->exists)): ?>
        <form id="pharmacy-return-source-form" method="GET" action="<?php echo e(route($routePrefix.'.create')); ?>" data-pharmacy-return-source>
            <input id="source_product_type" name="product_type" type="hidden" value="<?php echo e(request()->attributes->get('pharmacy_product_type', 'medicine')); ?>">
        </form>
    <?php endif; ?>

    <form
        class="app-card p-3 p-md-4"
        action="<?php echo e($formAction); ?>"
        method="POST"
        data-pharmacy-entry
        data-pharmacy-return
        data-type="sale_return"
        data-return-source-total="<?php echo e($source?->total ?? 0); ?>"
        data-return-source-returned="<?php echo e($source?->returned ?? 0); ?>"
        data-return-source-paid="<?php echo e($source?->paid ?? 0); ?>"
        data-return-source-refunded="<?php echo e($source?->refunded ?? 0); ?>"
        data-return-source-discount="<?php echo e($source?->settlement_discount ?? 0); ?>"
        data-return-edit-total="<?php echo e($entry->exists ? $entry->total : 0); ?>"
        data-return-edit-cash="<?php echo e($entry->exists ? $entry->initial_paid : 0); ?>"
    >
        <?php echo csrf_field(); ?>
        <?php if($formMethod !== 'POST'): ?>
            <?php echo method_field($formMethod); ?>
        <?php endif; ?>
        <input id="pharmacy_product_type" name="product_type" type="hidden" value="<?php echo e(request()->attributes->get('pharmacy_product_type', 'medicine')); ?>">
        <input name="sale_id" type="hidden" value="<?php echo e($source?->id); ?>">

        <section class="app-form-section mb-3" aria-labelledby="return-invoice-heading">
            <h2 class="app-form-section-title" id="return-invoice-heading">
                <span class="app-form-section-icon"><i class="fa-solid fa-arrow-rotate-left" aria-hidden="true"></i></span>
                Return Details
            </h2>
            <div class="row g-3 align-items-end">
                <div class="col-md-6 col-xl-4">
                    <label class="form-label" for="sale">Original sale invoice</label>
                    <?php if($entry->exists): ?>
                        <output class="form-control bg-body-tertiary" id="sale">
                            <?php echo e($source->number); ?>

                            <span class="small text-body-secondary ms-2"><?php echo e($source->entry_date->toDateString()); ?></span>
                        </output>
                    <?php else: ?>
                        <select class="<?php echo e(inputClass('sale_id', 'form-select')); ?>" id="sale" name="sale_id" form="pharmacy-return-source-form" data-ajax-select data-url="<?php echo e(route('pharmacy.sale-sources.options')); ?>" data-parent-select="source_product_type" data-parent-field="product_type" data-placeholder="Search invoice number" <?php echo e(inputAttrs('sale_id', 'sale')); ?>>
                            <option value=""></option>
                            <?php if($source): ?>
                                <option value="<?php echo e($source->id); ?>" selected><?php echo e($source->number); ?> / <?php echo e($source->entry_date->toDateString()); ?></option>
                            <?php endif; ?>
                        </select>
                    <?php endif; ?>
                    <?php $__errorArgs = ['sale_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="sale-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6 col-xl-3">
                    <label class="form-label" for="return_party">Customer</label>
                    <output class="form-control bg-body-tertiary text-break" id="return_party"><?php echo e($sourceCustomer['name'] ?? '—'); ?></output>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <?php echo $__env->make('pharmacy::entries.partials.date-field', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
                <div class="col-sm-6 col-xl-2">
                    <?php echo $__env->make('pharmacy::entries.partials.payment-type-field', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>
        </section>

        <?php if($source): ?>
            <?php echo $__env->make('pharmacy::sale-returns.items', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->make('pharmacy::entries.partials.form-actions', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php else: ?>
            <p class="small text-body-secondary bg-body-tertiary rounded p-3 mb-0">Select an invoice to load its returnable items.</p>
            <div class="app-form-actions border-top pt-3 mt-4">
                <a class="btn btn-warning" href="<?php echo e(route($routePrefix.'.index', $routeParameters)); ?>">Cancel</a>
            </div>
        <?php endif; ?>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\sale-returns\form.blade.php ENDPATH**/ ?>