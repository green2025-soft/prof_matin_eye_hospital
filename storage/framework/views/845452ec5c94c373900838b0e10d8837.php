<div class="table-responsive">
    <table class="app-report-table table app-table table-sm align-middle mb-0">
        <caption class="visually-hidden">Daily cash movements and closing dues</caption>
        <thead>
            <tr>
                <th scope="col">Date</th>
                <th class="text-end" scope="col">Opening</th>
                <th class="text-end" scope="col">Received</th>
                <th class="text-end" scope="col">Paid</th>
                <th class="text-end" scope="col">Closing</th>
                <th class="text-end" scope="col">Receivable</th>
                <th class="text-end" scope="col">Payable</th>
                <th class="text-end" scope="col">Balance</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $previousDay = $openingSnapshot;
            ?>
            <?php $__currentLoopData = \Carbon\CarbonPeriodImmutable::create($fromDate, $toDate); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $day = $days[$date->toDateString()] ?? [
                        'opening' => $previousDay['closing'],
                        'received' => 0,
                        'paid' => 0,
                        'closing' => $previousDay['closing'],
                        'receivable' => $previousDay['receivable'],
                        'payable' => $previousDay['payable'],
                        'balance' => $previousDay['balance'],
                    ];
                    $previousDay = $day;
                ?>
                <tr>
                    <th class="text-nowrap" scope="row"><?php echo e($date->toDateString()); ?></th>
                    <td class="text-end text-nowrap"><?php echo e(moneyDecimal((string) $day['opening'])); ?></td>
                    <td class="text-end text-nowrap"><?php echo e(moneyDecimal((string) $day['received'])); ?></td>
                    <td class="text-end text-nowrap"><?php echo e(moneyDecimal((string) $day['paid'])); ?></td>
                    <td class="text-end text-nowrap"><?php echo e(moneyDecimal((string) $day['closing'])); ?></td>
                    <td class="text-end text-nowrap"><?php echo e(moneyDecimal((string) $day['receivable'])); ?></td>
                    <td class="text-end text-nowrap"><?php echo e(moneyDecimal((string) $day['payable'])); ?></td>
                    <td class="text-end text-nowrap"><?php echo e(moneyDecimal((string) $day['balance'])); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\TenantCore\resources\views\reports\partials\day-table.blade.php ENDPATH**/ ?>