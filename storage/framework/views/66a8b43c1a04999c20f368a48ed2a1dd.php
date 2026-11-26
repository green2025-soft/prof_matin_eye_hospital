<form class="app-card p-3 p-md-4" action="<?php echo e($formAction); ?>" method="POST" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>
    <fieldset class="app-form-section border-0 p-0 mb-0" aria-labelledby="settings-<?php echo e($setting->group); ?>-heading" <?php if(! $canUpdate): echo 'disabled'; endif; ?>>
        <h2 class="app-form-section-title" id="settings-<?php echo e($setting->group); ?>-heading"><span class="app-form-section-icon"><i class="fa-solid fa-<?php echo e($groupIcon); ?>" aria-hidden="true"></i></span><?php echo e($groupLabel); ?></h2>
        <?php $__errorArgs = ['data'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block mb-3"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

        <?php if($activeGroup === 'payments'): ?>
            <div class="row g-3 align-items-start mb-4">
                <div class="col-md-6">
                    <div class="form-check mb-3">
                        <input name="data[enabled]" type="hidden" value="0">
                        <input class="<?php echo e(inputClass('data.enabled', 'form-check-input')); ?>"
                            id="payment-<?php echo e($setting->group); ?>-enabled" name="data[enabled]" type="checkbox" value="1"
                            <?php echo e(getChecked(1, 'data.enabled', null, $values['data']['enabled'])); ?>

                            <?php echo e(inputAttrs('data.enabled', 'payment-'.$setting->group.'-enabled')); ?>>
                        <label class="form-check-label" for="payment-<?php echo e($setting->group); ?>-enabled">
                            Enable <?php echo e($groupLabel); ?>

                        </label>
                        <?php $__errorArgs = ['data.enabled'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback d-block" id="payment-<?php echo e($setting->group); ?>-enabled-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <?php if($setting->group !== 'manual'): ?>
                        <div class="form-check">
                            <input name="data[sandbox]" type="hidden" value="0">
                            <input class="<?php echo e(inputClass('data.sandbox', 'form-check-input')); ?>"
                                id="payment-<?php echo e($setting->group); ?>-sandbox" name="data[sandbox]" type="checkbox" value="1"
                                <?php echo e(getChecked(1, 'data.sandbox', null, $values['data']['sandbox'])); ?>

                                <?php echo e(inputAttrs('data.sandbox', 'payment-'.$setting->group.'-sandbox')); ?>>
                            <label class="form-check-label" for="payment-<?php echo e($setting->group); ?>-sandbox">Sandbox mode</label>
                            <?php $__errorArgs = ['data.sandbox'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block" id="payment-<?php echo e($setting->group); ?>-sandbox-error"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="payment-<?php echo e($setting->group); ?>-charge">Extra charge</label>
                    <input class="<?php echo e(inputClass('data.extra_charge')); ?>" id="payment-<?php echo e($setting->group); ?>-charge"
                        name="data[extra_charge]" type="text" inputmode="decimal"
                        value="<?php echo e(getValue('data.extra_charge', null, $values['data']['extra_charge'])); ?>" maxlength="13"
                        placeholder="0.00" required
                        <?php echo e(inputAttrs('data.extra_charge', 'payment-'.$setting->group.'-charge', 'payment-'.$setting->group.'-charge-help')); ?>>
                    <div class="form-text" id="payment-<?php echo e($setting->group); ?>-charge-help">
                        Fixed amount for this payment method. Enter 0.00 for no extra charge.
                    </div>
                    <?php $__errorArgs = ['data.extra_charge'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback d-block" id="payment-<?php echo e($setting->group); ?>-charge-error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
            <?php if($credentialFields !== []): ?>
                <?php $__errorArgs = ['credentials'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block mb-3"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <div class="row g-3">
                    <?php echo $__env->make('core::settings.credentials', ['credentialFields' => $credentialFields], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if($setting->group === 'company'): ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="company-name">Company name <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="<?php echo e(inputClass('data.name')); ?>" id="company-name" name="data[name]" type="text" value="<?php echo e(getValue('data.name', null, $values['data']['name'])); ?>" maxlength="255" autocomplete="organization" required <?php echo e(inputAttrs('data.name', 'company-name')); ?>>
                    <?php $__errorArgs = ['data.name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="company-name-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="company-email">Email address</label>
                    <input class="<?php echo e(inputClass('data.email')); ?>" id="company-email" name="data[email]" type="email" value="<?php echo e(getValue('data.email', null, $values['data']['email'])); ?>" maxlength="255" autocomplete="email" <?php echo e(inputAttrs('data.email', 'company-email')); ?>>
                    <?php $__errorArgs = ['data.email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="company-email-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="company-phone">Phone</label>
                    <input class="<?php echo e(inputClass('data.phone')); ?>" id="company-phone" name="data[phone]" type="tel" inputmode="numeric" pattern="[0-9]{1,30}" value="<?php echo e(getValue('data.phone', null, $values['data']['phone'])); ?>" maxlength="30" autocomplete="tel" <?php echo e(inputAttrs('data.phone', 'company-phone')); ?>>
                    <?php $__errorArgs = ['data.phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="company-phone-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <?php if (! ($isPlatform)): ?>
                    <div class="col-md-6">
                        <label class="form-label" for="company-website">Website</label>
                        <input class="<?php echo e(inputClass('data.website')); ?>" id="company-website" name="data[website]" type="text" value="<?php echo e(getValue('data.website', null, $values['data']['website'] ?? '')); ?>" maxlength="255" placeholder="https://example.com"  <?php echo e(inputAttrs('data.website', 'company-website')); ?>>
                        <?php $__errorArgs = ['data.website'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="company-website-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                <?php endif; ?>
                
                <div class="col-md-6">
                    <label class="form-label" for="company-address">Address</label>
                    <textarea class="<?php echo e(inputClass('data.address')); ?>" id="company-address" name="data[address]" rows="3" maxlength="2000" autocomplete="street-address" <?php echo e(inputAttrs('data.address', 'company-address')); ?>><?php echo e(getValue('data.address', null, $values['data']['address'])); ?></textarea>
                    <?php $__errorArgs = ['data.address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="company-address-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        <?php elseif($setting->group === 'branding'): ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="branding-name">Application name <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="<?php echo e(inputClass('data.app_name')); ?>" id="branding-name" name="data[app_name]" type="text" value="<?php echo e(getValue('data.app_name', null, $values['data']['app_name'])); ?>" maxlength="120" required <?php echo e(inputAttrs('data.app_name', 'branding-name')); ?>>
                    <?php $__errorArgs = ['data.app_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="branding-name-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="branding-tagline">Tagline</label>
                    <input class="<?php echo e(inputClass('data.tagline')); ?>" id="branding-tagline" name="data[tagline]" type="text" value="<?php echo e(getValue('data.tagline', null, $values['data']['tagline'])); ?>" maxlength="255" <?php echo e(inputAttrs('data.tagline', 'branding-tagline')); ?>>
                    <?php $__errorArgs = ['data.tagline'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="branding-tagline-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="branding-logo">Logo</label>
                    <?php if($logoUrl): ?>
                        <img class="app-brand-preview img-thumbnail mb-2" src="<?php echo e($logoUrl); ?>" alt="Current workspace logo" width="160" height="80">
                    <?php endif; ?>
                    <input class="<?php echo e(inputClass('logo')); ?>" id="branding-logo" name="logo" type="file"
                        accept=".png,.jpg,.jpeg,.webp,.gif,image/png,image/jpeg,image/webp,image/gif"
                        <?php echo e(inputAttrs('logo', 'branding-logo', 'branding-logo-help')); ?>>
                    <div class="form-text" id="branding-logo-help">
                        PNG, JPG, WebP, or GIF up to 2 MB. Leave empty to keep the current logo.
                    </div>
                    <?php $__errorArgs = ['logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback d-block" id="branding-logo-error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="branding-favicon">Favicon</label>
                    <?php if($faviconUrl): ?>
                        <img class="app-brand-preview app-favicon-preview img-thumbnail mb-2" src="<?php echo e($faviconUrl); ?>" alt="Current workspace favicon" width="48" height="48">
                    <?php endif; ?>
                    <input class="<?php echo e(inputClass('favicon')); ?>" id="branding-favicon" name="favicon" type="file"
                        accept=".png,.ico,image/png,image/x-icon,image/vnd.microsoft.icon"
                        <?php echo e(inputAttrs('favicon', 'branding-favicon', 'branding-favicon-help')); ?>>
                    <div class="form-text" id="branding-favicon-help">
                        PNG or ICO up to 512 KB. Leave empty to keep the current browser icon.
                    </div>
                    <?php $__errorArgs = ['favicon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback d-block" id="branding-favicon-error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        <?php elseif($setting->group === 'billing'): ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="billing-currency">Currency code <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="<?php echo e(inputClass('data.currency')); ?>" id="billing-currency" name="data[currency]" type="text" value="<?php echo e(getValue('data.currency', null, $values['data']['currency'])); ?>" minlength="3" maxlength="3" placeholder="BDT" required <?php echo e(inputAttrs('data.currency', 'billing-currency')); ?>>
                    <?php $__errorArgs = ['data.currency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="billing-currency-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="billing-prefix">Invoice prefix <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="<?php echo e(inputClass('data.invoice_prefix')); ?>" id="billing-prefix" name="data[invoice_prefix]" type="text" value="<?php echo e(getValue('data.invoice_prefix', null, $values['data']['invoice_prefix'])); ?>" maxlength="20" required <?php echo e(inputAttrs('data.invoice_prefix', 'billing-prefix')); ?>>
                    <?php $__errorArgs = ['data.invoice_prefix'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="billing-prefix-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-12">
                    <label class="form-label" for="billing-instructions">Payment instructions</label>
                    <textarea class="<?php echo e(inputClass('data.payment_instructions')); ?>" id="billing-instructions" name="data[payment_instructions]" rows="4" maxlength="4000" <?php echo e(inputAttrs('data.payment_instructions', 'billing-instructions')); ?>><?php echo e(getValue('data.payment_instructions', null, $values['data']['payment_instructions'])); ?></textarea>
                    <?php $__errorArgs = ['data.payment_instructions'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="billing-instructions-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        <?php elseif($setting->group === 'general'): ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="general-timezone">Timezone <span class="text-danger" aria-hidden="true">*</span></label>
                    <select class="<?php echo e(inputClass('data.timezone', 'form-select')); ?>" id="general-timezone" name="data[timezone]" required <?php echo e(inputAttrs('data.timezone', 'general-timezone')); ?>>
                        <?php $__currentLoopData = $timezones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $timezone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($timezone); ?>" <?php echo e(getSelected($timezone, 'data.timezone', null, $values['data']['timezone'])); ?>><?php echo e($timezone); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['data.timezone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="general-timezone-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="general-locale">Locale <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="<?php echo e(inputClass('data.locale')); ?>" id="general-locale" name="data[locale]" type="text" value="<?php echo e(getValue('data.locale', null, $values['data']['locale'])); ?>" maxlength="12" placeholder="en" required <?php echo e(inputAttrs('data.locale', 'general-locale', 'locale-help')); ?>>
                    <?php $__errorArgs = ['data.locale'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="general-locale-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <div class="form-text" id="locale-help">Language code, such as en or bn.</div>
                </div>
            </div>
            <?php if($orderableModules !== []): ?>
                <section class="border-top mt-4 pt-3" aria-labelledby="module-order-heading">
                    <h3 class="h6 mb-1" id="module-order-heading">Module names &amp; order</h3>
                    <p class="small text-body-secondary mb-3">Leave the name blank to use its default. Smaller ordering numbers appear first for everyone in this workspace.</p>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead><tr><th scope="col">Default name</th><th scope="col">Display name</th><th scope="col">Ordering</th></tr></thead>
                            <tbody>
                                <?php $__currentLoopData = $orderableModules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $dashboard = $module->get('tenant_dashboard');
                                        $moduleKey = $module->getLowerName();
                                        $orderField = 'data.module_order.'.$moduleKey;
                                        $orderId = 'module-order-'.$moduleKey;
                                        $nameField = 'data.module_names.'.$moduleKey;
                                        $nameId = 'module-name-'.$moduleKey;
                                    ?>
                                    <?php if($dashboard === null) continue; ?>
                                    <tr>
                                        <th scope="row"><i class="fa-solid fa-<?php echo e($dashboard['icon']); ?> text-primary me-2" aria-hidden="true"></i><?php echo e($dashboard['label']); ?></th>
                                        <td>
                                            <label class="visually-hidden" for="<?php echo e($nameId); ?>">Display name for <?php echo e($dashboard['label']); ?></label>
                                            <input class="<?php echo e(inputClass($nameField, 'form-control form-control-sm')); ?>"
                                                id="<?php echo e($nameId); ?>" name="data[module_names][<?php echo e($moduleKey); ?>]" type="text" maxlength="100"
                                                value="<?php echo e(getValue($nameField, null, $values['data']['module_names'][$moduleKey] ?? '')); ?>"
                                                placeholder="<?php echo e($dashboard['label']); ?>" <?php echo e(inputAttrs($nameField, $nameId)); ?>>
                                            <?php $__errorArgs = [$nameField];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="<?php echo e($nameId); ?>-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </td>
                                        <td>
                                            <label class="visually-hidden" for="<?php echo e($orderId); ?>">Ordering for <?php echo e($dashboard['label']); ?></label>
                                            <div class="col-12 col-sm-6 col-lg-3">
                                                <input class="<?php echo e(inputClass($orderField, 'form-control form-control-sm')); ?>"
                                                    id="<?php echo e($orderId); ?>" name="data[module_order][<?php echo e($moduleKey); ?>]" type="number" min="0" max="65535" step="1"
                                                    value="<?php echo e(getValue($orderField, null, $values['data']['module_order'][$moduleKey] ?? null)); ?>"
                                                    placeholder="Default" <?php echo e(inputAttrs($orderField, $orderId)); ?>>
                                            </div>
                                            <?php $__errorArgs = [$orderField];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="<?php echo e($orderId); ?>-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php $__errorArgs = ['data.module_order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block mt-2"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <?php $__errorArgs = ['data.module_names'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block mt-2"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </section>
            <?php endif; ?>
        <?php elseif($setting->group === 'email'): ?>
            <div class="form-check mb-3">
                <input name="data[enabled]" type="hidden" value="0">
                <input class="<?php echo e(inputClass('data.enabled', 'form-check-input')); ?>" id="email-enabled" name="data[enabled]" type="checkbox" value="1" <?php echo e(getChecked(1, 'data.enabled', null, $values['data']['enabled'] ?? false)); ?> <?php echo e(inputAttrs('data.enabled', 'email-enabled', 'email-enabled-help')); ?>>
                <label class="form-check-label" for="email-enabled">Use these email settings</label>
                <?php $__errorArgs = ['data.enabled'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="email-enabled-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <p class="app-form-section-note" id="email-enabled-help">When disabled, emails use the deployment mailer.</p>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="email-host">SMTP host</label>
                    <input class="<?php echo e(inputClass('data.host')); ?>" id="email-host" name="data[host]" type="text" value="<?php echo e(getValue('data.host', null, $values['data']['host'] ?? '')); ?>" maxlength="255" autocomplete="off" <?php echo e(inputAttrs('data.host', 'email-host')); ?>>
                    <?php $__errorArgs = ['data.host'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="email-host-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="email-port">Port</label>
                    <input class="<?php echo e(inputClass('data.port')); ?>" id="email-port" name="data[port]" type="text" inputmode="numeric" pattern="[0-9]{1,5}" value="<?php echo e(getValue('data.port', null, $values['data']['port'] ?? 587)); ?>" maxlength="5" <?php echo e(inputAttrs('data.port', 'email-port')); ?>>
                    <?php $__errorArgs = ['data.port'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="email-port-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="email-encryption">Encryption</label>
                    <select class="<?php echo e(inputClass('data.encryption', 'form-select')); ?>" id="email-encryption" name="data[encryption]" <?php echo e(inputAttrs('data.encryption', 'email-encryption')); ?>>
                        <option value="tls" <?php echo e(getSelected('tls', 'data.encryption', null, $values['data']['encryption'] ?? 'tls')); ?>>STARTTLS</option>
                        <option value="ssl" <?php echo e(getSelected('ssl', 'data.encryption', null, $values['data']['encryption'] ?? 'tls')); ?>>SSL/TLS</option>
                        <option value="none" <?php echo e(getSelected('none', 'data.encryption', null, $values['data']['encryption'] ?? 'tls')); ?>>None</option>
                    </select>
                    <?php $__errorArgs = ['data.encryption'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="email-encryption-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="email-from-address">From email address</label>
                    <input class="<?php echo e(inputClass('data.from_address')); ?>" id="email-from-address" name="data[from_address]" type="email" value="<?php echo e(getValue('data.from_address', null, $values['data']['from_address'] ?? '')); ?>" maxlength="255" autocomplete="email" <?php echo e(inputAttrs('data.from_address', 'email-from-address')); ?>>
                    <?php $__errorArgs = ['data.from_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="email-from-address-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="email-from-name">From name</label>
                    <input class="<?php echo e(inputClass('data.from_name')); ?>" id="email-from-name" name="data[from_name]" type="text" value="<?php echo e(getValue('data.from_name', null, $values['data']['from_name'] ?? '')); ?>" maxlength="255" autocomplete="organization" <?php echo e(inputAttrs('data.from_name', 'email-from-name')); ?>>
                    <?php $__errorArgs = ['data.from_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="email-from-name-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
            <?php $__errorArgs = ['credentials'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block mt-3"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <div class="row g-3 mt-0">
                <?php echo $__env->make('core::settings.credentials', ['credentialFields' => $credentialFields], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        <?php elseif($setting->group === 'sms'): ?>
            <?php
                $smsBodyFormat = ($configuredCredentials['parameters'] ?? false) ? $values['data']['body_format'] : 'form';
                $showSmsAdvanced = $errors->hasAny([
                    'data.provider', 'data.method', 'data.body_format',
                    'data.success_field', 'data.success_value', 'data.failure_values',
                    'credentials.username', 'credentials.password', 'credentials.headers', 'credentials.parameters',
                ]);
            ?>
            <div class="form-check mb-3">
                <input name="data[enabled]" type="hidden" value="0">
                <input class="<?php echo e(inputClass('data.enabled', 'form-check-input')); ?>" id="sms-enabled"
                    name="data[enabled]" type="checkbox" value="1"
                    <?php echo e(getChecked(1, 'data.enabled', null, $values['data']['enabled'] ?? false)); ?>

                    <?php echo e(inputAttrs('data.enabled', 'sms-enabled')); ?>>
                <label class="form-check-label" for="sms-enabled">Enable SMS</label>
                <?php $__errorArgs = ['data.enabled'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="sms-enabled-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <?php $__errorArgs = ['credentials'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block mb-3"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label" for="sms-api-url">API URL</label>
                    <input class="<?php echo e(inputClass('data.api_url')); ?>" id="sms-api-url" name="data[api_url]" type="url"
                        value="<?php echo e(getValue('data.api_url', null, $values['data']['api_url'] ?? '')); ?>"
                        maxlength="2048" autocomplete="off" <?php echo e(inputAttrs('data.api_url', 'sms-api-url')); ?>>
                    <?php $__errorArgs = ['data.api_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="sms-api-url-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="sms-sender-id">Sender ID</label>
                    <input class="<?php echo e(inputClass('data.sender_id')); ?>" id="sms-sender-id" name="data[sender_id]" type="text"
                        value="<?php echo e(getValue('data.sender_id', null, $values['data']['sender_id'] ?? '')); ?>"
                        maxlength="100" <?php echo e(inputAttrs('data.sender_id', 'sms-sender-id')); ?>>
                    <?php $__errorArgs = ['data.sender_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="sms-sender-id-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <?php echo $__env->make('core::settings.credentials', ['credentialFields' => ['api_key' => $credentialFields['api_key']]], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>

            <details class="border rounded p-3 mt-3" <?php if($showSmsAdvanced): ?> open <?php endif; ?>>
                <summary class="small fw-semibold">Advanced</summary>
                <div class="row g-3 mt-0">
                    <div class="col-md-6">
                        <label class="form-label" for="sms-provider">Provider name</label>
                        <input class="<?php echo e(inputClass('data.provider')); ?>" id="sms-provider" name="data[provider]" type="text"
                            value="<?php echo e(getValue('data.provider', null, $values['data']['provider'] ?? '')); ?>"
                            maxlength="100" <?php echo e(inputAttrs('data.provider', 'sms-provider')); ?>>
                        <?php $__errorArgs = ['data.provider'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="sms-provider-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="sms-method">Method</label>
                        <select class="<?php echo e(inputClass('data.method', 'form-select')); ?>" id="sms-method"
                            name="data[method]" <?php echo e(inputAttrs('data.method', 'sms-method')); ?>>
                            <option value="POST" <?php echo e(getSelected('POST', 'data.method', null, $values['data']['method'] ?? 'POST')); ?>>POST</option>
                            <option value="GET" <?php echo e(getSelected('GET', 'data.method', null, $values['data']['method'] ?? 'POST')); ?>>GET</option>
                        </select>
                        <?php $__errorArgs = ['data.method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="sms-method-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="sms-body-format">Format</label>
                        <select class="<?php echo e(inputClass('data.body_format', 'form-select')); ?>" id="sms-body-format"
                            name="data[body_format]" <?php echo e(inputAttrs('data.body_format', 'sms-body-format')); ?>>
                            <option value="json" <?php echo e(getSelected('json', 'data.body_format', null, $smsBodyFormat)); ?>>JSON</option>
                            <option value="form" <?php echo e(getSelected('form', 'data.body_format', null, $smsBodyFormat)); ?>>Form encoded</option>
                        </select>
                        <?php $__errorArgs = ['data.body_format'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="sms-body-format-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="sms-success-field">Success response field</label>
                        <input class="<?php echo e(inputClass('data.success_field')); ?>" id="sms-success-field" name="data[success_field]" type="text"
                            value="<?php echo e(getValue('data.success_field', null, $values['data']['success_field'] ?? '')); ?>"
                            maxlength="100" placeholder="response_code" <?php echo e(inputAttrs('data.success_field', 'sms-success-field', 'sms-success-help')); ?>>
                        <?php $__errorArgs = ['data.success_field'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="sms-success-field-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="sms-success-value">Success value</label>
                        <input class="<?php echo e(inputClass('data.success_value')); ?>" id="sms-success-value" name="data[success_value]" type="text"
                            value="<?php echo e(getValue('data.success_value', null, $values['data']['success_value'] ?? '')); ?>"
                            maxlength="100" placeholder="202" <?php echo e(inputAttrs('data.success_value', 'sms-success-value', 'sms-success-help')); ?>>
                        <?php $__errorArgs = ['data.success_value'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="sms-success-value-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-12">
                        <p class="form-text mb-0" id="sms-success-help">Leave the response field blank to use HTTP success only. Otherwise match the field and value in your provider's JSON response.</p>
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="sms-failure-values">Confirmed rejection values</label>
                        <input class="<?php echo e(inputClass('data.failure_values')); ?>" id="sms-failure-values" name="data[failure_values]" type="text"
                            value="<?php echo e(getValue('data.failure_values', null, $values['data']['failure_values'] ?? '')); ?>"
                            maxlength="1000" placeholder="false" <?php echo e(inputAttrs('data.failure_values', 'sms-failure-values', 'sms-failure-help')); ?>>
                        <div class="form-text" id="sms-failure-help">Comma-separated response values that confirm rejection in your provider's documentation. Leave blank when unsure; other unmatched responses remain uncertain.</div>
                        <?php $__errorArgs = ['data.failure_values'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="sms-failure-values-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <?php echo $__env->make('core::settings.credentials', ['credentialFields' => array_diff_key($credentialFields, ['api_key' => true])], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
                <p class="form-text mb-0 mt-3">Match your provider's field names; keep <code>{to}</code> and <code>{message}</code>.</p>
            </details>
        <?php elseif($setting->group === 'manual'): ?>
            <div>
                <label class="form-label" for="manual-instructions">Payment instructions</label>
                <textarea
                    class="<?php echo e(inputClass('data.instructions')); ?>"
                    id="manual-instructions"
                    name="data[instructions]"
                    rows="4"
                    maxlength="4000"
                    <?php echo e(inputAttrs('data.instructions', 'manual-instructions', 'manual-instructions-help')); ?>

                ><?php echo e(getValue('data.instructions', null, $values['data']['instructions'])); ?></textarea>
                <div class="form-text" id="manual-instructions-help">
                    Tell customers where to send payment and which reference to submit for review.
                </div>
                <?php $__errorArgs = ['data.instructions'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback d-block" id="manual-instructions-error"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\resources\views\settings\form.blade.php ENDPATH**/ ?>