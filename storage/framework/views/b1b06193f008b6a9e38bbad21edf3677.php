<?php $__currentLoopData = $summaries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $summary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <section class="app-card p-3 p-md-4 mb-4" aria-label="<?php echo e($summary->currency); ?> invoice summary">
        <div class="d-flex justify-content-between align-items-center gap-3 mb-3">
            <h2 class="h6 mb-0"><?php echo e($summary->currency); ?> summary</h2>
            <span class="small text-body-secondary"><?php echo e($summary->invoice_count); ?> issued invoices</span>
        </div>
        <div class="row g-3">
            <div class="col-sm-4">
                <div class="small text-body-secondary mb-1">Invoiced</div>
                <div class="h5 mb-0"><?php echo e($summary->currency); ?> <?php echo e(moneyDecimal($summary->total)); ?></div>
            </div>
            <div class="col-sm-4">
                <div class="small text-body-secondary mb-1">Paid</div>
                <div class="h5 mb-0"><?php echo e($summary->currency); ?> <?php echo e(moneyDecimal($summary->paid)); ?></div>
            </div>
            <div class="col-sm-4">
                <div class="small text-body-secondary mb-1">Outstanding</div>
                <div class="h5 mb-0"><?php echo e($summary->currency); ?> <?php echo e(moneyDecimal($summary->balance)); ?></div>
            </div>
        </div>
    </section>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\resources\views\invoices\summary.blade.php ENDPATH**/ ?>