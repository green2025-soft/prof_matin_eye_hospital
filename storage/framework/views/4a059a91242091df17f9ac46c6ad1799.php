

<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <div class="app-print-hidden">
        <?php echo $__env->make('core::partials.heading', [
            'title' => $pageTitle,
            'description' => 'Payment receipt. Recorded payments cannot be edited or deleted.',
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <button class="btn btn-outline-secondary mb-4" type="button" data-print>
            <i class="fa-solid fa-print me-2" aria-hidden="true"></i>
            Print receipt
        </button>
    </div>

    <article class="app-card p-3 p-md-4 app-invoice-document">
        <div class="d-flex flex-wrap justify-content-between gap-3 border-bottom pb-3 mb-4">
            <div>
                <h2 class="h5"><?php echo e($merchant['name'] ?? 'Payment receipt'); ?></h2>
                <p class="small mb-0"><?php echo e($merchant['email'] ?? ''); ?></p>
            </div>
            <div class="text-sm-end">
                <h2 class="h5">PAYMENT RECEIPT</h2>
                <p class="mb-0">#<?php echo e($payment->id); ?></p>
            </div>
        </div>
        <dl class="row mb-0">
            <dt class="col-sm-4">Received from</dt>
            <dd class="col-sm-8"><?php echo e($customer['name'] ?? ''); ?></dd>
            <dt class="col-sm-4">Invoice</dt>
            <dd class="col-sm-8">
                <?php if($invoiceUrl): ?>
                    <a href="<?php echo e($invoiceUrl); ?>"><?php echo e($payment->invoice->number); ?></a>
                <?php else: ?>
                    <?php echo e($payment->invoice->number); ?>

                <?php endif; ?>
            </dd>
            <dt class="col-sm-4">Date</dt>
            <dd class="col-sm-8"><?php echo e($paidDate); ?></dd>
            <dt class="col-sm-4">Provider</dt>
            <dd class="col-sm-8"><?php echo e($payment->provider); ?></dd>
            <dt class="col-sm-4">Method</dt>
            <dd class="col-sm-8"><?php echo e(str_replace('_', ' ', $payment->method)); ?></dd>
            <dt class="col-sm-4">Reference</dt>
            <dd class="col-sm-8 text-break"><?php echo e($payment->provider_reference ?? '-'); ?></dd>
            <dt class="col-sm-4">Invoice amount</dt>
            <dd class="col-sm-8 fw-semibold"><?php echo e($payment->currency); ?> <?php echo e($amount); ?></dd>
            <dt class="col-sm-4">Extra charge</dt>
            <dd class="col-sm-8"><?php echo e($payment->currency); ?> <?php echo e($extraCharge); ?></dd>
            <dt class="col-sm-4">Total received</dt>
            <dd class="col-sm-8 fw-semibold"><?php echo e($payment->currency); ?> <?php echo e($received); ?></dd>
        </dl>

        <?php if($payment->notes): ?>
            <div class="border-top mt-3 pt-3">
                <h3 class="h6">Notes</h3>
                <p class="small text-break mb-0"><?php echo e($payment->notes); ?></p>
            </div>
        <?php endif; ?>
    </article>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\resources\views\payments\show.blade.php ENDPATH**/ ?>