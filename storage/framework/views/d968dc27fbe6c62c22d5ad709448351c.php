<?php $__env->startSection('title', ucfirst($filters['product_type'] ?? 'medicine').' '.$pageTitle); ?>
<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between gap-3 mb-3">
        <?php echo $__env->make('core::partials.heading', ['title' => ucfirst($filters['product_type'] ?? 'medicine').' '.$pageTitle], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php if($canCreate): ?>
            <a class="btn btn-primary text-nowrap align-self-start"
                href="<?php echo e(route('pharmacy.purchase-payments.create', ['product_type' => $filters['product_type']])); ?>">
                New Payment
            </a>
        <?php endif; ?>
    </div>
    <section class="app-card overflow-hidden">
        <form class="p-3 border-bottom" method="GET" action="<?php echo e(route('pharmacy.purchase-payments.index')); ?>">
            <input name="product_type" type="hidden" value="<?php echo e($filters['product_type'] ?? 'medicine'); ?>">
            <input name="per_page" type="hidden" value="<?php echo e($filters['per_page'] ?? config('pagination.default')); ?>">
            <div class="row g-2 align-items-end">
                <div class="col-sm-6 col-lg-2">
                    <label class="form-label small" for="from_date">From Date</label>
                    <div class="input-group" data-datepicker>
                        <input class="<?php echo e(inputClass('from_date')); ?>" id="from_date" name="from_date" type="text"
                            inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD"
                            value="<?php echo e(getValue('from_date', null, $filters['from_date'] ?? '')); ?>" data-input
                            <?php echo e(inputAttrs('from_date')); ?>>
                        <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="from_date"
                            aria-label="Choose start date" title="Choose start date">
                            <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
                        </button>
                    </div>
                    <?php $__errorArgs = ['from_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback d-block" id="from_date-error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <label class="form-label small" for="to_date">To Date</label>
                    <div class="input-group" data-datepicker>
                        <input class="<?php echo e(inputClass('to_date')); ?>" id="to_date" name="to_date" type="text"
                            inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD"
                            value="<?php echo e(getValue('to_date', null, $filters['to_date'] ?? '')); ?>" data-input
                            <?php echo e(inputAttrs('to_date')); ?>>
                        <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="to_date"
                            aria-label="Choose end date" title="Choose end date">
                            <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
                        </button>
                    </div>
                    <?php $__errorArgs = ['to_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback d-block" id="to_date-error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-lg-4 d-flex gap-2">
                    <button class="btn btn-primary" type="submit">Filter</button>
                    <a class="btn btn-outline-secondary"
                        href="<?php echo e(route('pharmacy.purchase-payments.index', ['product_type' => $filters['product_type'], 'per_page' => $filters['per_page'] ?? config('pagination.default')])); ?>">
                        Reset
                    </a>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table app-table align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">Number</th>
                        <th scope="col">Date</th>
                        <th scope="col">Supplier</th>
                        <th scope="col" class="text-end">Collection</th>
                        <th scope="col" class="text-end">Discount</th>
                        
                        <?php if($canDelete): ?>
                            <th scope="col" class="text-end">Actions</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <a href="<?php echo e(route('pharmacy.purchase-payments.show', ['purchase_payment' => $record, 'product_type' => $record->product_type])); ?>">
                                    <?php echo e($record->number); ?>

                                </a>
                            </td>
                            <td><?php echo e($record->payment_date->format('Y-m-d')); ?></td>
                            <td><?php echo e($suppliers[$record->supplier_id] ?? '—'); ?></td>
                            <td class="text-end"><?php echo e(moneyDecimal($record->amount)); ?></td>
                            <td class="text-end"><?php echo e(moneyDecimal($record->discount)); ?></td>
                            
                            <?php if($canDelete): ?>
                                <td class="text-end">
                                    <form action="<?php echo e(route('pharmacy.purchase-payments.destroy', [
                                        'purchase_payment' => $record,
                                        'product_type' => $record->product_type,
                                    ])); ?>" method="POST"
                                        data-confirm="Permanently delete this payment? Active invoice and journal effects will be reversed.">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button class="btn btn-outline-danger btn-sm app-action-button" type="submit" aria-label="Delete <?php echo e($record->number); ?>" title="Delete">
                                            <i class="fa-solid fa-trash-can" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td class="text-center text-body-secondary py-4" colspan="<?php echo e(5 + (int) $canDelete); ?>">
                                No supplier payments found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php echo $__env->make('core::partials.pagination', ['paginator' => $records, 'filters' => $filters, 'routePrefix' => $routePrefix], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\purchase-payments\index.blade.php ENDPATH**/ ?>