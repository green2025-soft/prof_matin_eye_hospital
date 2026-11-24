<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => 'Edit posting mapping'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <form class="app-card p-3 p-md-4" action="<?php echo e($formAction); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <div class="row g-3 mb-3">
            <div class="col-md-6"><label class="form-label" for="name">Name</label><input class="<?php echo e(inputClass('name')); ?>" id="name" name="name" value="<?php echo e(getValue('name', $account_module)); ?>" maxlength="150" required></div>
            <div class="col-md-3">
                <label class="form-label" for="voucher-type">Voucher type</label>
                <select class="<?php echo e(inputClass('voucher_type_id', 'form-select')); ?>" id="voucher-type" name="voucher_type_id" required>
                    <?php $__currentLoopData = $voucherTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $voucherType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($voucherType->id); ?>" <?php echo e(getSelected($voucherType->id, 'voucher_type_id', $account_module)); ?>><?php echo e($voucherType->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['voucher_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-3"><label class="form-label" for="active">Automatic posting</label><select class="form-select" id="active" name="active"><option value="1" <?php echo e(getSelected(1, 'active', $account_module)); ?>>Enabled</option><option value="0" <?php echo e(getSelected(0, 'active', $account_module)); ?>>Disabled</option></select></div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light"><tr><th>Component</th><th>Side</th><th>Default ledger</th><th>Payment type</th></tr></thead>
                <tbody>
                    <?php $__currentLoopData = $account_module->components; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $component): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <th scope="row"><?php echo e($component->name); ?><input type="hidden" name="components[<?php echo e($index); ?>][id]" value="<?php echo e($component->id); ?>"></th>
                            <td><?php echo e(ucfirst($component->side)); ?></td>
                            <td>
                                <?php ($selectedLedger = $selectedLedgers->get(getValue('components.'.$index.'.account_head_id', null, $component->account_head_id))); ?>
                                <label class="visually-hidden" for="component-ledger-<?php echo e($component->id); ?>"><?php echo e($component->name); ?> ledger</label>
                                <select class="<?php echo e(inputClass('components.'.$index.'.account_head_id', 'form-select')); ?>"
                                    id="component-ledger-<?php echo e($component->id); ?>" name="components[<?php echo e($index); ?>][account_head_id]" required
                                    data-ajax-select data-url="<?php echo e(route('accounting.ledgers.options', ['active' => 1])); ?>"
                                    data-placeholder="Search ledger name or code">
                                    <option value=""></option>
                                    <?php if($selectedLedger): ?>
                                        <option value="<?php echo e($selectedLedger->id); ?>" selected><?php echo e($selectedLedger->code); ?> · <?php echo e($selectedLedger->name); ?><?php if (! ($selectedLedger->active)): ?> (inactive)<?php endif; ?></option>
                                    <?php endif; ?>
                                </select>
                                <?php $__errorArgs = ['components.'.$index.'.account_head_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </td>
                            <td class="small text-body-secondary"><?php echo e($component->use_payment_type ? 'Default when no Payment Type is selected' : 'Default ledger'); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <?php echo $__env->make('core::partials.form-actions', ['model' => $account_module, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Accounting\resources\views\account-modules\form.blade.php ENDPATH**/ ?>