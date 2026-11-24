<div class="table-responsive mt-3">
    <table class="table align-middle mb-0">
        <thead>
            <tr>
                <th scope="col">Head <span class="text-danger" aria-hidden="true">*</span></th>
                <th class="text-end" scope="col">Amount <span class="text-danger" aria-hidden="true">*</span></th>
                <th scope="col"><span class="visually-hidden">Remove</span></th>
            </tr>
        </thead>
        <tbody id="income-expense-rows">
            <?php $__empty_1 = true; $__currentLoopData = getValue('items', null, $record->items->all()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php echo $__env->make('tenantcore::partials.income-expense-row', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <?php echo $__env->make('tenantcore::partials.income-expense-row', ['index' => 0, 'item' => null], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <th class="text-end" scope="row">Total</th>
                <td class="text-end fw-semibold"><output id="income-expense-total" aria-live="polite"><?php echo e($record->amount ?? '0.00'); ?></output></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>
<?php $__errorArgs = ['items'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

<button class="btn btn-outline-primary btn-sm mt-2" id="income-expense-add-row" type="button">
    <i class="fa-solid fa-plus me-1" aria-hidden="true"></i>Add head
</button>

<template id="income-expense-row-template">
    <?php echo $__env->make('tenantcore::partials.income-expense-row', ['index' => '__INDEX__', 'item' => null], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</template>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\TenantCore\resources\views\partials\income-expense-items.blade.php ENDPATH**/ ?>