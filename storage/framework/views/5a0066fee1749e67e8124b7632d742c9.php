

<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php ($postedItems = getValue('items', null, $items)); ?>
    <?php ($formItems = collect(is_iterable($postedItems) ? $postedItems : [])->take(100)); ?>

    <?php echo $__env->make('core::partials.heading', [
        'title' => $pageTitle,
        'description' => 'Choose Draft to keep editing or Issued to finalize this invoice. Issued invoices cannot be edited.',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <form class="app-card p-3 p-md-4" action="<?php echo e($formAction); ?>" method="POST" data-invoice-editor>
        <?php echo csrf_field(); ?>
        <?php if($formMethod !== 'POST'): ?>
            <?php echo method_field($formMethod); ?>
        <?php endif; ?>

        <section class="app-form-section" aria-labelledby="invoice-details-heading">
            <h2 class="app-form-section-title" id="invoice-details-heading">Invoice details</h2>
            <div class="row g-3">
                <div class="col-md-6 col-xl-5">
                    <label class="form-label" for="tenant-id">Workspace</label>
                    <?php if($invoice->exists): ?>
                        <input class="form-control" id="tenant-id" type="text" value="<?php echo e($invoice->tenant->name); ?>" readonly>
                    <?php else: ?>
                        <input class="<?php echo e(inputClass('tenant_id')); ?>" id="tenant-id" name="tenant_id" type="text"
                            inputmode="numeric" list="invoice-tenants" value="<?php echo e(getValue('tenant_id')); ?>" required
                            <?php echo e(inputAttrs('tenant_id', 'tenant-id', 'tenant-id-help')); ?>>
                        <datalist id="invoice-tenants">
                            <?php $__currentLoopData = $tenantSuggestions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tenant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($tenant->id); ?>"><?php echo e($tenant->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </datalist>
                        <div class="form-text" id="tenant-id-help">
                            Choose a suggestion or enter a workspace ID from tenant management.
                        </div>
                        <?php $__errorArgs = ['tenant_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback d-block" id="tenant-id-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <?php endif; ?>
                </div>
                <div class="col-md-3 col-xl-2">
                    <label class="form-label" for="invoice-currency">Currency</label>
                    <input class="form-control" id="invoice-currency" type="text" value="<?php echo e($currency); ?>" readonly>
                </div>
                <div class="col-md-3 col-xl-3">
                    <label class="form-label" for="due-at">Due date</label>
                    <div class="input-group" data-datepicker>
                        <input class="<?php echo e(inputClass('due_at')); ?>" id="due-at" name="due_at" type="text" inputmode="numeric"
                            autocomplete="off" placeholder="YYYY-MM-DD"
                            value="<?php echo e(getValue('due_at', null, $invoice->due_at?->toDateString())); ?>" data-input
                            <?php echo e(inputAttrs('due_at', 'due-at')); ?>>
                        <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="due-at" aria-label="Choose due date" title="Choose due date">
                            <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
                        </button>
                    </div>
                    <?php $__errorArgs = ['due_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback d-block" id="due-at-error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6 col-xl-2">
                    <label class="form-label" for="invoice-status">Status</label>
                    <select class="<?php echo e(inputClass('status', 'form-select')); ?>" id="invoice-status" name="status" required <?php echo e(inputAttrs('status', 'invoice-status')); ?>>
                        <option value="draft" <?php echo e(getSelected('draft', 'status', $invoice, 'draft')); ?>>Draft</option>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('issue', $invoice)): ?>
                            <option value="issued" <?php echo e(getSelected('issued', 'status', $invoice, 'draft')); ?>>Issued</option>
                        <?php endif; ?>
                    </select>
                    <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback d-block" id="invoice-status-error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </section>

        <section class="app-form-section" aria-labelledby="invoice-items-heading">
            <h2 class="app-form-section-title" id="invoice-items-heading">Line items</h2>
            <p class="app-form-section-note">
                Amounts are in <?php echo e($currency); ?>. Quantities are whole numbers; totals are calculated when you save.
            </p>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th scope="col">Description</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Unit amount</th>
                            <th scope="col"><span class="visually-hidden">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody data-invoice-items>
                        <?php $__empty_1 = true; $__currentLoopData = $formItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php echo $__env->make('core::invoices.item-row', ['index' => $index, 'item' => $item], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <?php echo $__env->make('core::invoices.item-row', ['index' => 0, 'item' => []], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php $__errorArgs = ['items'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback d-block mb-3"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <button class="btn btn-outline-secondary btn-sm" type="button" data-invoice-add>
                <i class="fa-solid fa-plus me-2" aria-hidden="true"></i>
                Add item
            </button>
            <template data-invoice-template>
                <?php echo $__env->make('core::invoices.item-row', ['index' => '__INDEX__', 'item' => []], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </template>
        </section>

        <section class="app-form-section">
            <label class="form-label" for="notes">Notes</label>
            <textarea class="<?php echo e(inputClass('notes')); ?>" id="notes" name="notes" rows="3" <?php echo e(inputAttrs('notes')); ?>><?php echo e(getValue('notes', $invoice)); ?></textarea>
            <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback d-block" id="notes-error"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </section>

        <?php echo $__env->make('core::partials.form-actions', [
            'model' => $invoice,
            'routePrefix' => 'core.invoices',
            'submitLabel' => $submitLabel,
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\resources\views\invoices\form.blade.php ENDPATH**/ ?>