<?php $__env->startSection('title', ucfirst($payment->product_type).' '.$pageTitle); ?>
<?php $__env->startSection('content'); ?>
    <div class="d-flex flex-wrap justify-content-between gap-3 mb-3">
        <?php echo $__env->make('core::partials.heading', ['title' => ucfirst($payment->product_type).' '.$pageTitle], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div class="d-flex gap-2 align-self-start d-print-none">
            <a class="btn btn-outline-secondary"
                href="<?php echo e(route('pharmacy.purchase-payments.index', ['product_type' => $payment->product_type])); ?>">Back</a>
            <button class="btn btn-outline-primary" type="button" data-print>Print</button>
            
            <?php if($canDelete): ?>
                <form action="<?php echo e(route('pharmacy.purchase-payments.destroy', [
                    'purchase_payment' => $payment,
                    'product_type' => $payment->product_type,
                ])); ?>" method="POST"
                    data-confirm="Permanently delete this payment? Active invoice and journal effects will be reversed.">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button class="btn btn-danger" type="submit">Delete</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
    <section class="app-card p-3 p-md-4">
        <?php if($payment->voided_at): ?>
            <div class="alert alert-secondary">Voided on <?php echo e($payment->voided_at->format('Y-m-d H:i')); ?></div>
        <?php endif; ?>
        <dl class="row">
            <div class="col-sm-4">
                <dt class="small text-body-secondary">Date</dt>
                <dd><?php echo e($payment->payment_date->format('Y-m-d')); ?></dd>
            </div>
            <div class="col-sm-4">
                <dt class="small text-body-secondary">Payment</dt>
                <dd>Supplier Payment / <?php echo e(ucfirst($payment->product_type)); ?></dd>
            </div>
            <div class="col-sm-4">
                <dt class="small text-body-secondary">Supplier</dt>
                <dd><?php echo e($supplier?->name ?? '—'); ?></dd>
            </div>
        </dl>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Invoice</th>
                        <th scope="col" class="text-end">Collection</th>
                        <th scope="col" class="text-end">Discount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $allocations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $allocation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($allocation->purchase?->number); ?></td>
                            <td class="text-end"><?php echo e(moneyDecimal($allocation->amount)); ?></td>
                            <td class="text-end"><?php echo e(moneyDecimal($allocation->discount)); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total</th>
                        <th class="text-end"><?php echo e(moneyDecimal($payment->amount)); ?></th>
                        <th class="text-end"><?php echo e(moneyDecimal($payment->discount)); ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php if($payment->notes): ?>
            <p class="mb-0 text-break"><?php echo e($payment->notes); ?></p>
        <?php endif; ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\purchase-payments\show.blade.php ENDPATH**/ ?>