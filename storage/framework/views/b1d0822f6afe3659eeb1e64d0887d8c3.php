<form class="app-card p-3 p-md-4" action="<?php echo e($formAction); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>
    <fieldset class="app-form-section border-0 p-0 mb-0" aria-labelledby="settings-pharmacy-heading" <?php if(! $canUpdate): echo 'disabled'; endif; ?>>
        <h2 class="app-form-section-title" id="settings-pharmacy-heading"><span class="app-form-section-icon"><i class="fa-solid fa-boxes-stacked" aria-hidden="true"></i></span>Pharmacy</h2>
        <?php $__errorArgs = ['data'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block mb-3"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <?php $__errorArgs = ['section'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block mb-3"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

        <input name="section" type="hidden" value="<?php echo e($activePharmacySection); ?>">
        <?php if($activePharmacySection === 'general'): ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="pharmacy-stock-method">Stock method</label>
                    <select class="<?php echo e(inputClass('data.stock_method', 'form-select')); ?>" id="pharmacy-stock-method" name="data[stock_method]" required>
                        <option value="fifo" <?php if(getValue('data.stock_method', null, $values['data']['stock_method']) === 'fifo'): echo 'selected'; endif; ?>>FIFO — oldest stock first</option>
                        <option value="lifo" <?php if(getValue('data.stock_method', null, $values['data']['stock_method']) === 'lifo'): echo 'selected'; endif; ?>>LIFO — newest stock first</option>
                    </select>
                    <?php $__errorArgs = ['data.stock_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="pharmacy-customer-scope">Sales customers</label>
                    <select class="<?php echo e(inputClass('data.customer_scope', 'form-select')); ?>" id="pharmacy-customer-scope" name="data[customer_scope]" required>
                        <option value="all" <?php if(getValue('data.customer_scope', null, $values['data']['customer_scope']) === 'all'): echo 'selected'; endif; ?>>All patients and customers</option>
                        <option value="customer" <?php if(getValue('data.customer_scope', null, $values['data']['customer_scope']) === 'customer'): echo 'selected'; endif; ?>>Customers only</option>
                    </select>
                    <?php $__errorArgs = ['data.customer_scope'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <div class="form-check form-switch">
                        <input type="hidden" name="data[medicine_sale_without_stock_enabled]" value="0">
                        <input class="<?php echo e(inputClass('data.medicine_sale_without_stock_enabled', 'form-check-input')); ?>" id="pharmacy-medicine-sale-without-stock" name="data[medicine_sale_without_stock_enabled]" type="checkbox" value="1" role="switch" <?php echo e(getChecked(1, 'data.medicine_sale_without_stock_enabled', null, $values['data']['medicine_sale_without_stock_enabled'])); ?> <?php echo e(inputAttrs('data.medicine_sale_without_stock_enabled', 'pharmacy-medicine-sale-without-stock')); ?>>
                        <label class="form-check-label" for="pharmacy-medicine-sale-without-stock">Medicine: allow sales without stock</label>
                    </div>
                    <?php $__errorArgs = ['data.medicine_sale_without_stock_enabled'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="pharmacy-medicine-sale-without-stock-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <div class="form-check form-switch">
                        <input type="hidden" name="data[optic_sale_without_stock_enabled]" value="0">
                        <input class="<?php echo e(inputClass('data.optic_sale_without_stock_enabled', 'form-check-input')); ?>" id="pharmacy-optic-sale-without-stock" name="data[optic_sale_without_stock_enabled]" type="checkbox" value="1" role="switch" <?php echo e(getChecked(1, 'data.optic_sale_without_stock_enabled', null, $values['data']['optic_sale_without_stock_enabled'])); ?> <?php echo e(inputAttrs('data.optic_sale_without_stock_enabled', 'pharmacy-optic-sale-without-stock')); ?>>
                        <label class="form-check-label" for="pharmacy-optic-sale-without-stock">Optic: allow sales without stock</label>
                    </div>
                    <?php $__errorArgs = ['data.optic_sale_without_stock_enabled'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="pharmacy-optic-sale-without-stock-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="pharmacy-sale-invoice-paper">Sales invoice paper</label>
                    <select class="<?php echo e(inputClass('data.sale_invoice_paper', 'form-select')); ?>" id="pharmacy-sale-invoice-paper" name="data[sale_invoice_paper]" required <?php echo e(inputAttrs('data.sale_invoice_paper', 'pharmacy-sale-invoice-paper')); ?>>
                        <option value="a4" <?php if(getValue('data.sale_invoice_paper', null, $values['data']['sale_invoice_paper']) === 'a4'): echo 'selected'; endif; ?>>A4</option>
                        <option value="a5" <?php if(getValue('data.sale_invoice_paper', null, $values['data']['sale_invoice_paper']) === 'a5'): echo 'selected'; endif; ?>>A5</option>
                        <option value="pos" <?php if(getValue('data.sale_invoice_paper', null, $values['data']['sale_invoice_paper']) === 'pos'): echo 'selected'; endif; ?>>POS</option>
                    </select>
                    <?php $__errorArgs = ['data.sale_invoice_paper'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="pharmacy-sale-invoice-paper-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6" data-pharmacy-pos-width <?php if(getValue('data.sale_invoice_paper', null, $values['data']['sale_invoice_paper']) !== 'pos' && ! $errors->has('data.sale_pos_width_mm')): ?> hidden <?php endif; ?>>
                    <label class="form-label" for="pharmacy-sale-pos-width">POS width (mm)</label>
                    <input class="<?php echo e(inputClass('data.sale_pos_width_mm')); ?>" id="pharmacy-sale-pos-width" name="data[sale_pos_width_mm]" type="number" inputmode="decimal" min="20" max="300" step="0.01" value="<?php echo e(getValue('data.sale_pos_width_mm', null, $values['data']['sale_pos_width_mm'])); ?>" <?php echo e(inputAttrs('data.sale_pos_width_mm', 'pharmacy-sale-pos-width')); ?>>
                    <?php $__errorArgs = ['data.sale_pos_width_mm'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="pharmacy-sale-pos-width-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input type="hidden" name="data[vat_enabled]" value="0">
                        <input class="<?php echo e(inputClass('data.vat_enabled', 'form-check-input')); ?>" id="pharmacy-vat-enabled" name="data[vat_enabled]" type="checkbox" value="1" role="switch" <?php echo e(getChecked(1, 'data.vat_enabled', null, $values['data']['vat_enabled'])); ?>>
                        <label class="form-check-label" for="pharmacy-vat-enabled">Enable VAT</label>
                    </div>
                    <?php $__errorArgs = ['data.vat_enabled'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="pharmacy-vat-mode">VAT calculation</label>
                    <select class="<?php echo e(inputClass('data.vat_mode', 'form-select')); ?>" id="pharmacy-vat-mode" name="data[vat_mode]" required>
                        <option value="exclusive" <?php if(getValue('data.vat_mode', null, $values['data']['vat_mode']) === 'exclusive'): echo 'selected'; endif; ?>>Add to price</option>
                        <option value="inclusive" <?php if(getValue('data.vat_mode', null, $values['data']['vat_mode']) === 'inclusive'): echo 'selected'; endif; ?>>Included in price</option>
                    </select>
                    <?php $__errorArgs = ['data.vat_mode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="pharmacy-vat-percent">VAT %</label>
                    <input class="<?php echo e(inputClass('data.vat_percent')); ?>" id="pharmacy-vat-percent" name="data[vat_percent]" type="number" min="0" max="100" step="0.01" value="<?php echo e(getValue('data.vat_percent', null, $values['data']['vat_percent'])); ?>" required>
                    <?php $__errorArgs = ['data.vat_percent'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        <?php elseif($activePharmacySection === 'numbering'): ?>
            <?php
                $patternLabels = [
                    'purchase_invoice_pattern' => 'Purchase',
                    'sale_invoice_pattern' => 'Sale',
                    'purchase_return_invoice_pattern' => 'Purchase return',
                    'sale_return_invoice_pattern' => 'Sale return',
                    'payment_invoice_pattern' => 'Payment',
                    'adjustment_invoice_pattern' => 'Adjustment',
                    'damage_invoice_pattern' => 'Wastage',
                    'transfer_invoice_pattern' => 'Branch transfer',
                ];
            ?>
            <p class="small text-body-secondary mb-2">Changes apply to new invoices.</p>
            <details class="small text-body-secondary mb-3">
                <summary>Pattern guide</summary>
                <p class="mt-2 mb-0" id="pharmacy-pattern-help">Use {YYYY}, {MM}, {DD}, {BRANCH}, {PRODUCT} (MED/OPT), and one {SEQ} or {SEQ:1} to {SEQ:12}. {BRANCH} is required. {SEQ} uses at least six digits. Counters continue when a pattern changes.</p>
            </details>
            <div class="row g-3">
                <?php $__currentLoopData = $patternLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-6">
                        <label class="form-label" for="pharmacy-<?php echo e($field); ?>"><?php echo e($label); ?></label>
                        <input class="<?php echo e(inputClass('data.'.$field)); ?>" id="pharmacy-<?php echo e($field); ?>" name="data[<?php echo e($field); ?>]" type="text" value="<?php echo e(getValue('data.'.$field, null, $values['data'][$field])); ?>" maxlength="<?php echo e($field === 'payment_invoice_pattern' ? 40 : 80); ?>" required <?php echo e(inputAttrs('data.'.$field, 'pharmacy-'.$field, 'pharmacy-pattern-help')); ?>>
                        <?php $__errorArgs = ['data.'.$field];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback d-block" id="pharmacy-<?php echo e($field); ?>-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php elseif($activePharmacySection === 'items'): ?>
            <?php
                $itemColumns = ['Discount', 'VAT', 'Sale price', 'Expiry'];
                $itemRows = [
                    'Medicine Purchase' => ['medicine_purchase_discount_enabled', 'medicine_purchase_vat_enabled', 'medicine_purchase_sale_price_enabled', 'medicine_purchase_expiry_enabled'],
                    'Optic Purchase' => ['optic_purchase_discount_enabled', 'optic_purchase_vat_enabled', 'optic_purchase_sale_price_enabled', null],
                    'Medicine Sales' => ['medicine_sale_discount_enabled', null, null, null],
                    'Optic Sales' => ['optic_sale_discount_enabled', null, null, null],
                ];
            ?>
            <p class="small text-body-secondary mb-2">Choose which item fields appear.</p>
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <caption class="visually-hidden">Item field visibility</caption>
                    <thead>
                        <tr>
                            <th scope="col">Form</th>
                            <?php $__currentLoopData = $itemColumns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <th class="text-center" scope="col"><?php echo e($label); ?></th>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $itemRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $fields): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <th scope="row"><?php echo e($label); ?></th>
                                <?php $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column => $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <td class="text-center">
                                        <?php if($field !== null): ?>
                                            <div class="form-check form-switch d-inline-block mb-0">
                                                <input name="data[<?php echo e($field); ?>]" type="hidden" value="0">
                                                <input class="<?php echo e(inputClass('data.'.$field, 'form-check-input')); ?>" id="pharmacy-<?php echo e($field); ?>" name="data[<?php echo e($field); ?>]" type="checkbox" value="1" role="switch" <?php echo e(getChecked(1, 'data.'.$field, null, $values['data'][$field])); ?> <?php echo e(inputAttrs('data.'.$field, 'pharmacy-'.$field)); ?>>
                                                <label class="visually-hidden" for="pharmacy-<?php echo e($field); ?>"><?php echo e($label); ?> <?php echo e($itemColumns[$column]); ?></label>
                                            </div>
                                            <?php $__errorArgs = ['data.'.$field];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback d-block" id="pharmacy-<?php echo e($field); ?>-error"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        <?php else: ?>
                                            <span class="text-body-secondary" aria-label="Not available">&mdash;</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php elseif($activePharmacySection === 'sms'): ?>
            <p class="small text-body-secondary mb-2">Send SMS for new records only. Edits and reversals do not send another message. Platform access and SMS balance apply.</p>
            <details class="small text-body-secondary mb-3">
                <summary>Message placeholders</summary>
                <p class="mt-2 mb-1">Organization: {organization}, {organization_phone}, {organization_address}.</p>
                <p class="mb-1">{name}, {invoice}, {date}, {total}, {paid}, {due}, {amount}</p>
                <p class="mb-1">Customer SMS can also use {patient_name}, {patient_mobile}, {patient_address}, {patient_id}, {patient_age}, {father_husband_name}. Choose the patient details needed in your message.</p>
                <p class="mb-1">Sales returns also support {source_invoice}, {refund}, {due_adjustment}. Payments support {direction}.</p>
                <p class="mb-0">Return total is invoice credit; refund is cash returned. For payments, total includes cash and discount; amount and paid are cash. Payment due is the party's remaining balance in this branch.</p>
            </details>
            <div class="row g-3">
                <?php $__currentLoopData = collect($smsNotifications)->groupBy('type'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $typeLabel => $notifications): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <section class="col-lg-6" aria-labelledby="pharmacy-sms-<?php echo e(strtolower($typeLabel)); ?>-heading">
                        <h3 class="h6 fw-semibold mb-3" id="pharmacy-sms-<?php echo e(strtolower($typeLabel)); ?>-heading"><?php echo e($typeLabel); ?></h3>
                        <div class="vstack gap-3">
                            <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $enabledField = $notification['enabled'];
                                    $templateField = $notification['template'];
                                ?>
                                <div class="border rounded p-3">
                                    <div class="form-check form-switch mb-2">
                                        <input name="data[<?php echo e($enabledField); ?>]" type="hidden" value="0">
                                        <input class="<?php echo e(inputClass('data.'.$enabledField, 'form-check-input')); ?>" id="pharmacy-<?php echo e($enabledField); ?>"
                                            name="data[<?php echo e($enabledField); ?>]" type="checkbox" value="1" role="switch"
                                            <?php echo e(getChecked(1, 'data.'.$enabledField, null, $values['data'][$enabledField])); ?>

                                            <?php echo e(inputAttrs('data.'.$enabledField, 'pharmacy-'.$enabledField)); ?>>
                                        <label class="form-check-label fw-medium" for="pharmacy-<?php echo e($enabledField); ?>"><?php echo e($notification['label']); ?> SMS</label>
                                    </div>
                                    <?php $__errorArgs = ['data.'.$enabledField];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback d-block mb-2" id="pharmacy-<?php echo e($enabledField); ?>-error"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <label class="form-label small mb-1" for="pharmacy-<?php echo e($templateField); ?>">Message</label>
                                    <textarea class="<?php echo e(inputClass('data.'.$templateField)); ?>" id="pharmacy-<?php echo e($templateField); ?>" name="data[<?php echo e($templateField); ?>]" rows="2"
                                        <?php echo e(inputAttrs('data.'.$templateField, 'pharmacy-'.$templateField)); ?>><?php echo e(getValue('data.'.$templateField, null, $values['data'][$templateField])); ?></textarea>
                                    <?php $__errorArgs = ['data.'.$templateField];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback d-block" id="pharmacy-<?php echo e($templateField); ?>-error"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </section>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </fieldset>
    <?php if($canUpdate): ?>
        <div class="d-flex justify-content-end border-top pt-2 mt-3">
            <button class="btn btn-primary" type="submit"><i class="fa-solid fa-check me-2" aria-hidden="true"></i>Save</button>
        </div>
    <?php else: ?>
        <p class="small text-body-secondary mb-0 mt-3">You have read-only access to these settings.</p>
    <?php endif; ?>
</form>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\settings\form.blade.php ENDPATH**/ ?>