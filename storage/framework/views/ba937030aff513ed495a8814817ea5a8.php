

<?php ($invoiceTitle = $invoice->number ? $pageTitle : 'Invoice #'.$invoice->id); ?>

<?php $__env->startSection('title', $invoiceTitle); ?>

<?php $__env->startSection('content'); ?>
    <div class="app-print-hidden">
        <?php echo $__env->make('core::partials.heading', [
            'title' => $invoiceTitle,
            'description' => 'Invoice details and payment history.',
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div class="d-flex flex-wrap gap-2 mb-4">
            <button class="btn btn-outline-secondary" type="button" data-print>
                <i class="fa-solid fa-print me-2" aria-hidden="true"></i>
                Print
            </button>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $invoice)): ?>
                <a class="btn btn-outline-secondary" href="<?php echo e(route('core.invoices.edit', $invoice)); ?>">Edit draft</a>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('issue', $invoice)): ?>
                <form action="<?php echo e(route('core.invoices.issue', $invoice)); ?>" method="POST"
                    data-confirm="Issue this invoice? Its details will become final.">
                    <?php echo csrf_field(); ?>
                    <button class="btn btn-primary" type="submit">Issue</button>
                </form>
            <?php endif; ?>
            <?php if($canRecordPayment): ?>
                <a class="btn btn-primary" href="<?php echo e(route('core.payments.create')); ?>">Record payment</a>
            <?php endif; ?>
        </div>
    </div>

    <article class="app-card p-3 p-md-4 app-invoice-document" aria-labelledby="invoice-title">
        <div class="d-flex flex-wrap justify-content-between gap-4 border-bottom pb-4 mb-4">
            <div>
                <h2 class="h5" id="invoice-title"><?php echo e($merchant['name']); ?></h2>
                <?php if($merchant['address'] ?? ''): ?>
                    <p class="small mb-1 text-break"><?php echo e($merchant['address']); ?></p>
                <?php endif; ?>
                <p class="small mb-1">
                    <?php echo e($merchant['email'] ?? ''); ?>

                    <?php if($merchant['phone'] ?? ''): ?>
                        <span class="ms-2"><?php echo e($merchant['phone']); ?></span>
                    <?php endif; ?>
                </p>
                <?php if($merchant['tax_id'] ?? ''): ?>
                    <p class="small mb-0">Tax ID: <?php echo e($merchant['tax_id']); ?></p>
                <?php endif; ?>
            </div>
            <div class="text-md-end">
                <h2 class="h4">INVOICE</h2>
                <p class="fw-semibold mb-1"><?php echo e($invoice->number ?: 'Invoice #'.$invoice->id); ?></p>
                <span class="app-tag"><?php echo e($statusLabel); ?></span>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-sm-6">
                <h3 class="small text-body-secondary text-uppercase">Bill to</h3>
                <p class="fw-semibold mb-1"><?php echo e($customer['name']); ?></p>
                <p class="small mb-0"><?php echo e($customer['email'] ?? ''); ?></p>
            </div>
            <div class="col-sm-6 text-sm-end">
                <p class="small mb-1">Issued: <?php echo e($issuedDate ?? 'Not issued'); ?></p>
                <p class="small mb-1">Due: <?php echo e($dueDate ?? 'Not specified'); ?></p>
                <?php if($periodLabel): ?>
                    <p class="small mb-0">Period: <?php echo e($periodLabel); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table app-table align-middle">
                <thead>
                    <tr>
                        <th scope="col">Description</th>
                        <th class="text-end" scope="col">Quantity</th>
                        <th class="text-end" scope="col">Unit amount</th>
                        <th class="text-end" scope="col">Amount (<?php echo e($invoice->currency); ?>)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="text-break"><?php echo e($item->description); ?></td>
                            <td class="text-end"><?php echo e($item->quantity); ?></td>
                            <td class="text-end"><?php echo e(moneyDecimal($item->unit_amount_minor)); ?></td>
                            <td class="text-end"><?php echo e(moneyDecimal($item->line_total_minor)); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <div class="row justify-content-end">
            <div class="col-sm-6 col-lg-4">
                <dl class="mb-0">
                    <div class="d-flex justify-content-between py-2">
                        <dt>Subtotal</dt>
                        <dd class="mb-0"><?php echo e($subtotal); ?></dd>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-top">
                        <dt>Total</dt>
                        <dd class="fw-semibold mb-0"><?php echo e($invoice->currency); ?> <?php echo e($total); ?></dd>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <dt>Paid</dt>
                        <dd class="mb-0"><?php echo e($paid); ?></dd>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-top">
                        <dt>Balance</dt>
                        <dd class="fw-semibold mb-0"><?php echo e($invoice->currency); ?> <?php echo e($balance); ?></dd>
                    </div>
                </dl>
            </div>
        </div>

        <?php if($invoice->notes): ?>
            <div class="border-top mt-4 pt-3">
                <h3 class="h6">Notes</h3>
                <p class="small text-break mb-0"><?php echo e($invoice->notes); ?></p>
            </div>
        <?php endif; ?>
        <?php if($invoice->payment_instructions): ?>
            <div class="mt-3">
                <h3 class="h6">Payment instructions</h3>
                <p class="small text-break mb-0"><?php echo e($invoice->payment_instructions); ?></p>
            </div>
        <?php endif; ?>
        <?php if($invoice->void_reason): ?>
            <div class="alert alert-secondary mt-4 mb-0">
                <strong>Voided:</strong>
                <?php echo e($invoice->void_reason); ?>

            </div>
        <?php endif; ?>
    </article>

    <?php if($checkout['canPay']): ?>
        <section class="app-card p-3 p-md-4 mt-4 app-print-hidden" aria-labelledby="payment-title">
            <h2 class="h5" id="payment-title">Pay this invoice</h2>
            <p class="text-secondary small">Amount due: <strong><?php echo e($invoice->currency); ?> <?php echo e($balance); ?></strong></p>

            <?php if($checkout['activeAttempt']): ?>
                <p class="mb-2"><?php echo e($checkout['activeAttempt']['message']); ?></p>
                <p class="small">Extra charge: <?php echo e($invoice->currency); ?> <?php echo e($checkout['activeAttempt']['extraCharge']); ?> · Total payment: <strong><?php echo e($invoice->currency); ?> <?php echo e($checkout['activeAttempt']['total']); ?></strong></p>
                <?php if($checkout['activeAttempt']['reference']): ?>
                    <p class="small mb-0">Reference: <?php echo e($checkout['activeAttempt']['reference']); ?></p>
                <?php endif; ?>
                <?php if($checkout['activeAttempt']['url']): ?>
                    <a class="btn btn-primary mt-2" href="<?php echo e($checkout['activeAttempt']['url']); ?>">Resume checkout</a>
                <?php endif; ?>
            <?php elseif($checkout['providers'] !== []): ?>
                <form action="<?php echo e($checkout['checkoutAction']); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="provider">Payment method</label>
                            <select class="<?php echo e(inputClass('provider', 'form-select')); ?>" id="provider" name="provider" required data-payment-provider <?php echo e(inputAttrs('provider')); ?>>
                                <?php $__currentLoopData = $checkout['providers']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($value); ?>" data-extra-charge="<?php echo e($method['extraCharge']); ?>" data-total="<?php echo e($method['total']); ?>" <?php echo e(getSelected($value, 'provider', null, $checkout['defaultProvider'])); ?>><?php echo e($method['label']); ?> — extra <?php echo e($invoice->currency); ?> <?php echo e($method['extraCharge']); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['provider'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback" id="provider-error"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-12" data-manual-payment-fields>
                            <?php if($checkout['manualInstructions']): ?>
                                <p class="text-break"><?php echo e($checkout['manualInstructions']); ?></p>
                            <?php endif; ?>
                            <label class="form-label" for="submitted_reference">Transaction reference</label>
                            <input class="<?php echo e(inputClass('submitted_reference')); ?>" id="submitted_reference" name="submitted_reference" value="<?php echo e(getValue('submitted_reference')); ?>" maxlength="190" required <?php echo e(inputAttrs('submitted_reference', 'submitted_reference', 'manual-payment-help')); ?>>
                            <?php $__errorArgs = ['submitted_reference'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback" id="submitted_reference-error"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <p class="form-text mb-0" id="manual-payment-help">Enter the reference from your completed transfer. A platform operator will review it.</p>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="payment-notes">Payment note <span class="text-secondary">(optional)</span></label>
                            <textarea class="<?php echo e(inputClass('notes')); ?>" id="payment-notes" name="notes" rows="2" maxlength="500" <?php echo e(inputAttrs('notes', 'payment-notes')); ?>><?php echo e(getValue('notes')); ?></textarea>
                            <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback" id="payment-notes-error"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-12">
                            <p class="small mb-1">Extra charge: <?php echo e($invoice->currency); ?> <span data-payment-extra-charge><?php echo e($checkout['providers'][$checkout['defaultProvider']]['extraCharge']); ?></span></p>
                            <p class="fw-semibold">Total payment: <?php echo e($invoice->currency); ?> <span data-payment-total><?php echo e($checkout['providers'][$checkout['defaultProvider']]['total']); ?></span></p>
                            <button class="btn btn-primary" type="submit">Pay</button>
                        </div>
                    </div>
                </form>
            <?php else: ?>
                <p class="mb-0 text-secondary">No payment methods are enabled. Contact the platform administrator.</p>
            <?php endif; ?>
        </section>
    <?php endif; ?>

    <?php if($payments->isNotEmpty()): ?>
        <section class="app-card mt-4 overflow-hidden">
            <div class="app-card-heading px-3 py-3 border-bottom">
                <h2 class="h6 mb-0">Recent payments (latest 20)</h2>
            </div>
            <div class="table-responsive">
                <table class="table app-table mb-0">
                    <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Provider</th>
                            <th scope="col">Reference</th>
                            <th class="text-end" scope="col">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($payment->paid_at->format('d M Y')); ?></td>
                                <td><?php echo e($payment->provider); ?></td>
                                <td class="text-break"><?php echo e($payment->provider_reference ?? '-'); ?></td>
                                <td class="text-end">
                                    <?php echo e($invoice->currency); ?> <?php echo e(moneyDecimal($payment->amount_minor + $payment->extra_charge_minor)); ?>

                                    <div class="small text-body-secondary">Invoice <?php echo e(moneyDecimal($payment->amount_minor)); ?> + extra <?php echo e(moneyDecimal($payment->extra_charge_minor)); ?></div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </section>
    <?php endif; ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('void', $invoice)): ?>
        <form class="app-card p-3 p-md-4 mt-4 app-print-hidden" action="<?php echo e(route('core.invoices.void', $invoice)); ?>"
            method="POST" data-confirm="Void this unpaid invoice?">
            <?php echo csrf_field(); ?>
            <label class="form-label" for="reason">Void reason</label>
            <textarea class="<?php echo e(inputClass('reason')); ?>" id="reason" name="reason" rows="2" required <?php echo e(inputAttrs('reason')); ?>><?php echo e(getValue('reason')); ?></textarea>
            <?php $__errorArgs = ['reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback d-block" id="reason-error"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <button class="btn btn-outline-danger mt-3" type="submit">Void</button>
        </form>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\resources\views\invoices\show.blade.php ENDPATH**/ ?>