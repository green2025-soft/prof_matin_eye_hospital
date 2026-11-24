<?php ($hasAdjustments = $creditAmount !== '0.00' || $collectedAmount !== '0.00'); ?>

<?php $__env->startSection('invoice-content'); ?>
    <div class="table-responsive invoice-body-font">
        <table class="table app-invoice-table text-center">
            <thead>
                <tr>
                    <th scope="col" class="text-start">Appointment Doctor Name</th>
                    <th scope="col" class="text-center">Type </th>
                    <th class="app-clinical-invoice-amount text-end" scope="col">Fee (Tk)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-start"><?php echo e($doctor?->name ?? '-'); ?></td>
                    <td><?php echo e((patientTypes()[$objData->patient_type] ?? $objData->patient_type)); ?></td>
                    <td class="text-end text-nowrap"><?php echo e($objData->amount); ?></td>
                </tr>

                <tr>
                    <td>
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

<?php echo $__env->make('hospital::invoices.show', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Hospital\resources\views\consultations\invoice.blade.php ENDPATH**/ ?>