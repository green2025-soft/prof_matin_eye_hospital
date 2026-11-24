<?php $__env->startSection('title', ucfirst($entry->product_type).' Sales Return'); ?>

<?php $__env->startSection('content'); ?>
    <div class="app-print-hidden">
        <?php echo $__env->make('core::partials.heading', ['title' => ucfirst($entry->product_type).' '.$entry->number], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div class="d-flex flex-wrap gap-2 mb-3">
            <a class="btn btn-outline-secondary btn-sm" href="<?php echo e(route($routePrefix.'.index', ['product_type' => $entry->product_type])); ?>">
                <i class="fa-solid fa-arrow-left me-2" aria-hidden="true"></i>Back
            </a>
            <button class="btn btn-primary btn-sm" type="button" data-print>
                <i class="fa-solid fa-print me-2" aria-hidden="true"></i>Print
            </button>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $entry)): ?>
                <a class="btn btn-outline-primary btn-sm" href="<?php echo e(route($routePrefix.'.edit', [$resourceParameter => $entry, 'product_type' => $entry->product_type])); ?>"><i class="fa-solid fa-pen-to-square me-2" aria-hidden="true"></i>Edit</a>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $entry)): ?>
                <form action="<?php echo e(route($routePrefix.'.destroy', [$resourceParameter => $entry, 'product_type' => $entry->product_type])); ?>" method="POST" data-confirm="Delete this sales return and reverse its stock and amounts?">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button class="btn btn-outline-danger btn-sm" type="submit"><i class="fa-solid fa-trash-can me-2" aria-hidden="true"></i>Delete</button>
                </form>
            <?php endif; ?>
            <?php if($canVoid): ?>
                <form action="<?php echo e(route($routePrefix.'.void', [$resourceParameter => $entry, 'product_type' => $entry->product_type])); ?>" method="POST" data-confirm="Void this sales return and reverse its stock and amounts?">
                    <?php echo csrf_field(); ?>
                    <button class="btn btn-outline-danger btn-sm" type="submit">Void</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <?php
        $invoiceTitle = 'Sales Return';
        $invoiceStatus = $entry->voided_at ? 'VOID' : 'POSTED';
    ?>

    <article class="app-card app-invoice-document app-clinical-invoice app-pharmacy-invoice" aria-labelledby="pharmacy-invoice-title">
        <?php echo $__env->make('pharmacy::entries.partials.invoice-header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="app-pharmacy-invoice-parties">
            <div>
                <h2 class="app-clinical-invoice-section-title">Customer</h2>
                <p class="fw-semibold mb-1"><?php echo e($customer['name'] ?? '—'); ?></p>
                <p class="mb-1">Patient ID: <?php echo e($customer['patient_id'] ?? '—'); ?></p>
                <?php if($customer['mobile_no'] ?? ''): ?>
                    <p class="mb-1">Contact: <?php echo e($customer['mobile_no']); ?></p>
                <?php endif; ?>
                <?php if($customer['address'] ?? ''): ?>
                    <p class="app-pharmacy-invoice-note mb-0"><?php echo e($customer['address']); ?></p>
                <?php endif; ?>
            </div>
            <div>
                <?php echo $__env->make('pharmacy::entries.partials.invoice-meta', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->make('pharmacy::entries.partials.invoice-payment-meta', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <dl class="app-clinical-invoice-meta mb-0">
                    <div><dt>Original invoice</dt><dd><?php echo e($entry->sale->number); ?></dd></div>
                </dl>
            </div>
        </div>

        <?php echo $__env->make('pharmacy::entries.partials.invoice-financial-items', ['showDiscount' => false, 'showVat' => false], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="app-pharmacy-invoice-summary">
            <div>
                <?php if($entry->notes): ?>
                    <h2 class="app-clinical-invoice-section-title">Notes</h2>
                    <p class="app-pharmacy-invoice-note"><?php echo e($entry->notes); ?></p>
                <?php endif; ?>
            </div>
            <div class="app-clinical-invoice-totals">
                <dl class="mb-0">
                    <div class="app-clinical-invoice-total">
                        <dt>Original invoice credit</dt>
                        <dd><?php echo e(moneyDecimal($entry->total)); ?></dd>
                    </div>
                    <div>
                        <dt>Cash refunded</dt>
                        <dd><?php echo e(moneyDecimal($entry->initial_paid)); ?></dd>
                    </div>
                </dl>
            </div>
        </div>

        <?php echo $__env->make('pharmacy::entries.partials.invoice-footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </article>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\sale-returns\show.blade.php ENDPATH**/ ?>