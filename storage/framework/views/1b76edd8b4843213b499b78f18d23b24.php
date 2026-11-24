<div class="table-responsive">
    <table class="table app-table align-middle mb-0">
        <thead>
            <tr>
                <th scope="col">Invoice</th>
                <?php if($showWorkspace): ?>
                    <th scope="col">Workspace</th>
                <?php endif; ?>
                <th scope="col">Status</th>
                <th class="text-end" scope="col">Total</th>
                <th class="text-end" scope="col">Balance</th>
                <th class="text-end" scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <th scope="row">
                        <a href="<?php echo e(route($invoiceRoutePrefix.'.show', $invoice)); ?>">
                            <?php echo e($invoice->number ?: 'Invoice #'.$invoice->id); ?>

                        </a>
                        <div class="small text-body-secondary fw-normal">
                            <?php echo e($invoice->created_at->format('d M Y')); ?>

                        </div>
                    </th>
                    <?php if($showWorkspace): ?>
                        <td><?php echo e($invoice->tenant->name); ?></td>
                    <?php endif; ?>
                    <td>
                        <span class="app-tag"><?php echo e($statusOptions[$invoice->status]); ?></span>
                    </td>
                    <td class="text-end text-nowrap"><?php echo e($invoice->currency); ?> <?php echo e($invoice->total_amount); ?></td>
                    <td class="text-end text-nowrap"><?php echo e($invoice->balance_amount); ?></td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-2 text-nowrap">
                            <a
                                class="btn btn-outline-secondary btn-sm app-action-button"
                                href="<?php echo e(route($invoiceRoutePrefix.'.show', $invoice)); ?>"
                                aria-label="View invoice <?php echo e($invoice->number ?: $invoice->id); ?>"
                                title="View invoice"
                            >
                                <i class="fa-solid fa-eye" aria-hidden="true"></i>
                            </a>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('issue', $invoice)): ?>
                                <form action="<?php echo e(route('core.invoices.issue', $invoice)); ?>" method="POST" data-confirm="Issue this invoice? Its details will become final.">
                                    <?php echo csrf_field(); ?>
                                    <button class="btn btn-outline-primary btn-sm" type="submit">Issue</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\resources\views\invoices\table.blade.php ENDPATH**/ ?>