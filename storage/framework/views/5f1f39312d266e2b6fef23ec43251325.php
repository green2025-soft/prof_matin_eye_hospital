<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <form class="app-card p-3 p-md-4" action="<?php echo e($formAction); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php if($formMethod !== 'POST'): ?> <?php echo method_field($formMethod); ?> <?php endif; ?>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                <input class="<?php echo e(inputClass('name')); ?>" id="name" name="name" value="<?php echo e(getValue('name', $account_head)); ?>" maxlength="255" required autofocus <?php echo e(inputAttrs('name')); ?>>
                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="parent_id">Parent account <span class="text-danger">*</span></label>
                <?php if($account_head->exists): ?>
                    <input class="form-control" id="parent_id" value="<?php echo e($account_head->parent->code); ?> · <?php echo e($account_head->parent->name); ?>" readonly>
                <?php else: ?>
                    <select class="<?php echo e(inputClass('parent_id', 'form-select')); ?>" id="parent_id" name="parent_id" required <?php echo e(inputAttrs('parent_id')); ?>>
                        <option value="">Select parent account</option>
                        <?php $__currentLoopData = $parents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($parent->id); ?>" <?php echo e(getSelected($parent->id, 'parent_id', $account_head, $selectedParent?->id)); ?>><?php echo e($parent->code); ?> · <?php echo e($parent->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                <?php endif; ?>
                <?php $__errorArgs = ['parent_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <?php if($account_head->exists): ?>
                <div class="col-md-6"><label class="form-label" for="code">Code</label><input class="form-control" id="code" value="<?php echo e($account_head->code); ?>" readonly></div>
            <?php endif; ?>
            <div class="col-md-6">
                <label class="form-label" for="active">Status</label>
                <select class="form-select" id="active" name="active">
                    <option value="1" <?php echo e(getSelected(1, 'active', $account_head, 1)); ?>>Active</option>
                    <option value="0" <?php echo e(getSelected(0, 'active', $account_head, 1)); ?>>Inactive</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="is_transaction">Use in vouchers</label>
                <?php ($defaultPosting = $account_head->exists ? $account_head->is_transaction : $selectedParent?->depth === 4); ?>
                <select class="form-select" id="is_transaction" name="is_transaction">
                    <option value="0" <?php echo e(getSelected(0, 'is_transaction', null, $defaultPosting)); ?>>Group only</option>
                    <option value="1" <?php echo e(getSelected(1, 'is_transaction', null, $defaultPosting)); ?>>Ledger account (level 5)</option>
                </select>
                <?php $__errorArgs = ['is_transaction'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="system_key">Cash / bank report role</label>
                <select class="<?php echo e(inputClass('system_key', 'form-select')); ?>" id="system_key" name="system_key">
                    <option value="">Inherit from parent</option>
                    <?php $__currentLoopData = $reportRoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($value); ?>" <?php echo e(getSelected($value, 'system_key', $account_head)); ?>><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['system_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="cash_flow_activity">Cash flow activity</label>
                <select class="<?php echo e(inputClass('cash_flow_activity', 'form-select')); ?>" id="cash_flow_activity" name="cash_flow_activity">
                    <option value="">Inherit from parent</option>
                    <?php $__currentLoopData = $cashFlowActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($value); ?>" <?php echo e(getSelected($value, 'cash_flow_activity', $account_head)); ?>><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['cash_flow_activity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-12"><label class="form-label" for="remarks">Remarks</label><textarea class="<?php echo e(inputClass('remarks')); ?>" id="remarks" name="remarks" rows="2" maxlength="2000"><?php echo e(getValue('remarks', $account_head)); ?></textarea></div>
        </div>
        <?php echo $__env->make('core::partials.form-actions', ['model' => $account_head, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Accounting\resources\views\account-heads\form.blade.php ENDPATH**/ ?>