

<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <form class="app-card p-3 p-md-4" method="POST" action="<?php echo e($formAction); ?>">
        <?php echo csrf_field(); ?>
        <?php if($formMethod !== 'POST'): ?> <?php echo method_field($formMethod); ?> <?php endif; ?>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label" for="tenant_id">Tenant</label>
                <?php if($account->exists): ?>
                    <input class="form-control" id="tenant_id" value="<?php echo e($account->tenant->name); ?>" readonly>
                <?php else: ?>
                    <select class="<?php echo e(inputClass('tenant_id', 'form-select')); ?>" id="tenant_id" name="tenant_id" required>
                        <option value="">Select tenant</option>
                        <?php $__currentLoopData = $tenants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tenant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($tenant->id); ?>" <?php echo e(getSelected($tenant->id, 'tenant_id', null, $selectedTenantId)); ?>><?php echo e($tenant->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                <?php endif; ?>
                <?php $__errorArgs = ['tenant_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-3">
                <label class="form-label" for="rate">Rate / SMS part (BDT)</label>
                <input class="<?php echo e(inputClass('rate')); ?>" id="rate" name="rate" inputmode="decimal" value="<?php echo e(getValue('rate', null, $rate)); ?>" required>
                <?php $__errorArgs = ['rate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-3">
                <label class="form-label" for="monthly_limit">Monthly part limit</label>
                <input class="<?php echo e(inputClass('monthly_limit')); ?>" id="monthly_limit" name="monthly_limit" type="number" min="0" value="<?php echo e(getValue('monthly_limit', $account)); ?>" placeholder="Unlimited">
                <?php $__errorArgs = ['monthly_limit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-12">
                <input name="enabled" type="hidden" value="0">
                <label class="form-check form-switch"><input class="form-check-input" name="enabled" type="checkbox" value="1" <?php echo e(getChecked(1, 'enabled', $account, false)); ?>>Enable SMS</label>
                <?php $__errorArgs = ['enabled'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>
        <fieldset class="mt-4">
            <legend class="h6 mb-3">Allowed features</legend>
            <div class="row g-2">
                <?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-6 col-xl-4">
                        <label class="form-check"><input class="form-check-input" name="allowed_features[]" type="checkbox" value="<?php echo e($key); ?>" <?php echo e(getChecked($key, 'allowed_features', $account, [])); ?>><?php echo e($label); ?></label>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php $__errorArgs = ['allowed_features'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <?php $__errorArgs = ['allowed_features.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </fieldset>
        <section class="border rounded p-3 mt-4">
            <input name="gateway_enabled" type="hidden" value="0">
            <label class="form-check form-switch mb-2"><input class="form-check-input" name="gateway_enabled" type="checkbox" value="1" <?php echo e(getChecked(1, 'gateway_enabled', null, $hasGateway)); ?>>Use a private gateway</label>
            <details <?php if($hasGateway || $errors->has('gateway.*')): ?> open <?php endif; ?>>
                <summary class="small fw-semibold">Gateway configuration</summary>
                <div class="row g-3 mt-0">
                    <div class="col-md-8">
                        <label class="form-label" for="gateway_api_url">API URL</label>
                        <input class="<?php echo e(inputClass('gateway.api_url')); ?>" id="gateway_api_url" name="gateway[api_url]" type="url" value="<?php echo e(getValue('gateway.api_url', null, $gatewayValues['api_url'] ?? '')); ?>">
                        <?php $__errorArgs = ['gateway.api_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="gateway_sender_id">Sender ID</label>
                        <input class="<?php echo e(inputClass('gateway.sender_id')); ?>" id="gateway_sender_id" name="gateway[sender_id]" value="<?php echo e(getValue('gateway.sender_id', null, $gatewayValues['sender_id'] ?? '')); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="gateway_method">Method</label>
                        <select class="form-select" id="gateway_method" name="gateway[method]">
                            <option value="POST" <?php echo e(getSelected('POST', 'gateway.method', null, $gatewayValues['method'] ?? 'POST')); ?>>POST</option>
                            <option value="GET" <?php echo e(getSelected('GET', 'gateway.method', null, $gatewayValues['method'] ?? 'POST')); ?>>GET</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="gateway_body_format">Format</label>
                        <select class="form-select" id="gateway_body_format" name="gateway[body_format]">
                            <option value="form" <?php echo e(getSelected('form', 'gateway.body_format', null, $gatewayValues['body_format'] ?? 'form')); ?>>Form encoded</option>
                            <option value="json" <?php echo e(getSelected('json', 'gateway.body_format', null, $gatewayValues['body_format'] ?? 'form')); ?>>JSON</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="gateway_success_field">Success field</label>
                        <input class="form-control" id="gateway_success_field" name="gateway[success_field]" value="<?php echo e(getValue('gateway.success_field', null, $gatewayValues['success_field'] ?? '')); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="gateway_success_value">Success value</label>
                        <input class="form-control" id="gateway_success_value" name="gateway[success_value]" value="<?php echo e(getValue('gateway.success_value', null, $gatewayValues['success_value'] ?? '')); ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="gateway_failure_values">Confirmed failure values (comma separated)</label>
                        <input class="form-control" id="gateway_failure_values" name="gateway[failure_values]" value="<?php echo e(getValue('gateway.failure_values', null, $gatewayValues['failure_values'] ?? '')); ?>" maxlength="1000">
                        <?php $__errorArgs = ['gateway.failure_values'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <?php $__currentLoopData = $credentialFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $definition): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="<?php echo e(($definition['multiline'] ?? false) ? 'col-12' : 'col-md-6'); ?>">
                            <label class="form-label" for="gateway_<?php echo e($field); ?>"><?php echo e($definition['label']); ?></label>
                            <?php if($definition['multiline'] ?? false): ?>
                                <textarea class="<?php echo e(inputClass('gateway.'.$field)); ?>" id="gateway_<?php echo e($field); ?>" name="gateway[<?php echo e($field); ?>]" rows="3" autocomplete="off" spellcheck="false"><?php echo e(($gatewayConfigured[$field] ?? false) ? '' : ($definition['default'] ?? '')); ?></textarea>
                            <?php else: ?>
                                <input class="<?php echo e(inputClass('gateway.'.$field)); ?>" id="gateway_<?php echo e($field); ?>" name="gateway[<?php echo e($field); ?>]" type="password" autocomplete="new-password">
                            <?php endif; ?>
                            <?php if($gatewayConfigured[$field] ?? false): ?><div class="form-text">Configured. Blank keeps existing.</div><?php endif; ?>
                            <?php $__errorArgs = ['gateway.'.$field];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </details>
        </section>
        <div class="d-flex justify-content-end gap-2 border-top mt-4 pt-3">
            <a class="btn btn-warning" href="<?php echo e(route('core.sms-accounts.index')); ?>">Cancel</a>
            <button class="btn btn-primary" type="submit">Save</button>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\resources\views\sms-accounts\form.blade.php ENDPATH**/ ?>