<?php $__env->startSection('invoice-content'); ?>

    <div class="table-responsive invoice-body-font">
        <table class="table app-invoice-table">

            <thead>
                <tr>
                    <th scope="col">Service</th>
                    <th scope="col">Invoice</th>
                    
                    <th scope="col" class="text-end">Discount</th>
                    <th class="app-clinical-invoice-amount text-end" scope="col">Amount</th>
                </tr>
            </thead>
            <tbody>

                <?php
                $invSubTotal = $objData->service;

                 ?>
                <tr>
                    <td><?php echo e($sourceServiceLabel); ?></td>
                    <td class="text-break"><?php echo e($originalInvoiceNumber); ?></td>
                    
                    <td class="text-break text-end"><?php echo e($objData->discount); ?></td>
                    <td class="text-end text-nowrap"><?php echo e($collectionAmount); ?></td>
                </tr>
                
            </tbody>
        </table>
        <div class="app-invoice-bottom-info">
            <p><strong>In Words :</strong> &nbsp; <?php echo e(amountInWords($collectionAmount)); ?></p>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('hospital::invoices.show', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Hospital\resources\views\due-collections\invoice.blade.php ENDPATH**/ ?>