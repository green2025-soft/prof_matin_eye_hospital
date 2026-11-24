<?php $__empty_1 = true; $__currentLoopData = $headCatalog; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module => $heads): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <?php $__currentLoopData = array_keys($heads, $side, true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td class="ps-3">
                <?php echo e($label); ?> <span class="d-block d-print-inline small text-body-secondary"><?php echo e(ucfirst($module)); ?></span>
            </td>
            <td class="text-end text-nowrap pe-3"><?php echo e(moneyDecimal((string) ($headTotals[$module.'|'.$label][$side] ?? 0))); ?></td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <tr><td colspan="2" class="text-body-secondary p-3">No report heads available.</td></tr>
<?php endif; ?>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\TenantCore\resources\views\reports\partials\head-rows.blade.php ENDPATH**/ ?>