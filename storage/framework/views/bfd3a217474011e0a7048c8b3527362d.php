<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <div class="app-print-hidden">
        <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div class="d-flex flex-wrap gap-2 mb-3">
            <a class="btn btn-outline-secondary btn-sm" href="<?php echo e(route($routePrefix.'.index')); ?>">
                <i class="fa-solid fa-arrow-left me-2" aria-hidden="true"></i>Back
            </a>
            <button class="btn btn-primary btn-sm" type="button" data-print>
                <i class="fa-solid fa-print me-2" aria-hidden="true"></i>Print voucher
            </button>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $journal_entry)): ?>
                <a class="btn btn-outline-primary btn-sm" href="<?php echo e(route($routePrefix.'.edit', $journal_entry)); ?>">
                    <i class="fa-solid fa-pen-to-square me-2" aria-hidden="true"></i>Edit
                </a>
            <?php endif; ?>
        </div>
    </div>

    <article class="app-card app-invoice-document app-clinical-invoice d-flex flex-column">
        <header>
            <div class="app-clinical-invoice-brand d-flex align-items-center justify-content-center text-center">
                <?php if($logoUrl): ?><img class="app-clinical-invoice-logo" src="<?php echo e($logoUrl); ?>" width="76" height="76" alt=""><?php endif; ?>
                <div>
                    <h1 class="app-clinical-invoice-company"><?php echo e($company['name']); ?></h1>
                    <?php if($company['address']): ?><p class="app-clinical-invoice-address"><?php echo e($company['address']); ?></p><?php endif; ?>
                    <div class="app-clinical-invoice-contact d-flex flex-wrap justify-content-center gap-3">
                        <?php if($company['phone']): ?><span>Phone: <?php echo e($company['phone']); ?></span><?php endif; ?>
                        <?php if($company['email']): ?><span><?php echo e($company['email']); ?></span><?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="app-clinical-invoice-title-row d-flex align-items-center justify-content-between gap-3">
                <div>
                    <h2 class="h5 mb-1"><?php echo e($journal_entry->voucherType->name); ?></h2>
                    <p class="fw-semibold mb-0">Voucher no.: <?php echo e($journal_entry->number); ?></p>
                </div>
                <div class="text-end">
                    <p class="mb-1">Date: <?php echo e($journal_entry->entry_date->format('d-m-Y')); ?></p>
                    <?php if($journal_entry->voided_at): ?>
                        <strong class="text-danger">VOIDED</strong>
                    <?php elseif($journal_entry->isAutomatic()): ?>
                        <span class="small">Automatic voucher</span>
                    <?php else: ?>
                        <span class="small">Manual voucher</span>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <?php if($journal_entry->reference): ?><p class="small mb-3"><strong>Reference:</strong> <?php echo e($journal_entry->reference); ?></p><?php endif; ?>
        <div class="table-responsive">
            <table class="table app-clinical-invoice-table align-middle">
                <thead>
                    <tr>
                        <th scope="col">Code</th>
                        <th scope="col">Account head / Remarks</th>
                        <th class="text-end" scope="col">Debit (BDT)</th>
                        <th class="text-end" scope="col">Credit (BDT)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $journal_entry->lines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="text-nowrap"><?php echo e($line->accountHead->code); ?></td>
                            <td><?php echo e($line->accountHead->name); ?><div class="small text-body-secondary"><?php echo e($line->remarks); ?></div></td>
                            <td class="text-end text-nowrap"><?php echo e($line->debit > 0 ? moneyDecimal($line->debit) : '—'); ?></td>
                            <td class="text-end text-nowrap"><?php echo e($line->credit > 0 ? moneyDecimal($line->credit) : '—'); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
                <tfoot>
                    <tr class="fw-semibold">
                        <th colspan="2" scope="row">Total</th>
                        <td class="text-end text-nowrap"><?php echo e(moneyDecimal($journal_entry->total)); ?></td>
                        <td class="text-end text-nowrap"><?php echo e(moneyDecimal($journal_entry->total)); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <p class="small mb-3"><strong>In words:</strong> <?php echo e($amountWords); ?></p>
        <?php if($journal_entry->narration): ?><p class="text-break small"><strong>Narration:</strong> <?php echo e($journal_entry->narration); ?></p><?php endif; ?>

        <footer class="mt-auto pt-5">
            <div class="row g-4 text-center pt-4 mb-4">
                <div class="col-4"><div class="border-top pt-2">Prepared by</div><div class="small"><?php echo e($journal_entry->created_by_name); ?></div></div>
                <div class="col-4"><div class="border-top pt-2">Checked by</div></div>
                <div class="col-4"><div class="border-top pt-2">Authorized by</div></div>
            </div>
            <p class="small text-body-secondary border-top pt-2 mb-0">Printed by: <?php echo e($printedBy); ?> &middot; <?php echo e($printedAt); ?></p>
        </footer>
    </article>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Accounting\resources\views\journal-entries\show.blade.php ENDPATH**/ ?>