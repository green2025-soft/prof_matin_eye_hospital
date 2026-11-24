<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle, 'description' => 'Add one or more expense heads in a single voucher.'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <form class="income-expense-form app-card p-3 p-md-4" action="<?php echo e($formAction); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php if($formMethod !== 'POST'): ?>
            <?php echo method_field($formMethod); ?>
        <?php endif; ?>

        <section class="app-form-section" aria-labelledby="expense-form-heading">
            <h2 class="app-form-section-title" id="expense-form-heading">
                <span class="app-form-section-icon"><i class="fa-solid fa-arrow-trend-down" aria-hidden="true"></i></span>Expense details
            </h2>

            <div class="row g-3">
                <div class="col-12 col-md-6 col-xl-3">
                    <label class="form-label" for="entry_date">Date <span class="text-danger" aria-hidden="true">*</span></label>
                    <div class="input-group" data-datepicker>
                        <input class="<?php echo e(inputClass('entry_date')); ?>" id="entry_date" name="entry_date" type="text"
                            inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD"
                            value="<?php echo e(getValue('entry_date', null, $entryDate)); ?>" required data-input <?php echo e(inputAttrs('entry_date')); ?>>
                        <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="entry_date"
                            aria-label="Choose expense date" title="Choose expense date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
                    </div>
                    <?php $__errorArgs = ['entry_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="entry_date-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-12 col-md-6 col-xl-3">
                    <label class="form-label" for="payment_type_id">Payment type <span class="text-danger" aria-hidden="true">*</span></label>
                    <select class="<?php echo e(inputClass('payment_type_id', 'form-select')); ?>" id="payment_type_id" name="payment_type_id"
                        required <?php echo e(inputAttrs('payment_type_id')); ?>>
                        <option value="">Select a payment type</option>
                        <?php $__currentLoopData = $paymentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paymentType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($paymentType->id); ?>" <?php if((string) $paymentTypeId === (string) $paymentType->id): echo 'selected'; endif; ?>><?php echo e($paymentType->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['payment_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="payment_type_id-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-12 col-xl-6">
                    <label class="form-label" for="note">Note</label>
                    <textarea class="<?php echo e(inputClass('note')); ?>" id="note" name="note" rows="1" maxlength="2000"
                        <?php echo e(inputAttrs('note')); ?>><?php echo e(getValue('note', $expense)); ?></textarea>
                    <?php $__errorArgs = ['note'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="note-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <?php echo $__env->make('tenantcore::partials.income-expense-items', [
                'record' => $expense,
                'headOptionsUrl' => route('tenantcore.expense-heads.options'),
            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </section>

        <?php echo $__env->make('core::partials.form-actions', ['model' => $expense, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\TenantCore\resources\views\expenses\form.blade.php ENDPATH**/ ?>