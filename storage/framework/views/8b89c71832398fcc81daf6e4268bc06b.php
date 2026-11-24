<?php $__env->startSection('title', ucfirst($entry->product_type).' Sales Invoice'); ?>

<?php $__env->startSection('content'); ?>
    <div class="app-print-hidden ">
        <?php echo $__env->make('core::partials.heading', ['title' => ucfirst($entry->product_type).' '.$entry->number], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div class="d-flex flex-wrap gap-2 mb-3">
            <a class="btn btn-outline-secondary btn-sm" href="<?php echo e(route($routePrefix.'.index', ['product_type' => $entry->product_type])); ?>">
                <i class="fa-solid fa-arrow-left me-2" aria-hidden="true"></i>Back
            </a>
            <button class="btn btn-primary btn-sm" type="button" data-print>
                <i class="fa-solid fa-print me-2" aria-hidden="true"></i>Print
            </button>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $entry)): ?>
                <a class="btn btn-outline-primary btn-sm" href="<?php echo e(route($routePrefix.'.edit', [$resourceParameter => $entry, 'product_type' => $entry->product_type])); ?>">
                    <i class="fa-solid fa-pen-to-square me-2" aria-hidden="true"></i>Edit
                </a>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $entry)): ?>
                <form action="<?php echo e(route($routePrefix.'.destroy', [$resourceParameter => $entry, 'product_type' => $entry->product_type])); ?>" method="POST" data-confirm="Delete this sales invoice and reverse its stock and amounts?">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button class="btn btn-outline-danger btn-sm" type="submit"><i class="fa-solid fa-trash-can me-2" aria-hidden="true"></i>Delete</button>
                </form>
            <?php endif; ?>

        </div>
    </div>

    <?php
        $invoiceTitle = 'Sales Invoice';
        $outstandingMinor = $entry->voided_at ? 0 : $entry->outstandingMinor();
        $invoiceStatus = $entry->voided_at ? 'VOID' : ($outstandingMinor > 0 ? 'DUE' : 'PAID');
        $invoicePaper = in_array($invoicePaper ?? null, ['a4', 'a5', 'pos'], true) ? $invoicePaper : 'a4';
        $isPos = $invoicePaper === 'pos';
        $hasVat = $entry->vat > 0;
        $itemDiscountTotal = $entry->lines->sum('discount');
        $invoiceDiscountTotal = $entry->discount - $itemDiscountTotal;
        $salePosWidth = is_string($salePosWidth ?? null) && preg_match('/\A\d{1,3}(?:\.\d{1,2})?\z/', $salePosWidth)
            && (float) $salePosWidth >= 20 && (float) $salePosWidth <= 300 ? $salePosWidth : '80';
        $salePosWidth = rtrim(rtrim(number_format((float) $salePosWidth, 2, '.', ''), '0'), '.');
    ?>

    <style>
        @media print {
            @page pharmacy-sale {
                size: <?php echo e(match ($invoicePaper) { 'a5' => 'A5', 'pos' => $salePosWidth.'mm 297mm', default => 'A4' }); ?>;
                margin: <?php echo e($isPos ? '2mm 0 0' : '10mm'); ?>;
            }

            .app-shell,
            .app-page,
            .app-content,
            .app-sale-invoice {
                page: pharmacy-sale;
            }
        }
    </style>
     <?php if($isPos): ?>
    <style>
         @media print {
            .app-shell,
            .app-page,
            .app-content {
                width: 100%;
                max-width: none;
                min-height: 0;
                margin: 0 !important;
                padding: 0 !important;
                overflow: visible;
            }
         }
    </style>
    <?php endif; ?>


    <article class="app-card app-invoice-document app-clinical-invoice app-pharmacy-invoice app-sale-invoice app-sale-invoice-<?php echo e($invoicePaper); ?>" <?php if($isPos): ?> style="--sale-pos-width: <?php echo e($salePosWidth); ?>mm" <?php endif; ?> aria-labelledby="pharmacy-invoice-title">

        <header class="app-clinical-invoice-header">
            <div class="app-clinical-invoice-brand">
                <?php if($logoUrl): ?>
                    <img class="app-clinical-invoice-logo" src="<?php echo e($logoUrl); ?>" alt="" width="76" height="76">
                <?php endif; ?>
                <div>
                    <h2 class="app-clinical-invoice-company"><?php echo e($company['name']); ?></h2>
                    <?php if($company['branch_name'] ?? ''): ?>
                        
                    <?php endif; ?>
                    <?php if($company['address'] ?? ''): ?>
                        <p class="app-clinical-invoice-address"><?php echo e($company['address']); ?></p>
                    <?php endif; ?>
                    <div class="app-clinical-invoice-contact">
                        <?php if($company['phone'] ?? ''): ?>
                            <span>Phone: <?php echo e($company['phone']); ?></span>
                        <?php endif; ?>
                        <?php if($company['email'] ?? ''): ?>
                            <span><?php echo e($company['email']); ?></span>
                        <?php endif; ?>
                        <?php if($company['tax_id'] ?? ''): ?>
                            <span>Tax ID: <?php echo e($company['tax_id']); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="app-clinical-invoice-title-row">
                <div>
                    <h1 id="pharmacy-invoice-title" class="text-center"><?php echo e($invoiceTitle); ?></h1>
                </div>

            </div>
        </header>

        <div class="app-pharmacy-invoice-parties">
            <table>
                <tr>
                    <td><b>Inv.No :</b> <?php echo e($entry->number); ?></td>
                    <td class="text-end"><b>Date :</b> <?php echo e($entry->entry_date->format('Y-m-d')); ?></td>
                </tr>
                <tr>
                    <td colspan="2"><b>Name : </b> <?php echo e($customer['name'] ?? '—'); ?></td>
                </tr>
                  <?php if($customer['mobile_no'] ?? ''): ?>
                  <tr>
                      <td colspan="2"><b>Mobile : </b> <?php echo e($customer['mobile_no'] ?? '—'); ?></td>
                  </tr>
                <?php endif; ?>
                <?php if($customer['address'] ?? ''): ?>
                <tr>
                      <td colspan="2"><b>Address : </b> <?php echo e($customer['address'] ?? '—'); ?></td>
                  </tr>
                <?php endif; ?>
            </table>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>P.Name</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Discount</th>
                        <th class="text-end">Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $entry->lines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($line->variant->product->name); ?></td>
                        <td  class="text-center">
                            <?php echo e($line->quantity); ?> <?php echo e($line->productUnit->unit->symbol ?: $line->productUnit->unit->name); ?>

                            <?php if($line->factor !== 1): ?>
                                <small class="d-block text-body-secondary"><?php echo e($line->base_quantity); ?> Pcs</small>
                            <?php endif; ?>
                        </td>
                        <td class="text-end"><?php echo e(moneyDecimal($line->discount)); ?></td>
                        <td class="text-end fw-semibold">
                            <?php echo e(moneyDecimal($line->total + $line->invoice_discount)); ?>

                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Sub Total  </th>
                        <th class="text-end"><?php echo e(moneyDecimal($entry->subtotal)); ?></th>
                    </tr>
                    <tr>
                        <th colspan="3" class="text-end">Item Discount</th>
                        <th class="text-end"><?php echo e(moneyDecimal($itemDiscountTotal)); ?></th>
                    </tr>
                    <tr>
                        <th colspan="3" class="text-end">Invoice Discount</th>
                        <th class="text-end"><?php echo e(moneyDecimal($invoiceDiscountTotal)); ?></th>
                    </tr>
                     <?php if($hasVat): ?>
                    <tr>
                        <th colspan="3" class="text-end">VAT</th>
                        <th class="text-end"><?php echo e(moneyDecimal($entry->vat)); ?></th>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <th colspan="3" class="text-end">Total</th>
                        <th class="text-end"><?php echo e(moneyDecimal($entry->total)); ?></th>
                    </tr>
                    <tr>
                        <th colspan="3" class="text-end">Paid</th>
                        <th class="text-end"><?php echo e(moneyDecimal($entry->paid)); ?></th>
                    </tr>
                    <tr>
                        <th colspan="3" class="text-end">Due</th>
                        <th class="text-end"><?php echo e(moneyDecimal($outstandingMinor)); ?></th>
                    </tr>
                </tfoot>
            </table>
             <?php if($entry->notes): ?>
              <p>
                <b class="app-clinical-invoice-section-title">Remarks:</b>
                <span  class="app-pharmacy-invoice-note"><?php echo e($entry->notes); ?></span>
            </p>
            <?php endif; ?>
            <div class="app-pharmacy-invoice-signatures">
                <div class="app-clinical-invoice-signature"><span>Received by</span></div>
                <div class="app-clinical-invoice-signature"><span>Authorized signature</span></div>
            </div>
        </div>
    </article>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\sales\show.blade.php ENDPATH**/ ?>