<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <header class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h4 fw-semibold mb-1"><?php echo e($pageTitle); ?></h1>
            <time class="small text-body-secondary" datetime="<?php echo e($todayIso); ?>"><?php echo e($todayLabel); ?></time>
        </div>
        <span class="badge text-bg-success px-3 py-2"><i class="fa-solid fa-book-open me-2" aria-hidden="true"></i>Accounting</span>
    </header>

    <nav class="row row-cols-2 row-cols-lg-5 g-3 mb-4" aria-label="Accounting quick actions">
        <?php if($canCreateJournal): ?>
            <div class="col">
                <a class="btn btn-success bg-gradient border-0 rounded-3 shadow-sm d-flex flex-column align-items-center justify-content-center gap-2 py-3 h-100"
                    href="<?php echo e(route('accounting.journal-entries.create')); ?>">
                    <i class="fa-solid fa-file-circle-plus fs-3" aria-hidden="true"></i><span class="small fw-semibold">Voucher Posting</span>
                </a>
            </div>
        <?php endif; ?>
        <?php if($canViewJournals): ?>
            <div class="col">
                <a class="btn btn-primary bg-gradient border-0 rounded-3 shadow-sm d-flex flex-column align-items-center justify-content-center gap-2 py-3 h-100"
                    href="<?php echo e(route('accounting.journal-entries.index')); ?>">
                    <i class="fa-solid fa-receipt fs-3" aria-hidden="true"></i><span class="small fw-semibold">Voucher List</span>
                </a>
            </div>
        <?php endif; ?>
        <?php if($canViewAccounts): ?>
            <div class="col">
                <a class="btn btn-warning bg-gradient border-0 rounded-3 shadow-sm d-flex flex-column align-items-center justify-content-center gap-2 py-3 h-100"
                    href="<?php echo e(route('accounting.chart-of-accounts.index')); ?>">
                    <i class="fa-solid fa-sitemap fs-3" aria-hidden="true"></i><span class="small fw-semibold">Chart of Account</span>
                </a>
            </div>
        <?php endif; ?>
        <?php if($canViewReports): ?>
            <div class="col">
                <a class="btn btn-danger bg-gradient border-0 rounded-3 shadow-sm d-flex flex-column align-items-center justify-content-center gap-2 py-3 h-100"
                    href="<?php echo e(route('accounting.reports.trial-balance')); ?>">
                    <i class="fa-solid fa-chart-column fs-3" aria-hidden="true"></i><span class="small fw-semibold">Report</span>
                </a>
            </div>
        <?php endif; ?>
        <?php if($canViewMappings): ?>
            <div class="col">
                <a class="btn btn-info bg-gradient border-0 rounded-3 shadow-sm d-flex flex-column align-items-center justify-content-center gap-2 py-3 h-100"
                    href="<?php echo e(route('accounting.account-modules.index')); ?>">
                    <i class="fa-solid fa-sliders fs-3" aria-hidden="true"></i><span class="small fw-semibold">Account Mapping</span>
                </a>
            </div>
        <?php endif; ?>
    </nav>

    <section class="row g-3 mb-4" aria-label="Current branch accounting activity">
        <?php if($canViewJournals): ?>
            <div class="col-md-6 col-xl-4">
                <article class="bg-primary-subtle border border-primary-subtle rounded-3 d-flex align-items-center gap-3 p-3 h-100">
                    <span class="bg-white text-primary rounded-circle p-3"><i class="fa-solid fa-file-invoice fs-4" aria-hidden="true"></i></span>
                    <div><h2 class="small fw-medium mb-1">Today's Voucher</h2><p class="h3 fw-bold mb-0"><?php echo e(number_format($todayCount)); ?></p></div>
                </article>
            </div>
            <div class="col-md-6 col-xl-4">
                <article class="bg-success-subtle border border-success-subtle rounded-3 d-flex align-items-center gap-3 p-3 h-100">
                    <span class="bg-white text-success rounded-circle p-3"><i class="fa-solid fa-bangladeshi-taka-sign fs-4" aria-hidden="true"></i></span>
                    <div><h2 class="small fw-medium mb-1">Today's Voucher Amount</h2><p class="h4 fw-bold mb-0">৳ <?php echo e(moneyDecimal($todayTotal)); ?></p></div>
                </article>
            </div>
        <?php endif; ?>
        <?php if($canViewAccounts): ?>
            <div class="col-md-6 col-xl-4">
                <article class="bg-warning-subtle border border-warning-subtle rounded-3 d-flex align-items-center gap-3 p-3 h-100">
                    <span class="bg-white text-warning-emphasis rounded-circle p-3"><i class="fa-solid fa-book fs-4" aria-hidden="true"></i></span>
                    <div><h2 class="small fw-medium mb-1">Active Ledger Head</h2><p class="h3 fw-bold mb-0"><?php echo e(number_format($ledgerCount)); ?></p></div>
                </article>
            </div>
        <?php endif; ?>
    </section>

    <?php if($canViewJournals): ?>
        <section class="app-card overflow-hidden" aria-labelledby="recent-vouchers-heading">
            <div class="d-flex align-items-center justify-content-between gap-2 p-3 border-bottom">
                <h2 class="h6 mb-0" id="recent-vouchers-heading"><i class="fa-solid fa-clock-rotate-left text-primary me-2" aria-hidden="true"></i>Recent vouchers</h2>
                <a class="small" href="<?php echo e(route('accounting.journal-entries.index')); ?>">View all</a>
            </div>
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <thead><tr><th scope="col">Voucher no.</th><th scope="col">Date</th><th scope="col">Type</th><th class="text-end" scope="col">Amount (BDT)</th></tr></thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $recentEntries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <th scope="row"><a href="<?php echo e(route('accounting.journal-entries.show', $entry)); ?>"><?php echo e($entry->number); ?></a></th>
                                <td class="text-nowrap"><?php echo e($entry->entry_date->format('d M Y')); ?></td>
                                <td><?php echo e($entry->voucherType->name); ?> <?php if($entry->voided_at): ?><span class="badge text-bg-secondary ms-1">Voided</span><?php endif; ?></td>
                                <td class="text-end text-nowrap"><?php echo e(moneyDecimal($entry->total)); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td class="text-center text-body-secondary py-4" colspan="4">No vouchers yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Accounting\resources\views\dashboard\index.blade.php ENDPATH**/ ?>