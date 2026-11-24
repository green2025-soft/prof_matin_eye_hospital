<?php ($reportTitle = ucfirst($filters['product_type']).' '.$pageTitle); ?>

<?php $__env->startSection('title', $reportTitle); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3 d-print-none">
        <?php echo $__env->make('core::partials.heading', ['title' => $reportTitle], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <button class="btn btn-outline-primary" type="button" data-print>
            <i class="fa-solid fa-print me-2" aria-hidden="true"></i>Print report
        </button>
    </div>

    <nav class="d-flex flex-wrap gap-2 mb-3 d-print-none" aria-label="Pharmacy reports">
        <a class="btn btn-sm <?php echo e(request()->routeIs('pharmacy.reports.stock') ? 'btn-primary' : 'btn-outline-secondary'); ?>" href="<?php echo e(route('pharmacy.reports.stock', ['product_type' => $filters['product_type']])); ?>">Stock</a>
        <a class="btn btn-sm <?php echo e(request()->routeIs('pharmacy.reports.movements') ? 'btn-primary' : 'btn-outline-secondary'); ?>" href="<?php echo e(route('pharmacy.reports.movements', ['product_type' => $filters['product_type']])); ?>">Movements</a>
        <a class="btn btn-sm <?php echo e(request()->routeIs('pharmacy.reports.expiry') ? 'btn-primary' : 'btn-outline-secondary'); ?>" href="<?php echo e(route('pharmacy.reports.expiry', ['product_type' => 'medicine'])); ?>">Medicine expiry</a>
        <a class="btn btn-sm <?php echo e(request()->routeIs('pharmacy.reports.profit-loss') ? 'btn-primary' : 'btn-outline-secondary'); ?>"
            href="<?php echo e(route('pharmacy.reports.profit-loss', ['product_type' => $filters['product_type']])); ?>">Profit / loss</a>
        <a class="btn btn-sm <?php echo e(request()->routeIs('pharmacy.reports.purchase-discounts') ? 'btn-primary' : 'btn-outline-secondary'); ?>"
            href="<?php echo e(route('pharmacy.reports.purchase-discounts', ['product_type' => $filters['product_type']])); ?>">Purchase discounts</a>
        <a class="btn btn-sm <?php echo e(request()->routeIs('pharmacy.reports.daily') ? 'btn-primary' : 'btn-outline-secondary'); ?>" href="<?php echo e(route('pharmacy.reports.daily', ['product_type' => $filters['product_type']])); ?>">Daily</a>
        <a class="btn btn-sm <?php echo e(request()->routeIs('pharmacy.reports.customer-dues') ? 'btn-primary' : 'btn-outline-secondary'); ?>"
            href="<?php echo e(route('pharmacy.reports.customer-dues', ['product_type' => $filters['product_type']])); ?>">Customer dues</a>
        <a class="btn btn-sm <?php echo e(request()->routeIs('pharmacy.reports.supplier-dues') ? 'btn-primary' : 'btn-outline-secondary'); ?>"
            href="<?php echo e(route('pharmacy.reports.supplier-dues', ['product_type' => $filters['product_type']])); ?>">Supplier dues</a>
    </nav>

    <div class="app-card mb-3 d-print-none">
        <?php echo $__env->yieldContent('report-filters'); ?>
    </div>

    <article class="app-card app-pharmacy-report <?php echo $__env->yieldContent('report-page-class'); ?>" aria-label="<?php echo e($pageTitle); ?>">
        <?php echo $__env->make('tenantcore::partials.report-letterhead', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div class="app-report-title d-flex flex-wrap align-items-center justify-content-between row-gap-2 column-gap-4">
            <h3><?php echo e(ucfirst($filters['product_type'])); ?> <?php echo e($pageTitle); ?></h3>
            <span>Amounts in BDT</span>
        </div>
        <div class="app-report-context d-flex flex-wrap justify-content-between row-gap-2 column-gap-4">
            <?php echo $__env->yieldContent('report-context'); ?>
        </div>

        <?php echo $__env->yieldContent('report-summary'); ?>
        <?php echo $__env->yieldContent('report-table'); ?>

        <footer class="app-report-footer d-flex flex-wrap justify-content-between row-gap-2 column-gap-4">
            <span>Printed by: <?php echo e($printedBy); ?></span>
            <span>Printed: <?php echo e($printedAt); ?></span>
            <span class="d-print-none">Page <?php echo e($records->currentPage()); ?> of <?php echo e($records->lastPage()); ?></span>
        </footer>
        <div class="app-report-signatures d-none d-print-flex flex-wrap justify-content-between row-gap-2 column-gap-4">
            <span>Prepared by</span>
            <span>Checked by</span>
            <span>Authorized signature</span>
        </div>
    </article>

    <div class="d-print-none">
        <?php echo $__env->make('core::partials.pagination', ['paginator' => $records, 'filters' => $filters], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\reports\layout.blade.php ENDPATH**/ ?>