<?php if($manualSubmissions->isNotEmpty()): ?>
    <section class="app-card mt-4 overflow-hidden" aria-labelledby="manual-payments-heading">
        <div class="app-card-heading p-3 border-bottom">
            <h2 class="h6 mb-1" id="manual-payments-heading">
                <?php echo e($isPlatform ? 'Manual payments awaiting review' : 'Recent manual payment submissions'); ?>

            </h2>
            <p class="small text-body-secondary mb-0">Showing the latest 20 submissions.</p>
        </div>
        <div class="table-responsive">
            <table class="table app-table align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">Invoice</th>
                        <th scope="col">Submitted</th>
                        <th scope="col">Reference</th>
                        <th scope="col">Status</th>
                        <th class="text-end" scope="col">Submitted total</th>
                        <?php if($canReviewPayments): ?>
                            <th class="text-end" scope="col">Review</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $manualSubmissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <th scope="row">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view', $submission->invoice)): ?>
                                    <a href="<?php echo e(route($invoiceRoutePrefix.'.show', $submission->invoice_id)); ?>"><?php echo e($submission->invoice->number); ?></a>
                                <?php else: ?>
                                    <?php echo e($submission->invoice->number); ?>

                                <?php endif; ?>
                            </th>
                            <td class="text-nowrap"><?php echo e($submission->created_at->format('d M Y H:i')); ?></td>
                            <td class="text-break">
                                <?php echo e($submission->submitted_reference ?? '-'); ?>

                                <?php if($submission->notes): ?>
                                    <div class="small text-body-secondary"><?php echo e($submission->notes); ?></div>
                                <?php endif; ?>
                            </td>
                            <td><span class="app-tag"><?php echo e(ucfirst(str_replace('_', ' ', $submission->status))); ?></span></td>
                            <td class="text-end text-nowrap">
                                <?php echo e($submission->currency); ?> <?php echo e(moneyDecimal($submission->amount_minor)); ?>

                                <?php if($submission->extra_charge_minor !== 0): ?>
                                    <div class="small text-body-secondary">Invoice <?php echo e($submission->currency); ?> <?php echo e(moneyDecimal($submission->amount_minor - $submission->extra_charge_minor)); ?></div>
                                    <div class="small text-body-secondary">Extra charge <?php echo e($submission->currency); ?> <?php echo e(moneyDecimal($submission->extra_charge_minor)); ?></div>
                                <?php endif; ?>
                            </td>
                            <?php if($canReviewPayments): ?>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <form action="<?php echo e(route('core.checkouts.approve', $submission->id)); ?>" method="POST"
                                            data-confirm="Approve this payment after verifying the received funds?">
                                            <?php echo csrf_field(); ?>
                                            <button class="btn btn-primary btn-sm" type="submit">Approve</button>
                                        </form>
                                        <details>
                                            <summary class="btn btn-outline-danger btn-sm">Reject</summary>
                                            <form class="mt-2" action="<?php echo e(route('core.checkouts.reject', $submission->id)); ?>"
                                                method="POST" data-confirm="Reject this manual payment submission?">
                                                <?php echo csrf_field(); ?>
                                                <label class="visually-hidden" for="reject-reason-<?php echo e($submission->id); ?>">
                                                    Rejection reason
                                                </label>
                                                <textarea class="<?php echo e(inputClass('reason', 'form-control form-control-sm')); ?>"
                                                    id="reject-reason-<?php echo e($submission->id); ?>" name="reason" rows="2" maxlength="500"
                                                    placeholder="Reason (optional)"
                                                    <?php echo e(inputAttrs('reason', 'reject-reason-'.$submission->id)); ?>><?php echo e(getValue('reason')); ?></textarea>
                                                <?php $__errorArgs = ['reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                    <div class="invalid-feedback d-block" id="reject-reason-<?php echo e($submission->id); ?>-error">
                                                        <?php echo e($message); ?>

                                                    </div>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                <button class="btn btn-outline-danger btn-sm mt-2" type="submit">Reject</button>
                                            </form>
                                        </details>
                                    </div>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </section>
<?php endif; ?>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\resources\views\invoices\manual-submissions.blade.php ENDPATH**/ ?>