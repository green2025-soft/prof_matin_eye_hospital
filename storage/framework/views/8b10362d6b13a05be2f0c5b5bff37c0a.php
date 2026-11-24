<?php $__currentLoopData = $balances['current']['rows']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
        <th class="fw-normal" scope="row"><?php echo e($row['account']->code); ?> - <?php echo e($row['account']->name); ?></th>
        <?php $__currentLoopData = $periods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $period): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <td class="text-end text-nowrap"><?php echo e(moneyDecimal((string) $balances[$key]['rows']->get($id)[$balanceField])); ?></td>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Accounting\resources\views\reports\cash-balance-rows.blade.php ENDPATH**/ ?>