<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3 d-print-none">
        <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <button class="btn btn-outline-primary" type="button" data-print><i class="fa-solid fa-print me-2" aria-hidden="true"></i>Print report</button>
    </div>

    <div class="app-card p-3 mb-3 d-print-none"><?php echo $__env->yieldContent('report-filters'); ?></div>

    <article class="app-card app-pharmacy-report" aria-label="<?php echo e($pageTitle); ?>">
        <?php echo $__env->make('tenantcore::partials.report-letterhead', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div class="app-report-title d-flex flex-wrap align-items-center justify-content-between gap-2">
            <h3><?php echo e($pageTitle); ?></h3>
        </div>
        <div class="app-report-context">Period: <?php echo e($fromDate); ?> to <?php echo e($toDate); ?></div>

        <?php $__env->startSection('report-summary'); ?>
            <?php if(isset($opening, $received, $paid, $closing, $receivable, $payable, $balance)): ?>
                <?php echo $__env->make('tenantcore::reports.partials.balances', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
        <?php echo $__env->yieldSection(); ?>
        <?php echo $__env->yieldContent('report-table'); ?>

        <footer class="app-report-footer d-flex flex-wrap justify-content-between gap-2">
            <span>Printed by: <?php echo e($printedBy); ?></span>
            <span>Printed: <?php echo e($printedAt); ?></span>
        </footer>
        <div class="app-report-signatures d-none d-print-flex justify-content-between gap-3">
            <span>Prepared by</span><span>Checked by</span><span>Authorized signature</span>
        </div>
    </article>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\TenantCore\resources\views\reports\layout.blade.php ENDPATH**/ ?>