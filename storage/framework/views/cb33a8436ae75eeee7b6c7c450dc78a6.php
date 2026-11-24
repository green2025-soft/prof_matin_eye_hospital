<?php ($hasAdjustments = $creditAmount !== '0.00' || $collectedAmount !== '0.00'); ?>

<?php $__env->startSection('invoice-notes'); ?>
    <h2 class="app-clinical-invoice-section-title">Operation details</h2>
    <p class="app-clinical-invoice-note">Operation date: <?php echo e($operationDate ?? '-'); ?></p>
    <p class="app-clinical-invoice-note">Patient type: <?php echo e($operationPatientTypeLabel ?? '-'); ?></p>
    <?php if(filled($consultantName)): ?>
        <p class="app-clinical-invoice-note">Consultant: <?php echo e($consultantName); ?></p>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('invoice-content'); ?>
    <div class="table-responsive invoice-body-font">
        <table class="table app-invoice-table">

            <thead>
                <tr>
                    <th class="app-clinical-invoice-number" scope="col">No.</th>
                    <th scope="col">Operation</th>
                    <th class="app-clinical-invoice-eye" scope="col">Eye</th>
                    <th class="app-clinical-invoice-amount text-end" scope="col">Amount (Tk)</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $lines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($loop->iteration); ?></td>
                        <td class="text-break"><?php echo e($line['name']); ?></td>
                        <td><?php echo e($line['eye'] ?? '-'); ?></td>
                        <td class="text-end text-nowrap"><?php echo e($line['amount']); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td class="text-center text-body-secondary" colspan="4">No operation</td></tr>
                <?php endif; ?>
                <tr>
                     <td colspan="2">
                        <div class="app-invoice-note-paid text-start">
                             <div class="note-title">Remarks : <?php echo e($remarks??''); ?></div>
                            <div class="app-invoice-paid"><?php echo e($status); ?></div>
                        </div>
                     </td>
                     <td colspan="2" class="p-0">
                        <table class="table m-0 b-0 ">
                            <tbody>
                                <tr>
                                    <th class="text-start">Total Amount</th>
                                    <th>:</th>
                                    <th class="text-end"><?php echo e($amount); ?></th>
                                </tr>
                                 <tr>
                                    <th class="text-start">Discount</th>
                                    <th>:</th>
                                    <th class="text-end"><?php echo e($discount); ?></th>
                                </tr>

                                 <tr>
                                    <th class="text-start">Sub Total</th>
                                    <th>:</th>
                                    <th class="text-end"><?php echo e($payable); ?></th>
                                </tr>
                                <tr>
                                    <th class="text-start">Paid Amount</th>
                                    <th>:</th>
                                    <th class="text-end"><?php echo e($paid); ?></th>
                                </tr>
                                <tr>
                                    <th class="text-start">Due Amount</th>
                                    <th>:</th>
                                    <th class="text-end"><?php echo e($due); ?></th>
                                </tr>

                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="app-invoice-bottom-info">
            <p><strong>In Words :</strong> &nbsp; <?php echo e(amountInWords($paid)); ?></p>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('hospital::invoices.show', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Hospital\resources\views\patient-operations\invoice.blade.php ENDPATH**/ ?>