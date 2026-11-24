<?php $__env->startSection('title', ucfirst($entry->product_type).' Stock Adjustment'); ?>

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
            <?php if($canVoid): ?>
                <form action="<?php echo e(route($routePrefix.'.void', [$resourceParameter => $entry, 'product_type' => $entry->product_type])); ?>" method="POST" data-confirm="Void this stock adjustment and reverse its stock and amounts?">
                    <?php echo csrf_field(); ?>
                    <button class="btn btn-outline-danger btn-sm" type="submit">Void</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <?php
        $invoiceTitle = 'Stock Adjustment';
        $invoiceStatus = $entry->voided_at ? 'VOID' : 'POSTED';
    ?>

    <article class="app-card app-invoice-document app-clinical-invoice app-pharmacy-invoice" aria-labelledby="pharmacy-invoice-title">
        <?php echo $__env->make('pharmacy::entries.partials.invoice-header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="app-pharmacy-invoice-parties">
            <div>
                <h2 class="app-clinical-invoice-section-title">Stock entry</h2>
                <p class="fw-semibold mb-1"><?php echo e($invoiceTitle); ?></p>
            </div>
            <div>
                <dl class="app-clinical-invoice-meta mb-0">
                    <div>
                        <dt>Date</dt>
                        <dd><?php echo e($entry->entry_date->format('d-m-Y')); ?></dd>
                    </div>
                    <div>
                        <dt>Stock method</dt>
                        <dd><?php echo e(strtoupper($entry->stock_method)); ?></dd>
                    </div>
                </dl>
            </div>
        </div>

        <?php echo $__env->make('pharmacy::adjustments.invoice-items', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="app-pharmacy-invoice-summary">
            <div>
                <?php if($entry->reason): ?>
                    <h2 class="app-clinical-invoice-section-title">Reason</h2>
                    <p class="app-pharmacy-invoice-note"><?php echo e($entry->reason); ?></p>
                <?php endif; ?>
                <?php if($entry->notes): ?>
                    <h2 class="app-clinical-invoice-section-title">Notes</h2>
                    <p class="app-pharmacy-invoice-note"><?php echo e($entry->notes); ?></p>
                <?php endif; ?>
            </div>
            <div class="app-clinical-invoice-totals">
                <dl class="mb-0">
                    <div class="app-clinical-invoice-total">
                        <dt>Stock value</dt>
                        <dd><?php echo e(moneyDecimal($entry->cost)); ?></dd>
                    </div>
                </dl>
            </div>
        </div>

        <?php echo $__env->make('pharmacy::entries.partials.invoice-footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </article>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\adjustments\show.blade.php ENDPATH**/ ?>