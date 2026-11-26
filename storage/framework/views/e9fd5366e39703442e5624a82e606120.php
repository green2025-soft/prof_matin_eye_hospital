<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $generalPaper = getValue('data.general_paper', null, $values['general_paper']);
        $glassPaper = getValue('data.glass_paper', null, $values['glass_paper']);
    ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <form class="app-card p-3 p-md-4" id="prescription-settings-form" action="<?php echo e(route('prescription.settings.update', $setting)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <fieldset <?php if(! $canUpdate): echo 'disabled'; endif; ?>>
            <legend class="visually-hidden">Prescription form and print settings</legend>
            <div class="row g-4">
                <section class="col-lg-6" aria-labelledby="settings-general-heading">
                    <h2 class="h6 mb-3" id="settings-general-heading">General Prescription</h2>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <input name="data[general_enabled]" type="hidden" value="0">
                            <div class="form-check form-switch">
                                <input class="<?php echo e(inputClass('data.general_enabled', 'form-check-input')); ?>" id="general_enabled" name="data[general_enabled]" type="checkbox" value="1" <?php echo e(getChecked(1, 'data.general_enabled', null, $values['general_enabled'])); ?> <?php echo e(inputAttrs('data.general_enabled', 'general_enabled')); ?>>
                                <label class="form-check-label" for="general_enabled">Enable General Prescription</label>
                            </div>
                            <?php $__errorArgs = ['data.general_enabled'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="general_enabled-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-sm-6">
                            <input name="data[general_chief_complaints_enabled]" type="hidden" value="0">
                            <div class="form-check form-switch">
                                <input class="<?php echo e(inputClass('data.general_chief_complaints_enabled', 'form-check-input')); ?>" id="general_chief_complaints_enabled" name="data[general_chief_complaints_enabled]" type="checkbox" value="1" <?php echo e(getChecked(1, 'data.general_chief_complaints_enabled', null, $values['general_chief_complaints_enabled'])); ?> <?php echo e(inputAttrs('data.general_chief_complaints_enabled', 'general_chief_complaints_enabled')); ?>>
                                <label class="form-check-label" for="general_chief_complaints_enabled">Chief Complaint</label>
                            </div>
                            <?php $__errorArgs = ['data.general_chief_complaints_enabled'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="general_chief_complaints_enabled-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-sm-6">
                            <input name="data[general_history_enabled]" type="hidden" value="0">
                            <div class="form-check form-switch">
                                <input class="<?php echo e(inputClass('data.general_history_enabled', 'form-check-input')); ?>" id="general_history_enabled" name="data[general_history_enabled]" type="checkbox" value="1" <?php echo e(getChecked(1, 'data.general_history_enabled', null, $values['general_history_enabled'])); ?> <?php echo e(inputAttrs('data.general_history_enabled', 'general_history_enabled')); ?>>
                                <label class="form-check-label" for="general_history_enabled">History</label>
                            </div>
                            <?php $__errorArgs = ['data.general_history_enabled'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="general_history_enabled-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-sm-6">
                            <input name="data[general_examination_enabled]" type="hidden" value="0">
                            <div class="form-check form-switch">
                                <input class="<?php echo e(inputClass('data.general_examination_enabled', 'form-check-input')); ?>" id="general_examination_enabled" name="data[general_examination_enabled]" type="checkbox" value="1" <?php echo e(getChecked(1, 'data.general_examination_enabled', null, $values['general_examination_enabled'])); ?> <?php echo e(inputAttrs('data.general_examination_enabled', 'general_examination_enabled')); ?>>
                                <label class="form-check-label" for="general_examination_enabled">Examination</label>
                            </div>
                            <?php $__errorArgs = ['data.general_examination_enabled'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="general_examination_enabled-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-sm-6">
                            <input name="data[general_diagnosis_enabled]" type="hidden" value="0">
                            <div class="form-check form-switch">
                                <input class="<?php echo e(inputClass('data.general_diagnosis_enabled', 'form-check-input')); ?>" id="general_diagnosis_enabled" name="data[general_diagnosis_enabled]" type="checkbox" value="1" <?php echo e(getChecked(1, 'data.general_diagnosis_enabled', null, $values['general_diagnosis_enabled'])); ?> <?php echo e(inputAttrs('data.general_diagnosis_enabled', 'general_diagnosis_enabled')); ?>>
                                <label class="form-check-label" for="general_diagnosis_enabled">Diagnosis</label>
                            </div>
                            <?php $__errorArgs = ['data.general_diagnosis_enabled'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="general_diagnosis_enabled-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-sm-6">
                            <input name="data[general_investigations_enabled]" type="hidden" value="0">
                            <div class="form-check form-switch">
                                <input class="<?php echo e(inputClass('data.general_investigations_enabled', 'form-check-input')); ?>" id="general_investigations_enabled" name="data[general_investigations_enabled]" type="checkbox" value="1" <?php echo e(getChecked(1, 'data.general_investigations_enabled', null, $values['general_investigations_enabled'])); ?> <?php echo e(inputAttrs('data.general_investigations_enabled', 'general_investigations_enabled')); ?>>
                                <label class="form-check-label" for="general_investigations_enabled">Investigation</label>
                            </div>
                            <?php $__errorArgs = ['data.general_investigations_enabled'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="general_investigations_enabled-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-sm-6">
                            <input name="data[general_advice_enabled]" type="hidden" value="0">
                            <div class="form-check form-switch">
                                <input class="<?php echo e(inputClass('data.general_advice_enabled', 'form-check-input')); ?>" id="general_advice_enabled" name="data[general_advice_enabled]" type="checkbox" value="1" <?php echo e(getChecked(1, 'data.general_advice_enabled', null, $values['general_advice_enabled'])); ?> <?php echo e(inputAttrs('data.general_advice_enabled', 'general_advice_enabled')); ?>>
                                <label class="form-check-label" for="general_advice_enabled">Advice</label>
                            </div>
                            <?php $__errorArgs = ['data.general_advice_enabled'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="general_advice_enabled-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-sm-6">
                            <input name="data[general_follow_up_enabled]" type="hidden" value="0">
                            <div class="form-check form-switch">
                                <input class="<?php echo e(inputClass('data.general_follow_up_enabled', 'form-check-input')); ?>" id="general_follow_up_enabled" name="data[general_follow_up_enabled]" type="checkbox" value="1" <?php echo e(getChecked(1, 'data.general_follow_up_enabled', null, $values['general_follow_up_enabled'])); ?> <?php echo e(inputAttrs('data.general_follow_up_enabled', 'general_follow_up_enabled')); ?>>
                                <label class="form-check-label" for="general_follow_up_enabled">Follow-Up Date</label>
                            </div>
                            <?php $__errorArgs = ['data.general_follow_up_enabled'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="general_follow_up_enabled-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-sm-6">
                            <input name="data[general_header_enabled]" type="hidden" value="0">
                            <div class="form-check form-switch">
                                <input class="<?php echo e(inputClass('data.general_header_enabled', 'form-check-input')); ?>" id="general_header_enabled" name="data[general_header_enabled]" type="checkbox" value="1" <?php echo e(getChecked(1, 'data.general_header_enabled', null, $values['general_header_enabled'])); ?> <?php echo e(inputAttrs('data.general_header_enabled', 'general_header_enabled')); ?>>
                                <label class="form-check-label" for="general_header_enabled">Print Hospital Header</label>
                            </div>
                            <?php $__errorArgs = ['data.general_header_enabled'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="general_header_enabled-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-12 prescription-paper-settings">
                            <div class="row g-2">
                                <div class="col-sm-4">
                                    <label class="form-label" for="general_paper">Print Paper</label>
                                    <select class="<?php echo e(inputClass('data.general_paper', 'form-select')); ?> prescription-paper-size"
                                        id="general_paper" name="data[general_paper]" required <?php echo e(inputAttrs('data.general_paper', 'general_paper')); ?>>
                                        <option value="a4" <?php if($generalPaper === 'a4'): echo 'selected'; endif; ?>>A4</option>
                                        <option value="a5" <?php if($generalPaper === 'a5'): echo 'selected'; endif; ?>>A5</option>
                                        <option value="custom" <?php if($generalPaper === 'custom'): echo 'selected'; endif; ?>>Custom</option>
                                    </select>
                                    <?php $__errorArgs = ['data.general_paper'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="general_paper-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="col-sm-8 prescription-custom-paper" <?php if($generalPaper !== 'custom'): ?> hidden <?php endif; ?>>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <label class="form-label" for="general_custom_width">Width (mm)</label>
                                            <input class="<?php echo e(inputClass('data.general_custom_width')); ?>" id="general_custom_width"
                                                name="data[general_custom_width]" type="number" min="50" max="1000" step="0.01" inputmode="decimal"
                                                value="<?php echo e(getValue('data.general_custom_width', null, $values['general_custom_width'])); ?>"
                                                <?php if($generalPaper === 'custom'): echo 'required'; endif; ?> <?php echo e(inputAttrs('data.general_custom_width', 'general_custom_width')); ?>>
                                            <?php $__errorArgs = ['data.general_custom_width'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="general_custom_width-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label" for="general_custom_height">Height (mm)</label>
                                            <input class="<?php echo e(inputClass('data.general_custom_height')); ?>" id="general_custom_height"
                                                name="data[general_custom_height]" type="number" min="50" max="1000" step="0.01" inputmode="decimal"
                                                value="<?php echo e(getValue('data.general_custom_height', null, $values['general_custom_height'])); ?>"
                                                <?php if($generalPaper === 'custom'): echo 'required'; endif; ?> <?php echo e(inputAttrs('data.general_custom_height', 'general_custom_height')); ?>>
                                            <?php $__errorArgs = ['data.general_custom_height'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="general_custom_height-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="general_rx_pattern">Rx Number Pattern</label>
                            <input class="<?php echo e(inputClass('data.general_rx_pattern')); ?>" id="general_rx_pattern"
                                name="data[general_rx_pattern]" type="text" maxlength="40" required
                                value="<?php echo e(getValue('data.general_rx_pattern', null, $values['general_rx_pattern'])); ?>"
                                <?php echo e(inputAttrs('data.general_rx_pattern', 'general_rx_pattern')); ?>>
                            <?php $__errorArgs = ['data.general_rx_pattern'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="general_rx_pattern-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <p class="form-text mb-0">Tokens: {SEQ:6}, {YYYY}, {YY}, {MM}, {DD}, {BRANCH}. SEQ uses the record ID.</p>
                        </div>
                        <div class="col-12">
                            <p class="form-label mb-2">Print Margin (mm)</p>
                            <div class="row g-2">
                                <div class="col-3">
                                    <label class="form-label small" for="general_margin_top">Top</label>
                                    <input class="<?php echo e(inputClass('data.general_margin_top')); ?>" id="general_margin_top"
                                        name="data[general_margin_top]" type="number" min="0" max="50" step="0.01" placeholder="Auto"
                                        value="<?php echo e(getValue('data.general_margin_top', null, $values['general_margin_top'])); ?>"
                                        <?php echo e(inputAttrs('data.general_margin_top', 'general_margin_top')); ?>>
                                    <?php $__errorArgs = ['data.general_margin_top'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="general_margin_top-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="col-3">
                                    <label class="form-label small" for="general_margin_right">Right</label>
                                    <input class="<?php echo e(inputClass('data.general_margin_right')); ?>" id="general_margin_right"
                                        name="data[general_margin_right]" type="number" min="0" max="50" step="0.01" placeholder="Auto"
                                        value="<?php echo e(getValue('data.general_margin_right', null, $values['general_margin_right'])); ?>"
                                        <?php echo e(inputAttrs('data.general_margin_right', 'general_margin_right')); ?>>
                                    <?php $__errorArgs = ['data.general_margin_right'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="general_margin_right-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="col-3">
                                    <label class="form-label small" for="general_margin_bottom">Bottom</label>
                                    <input class="<?php echo e(inputClass('data.general_margin_bottom')); ?>" id="general_margin_bottom"
                                        name="data[general_margin_bottom]" type="number" min="0" max="50" step="0.01" placeholder="Auto"
                                        value="<?php echo e(getValue('data.general_margin_bottom', null, $values['general_margin_bottom'])); ?>"
                                        <?php echo e(inputAttrs('data.general_margin_bottom', 'general_margin_bottom')); ?>>
                                    <?php $__errorArgs = ['data.general_margin_bottom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="general_margin_bottom-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="col-3">
                                    <label class="form-label small" for="general_margin_left">Left</label>
                                    <input class="<?php echo e(inputClass('data.general_margin_left')); ?>" id="general_margin_left"
                                        name="data[general_margin_left]" type="number" min="0" max="50" step="0.01" placeholder="Auto"
                                        value="<?php echo e(getValue('data.general_margin_left', null, $values['general_margin_left'])); ?>"
                                        <?php echo e(inputAttrs('data.general_margin_left', 'general_margin_left')); ?>>
                                    <?php $__errorArgs = ['data.general_margin_left'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="general_margin_left-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <p class="form-text mb-0">Blank uses paper default: A4 / Custom 10 mm, A5 8 mm.</p>
                        </div>
                    </div>
                </section>
                <section class="col-lg-6" aria-labelledby="settings-glass-heading">
                    <h2 class="h6 mb-3" id="settings-glass-heading">Glass Prescription</h2>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <input name="data[glass_enabled]" type="hidden" value="0">
                            <div class="form-check form-switch">
                                <input class="<?php echo e(inputClass('data.glass_enabled', 'form-check-input')); ?>" id="glass_enabled" name="data[glass_enabled]" type="checkbox" value="1" <?php echo e(getChecked(1, 'data.glass_enabled', null, $values['glass_enabled'])); ?> <?php echo e(inputAttrs('data.glass_enabled', 'glass_enabled')); ?>>
                                <label class="form-check-label" for="glass_enabled">Enable Glass Prescription</label>
                            </div>
                            <?php $__errorArgs = ['data.glass_enabled'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="glass_enabled-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-sm-6">
                            <input name="data[glass_pd_enabled]" type="hidden" value="0">
                            <div class="form-check form-switch">
                                <input class="<?php echo e(inputClass('data.glass_pd_enabled', 'form-check-input')); ?>" id="glass_pd_enabled" name="data[glass_pd_enabled]" type="checkbox" value="1" <?php echo e(getChecked(1, 'data.glass_pd_enabled', null, $values['glass_pd_enabled'])); ?> <?php echo e(inputAttrs('data.glass_pd_enabled', 'glass_pd_enabled')); ?>>
                                <label class="form-check-label" for="glass_pd_enabled">PD</label>
                            </div>
                            <?php $__errorArgs = ['data.glass_pd_enabled'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="glass_pd_enabled-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-sm-6">
                            <input name="data[glass_disclaimer_enabled]" type="hidden" value="0">
                            <div class="form-check form-switch">
                                <input class="<?php echo e(inputClass('data.glass_disclaimer_enabled', 'form-check-input')); ?>" id="glass_disclaimer_enabled" name="data[glass_disclaimer_enabled]" type="checkbox" value="1" <?php echo e(getChecked(1, 'data.glass_disclaimer_enabled', null, $values['glass_disclaimer_enabled'])); ?> <?php echo e(inputAttrs('data.glass_disclaimer_enabled', 'glass_disclaimer_enabled')); ?>>
                                <label class="form-check-label" for="glass_disclaimer_enabled">Disclaimer</label>
                            </div>
                            <?php $__errorArgs = ['data.glass_disclaimer_enabled'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="glass_disclaimer_enabled-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-sm-6">
                            <input name="data[glass_lens_options_enabled]" type="hidden" value="0">
                            <div class="form-check form-switch">
                                <input class="<?php echo e(inputClass('data.glass_lens_options_enabled', 'form-check-input')); ?>" id="glass_lens_options_enabled" name="data[glass_lens_options_enabled]" type="checkbox" value="1" <?php echo e(getChecked(1, 'data.glass_lens_options_enabled', null, $values['glass_lens_options_enabled'])); ?> <?php echo e(inputAttrs('data.glass_lens_options_enabled', 'glass_lens_options_enabled')); ?>>
                                <label class="form-check-label" for="glass_lens_options_enabled">Lens / Usage Option</label>
                            </div>
                            <?php $__errorArgs = ['data.glass_lens_options_enabled'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="glass_lens_options_enabled-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-sm-6">
                            <input name="data[glass_header_enabled]" type="hidden" value="0">
                            <div class="form-check form-switch">
                                <input class="<?php echo e(inputClass('data.glass_header_enabled', 'form-check-input')); ?>" id="glass_header_enabled" name="data[glass_header_enabled]" type="checkbox" value="1" <?php echo e(getChecked(1, 'data.glass_header_enabled', null, $values['glass_header_enabled'])); ?> <?php echo e(inputAttrs('data.glass_header_enabled', 'glass_header_enabled')); ?>>
                                <label class="form-check-label" for="glass_header_enabled">Print Hospital Header</label>
                            </div>
                            <?php $__errorArgs = ['data.glass_header_enabled'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="glass_header_enabled-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-12 prescription-paper-settings">
                            <div class="row g-2">
                                <div class="col-sm-4">
                                    <label class="form-label" for="glass_paper">Print Paper</label>
                                    <select class="<?php echo e(inputClass('data.glass_paper', 'form-select')); ?> prescription-paper-size"
                                        id="glass_paper" name="data[glass_paper]" required <?php echo e(inputAttrs('data.glass_paper', 'glass_paper')); ?>>
                                        <option value="a4" <?php if($glassPaper === 'a4'): echo 'selected'; endif; ?>>A4</option>
                                        <option value="a5" <?php if($glassPaper === 'a5'): echo 'selected'; endif; ?>>A5</option>
                                        <option value="custom" <?php if($glassPaper === 'custom'): echo 'selected'; endif; ?>>Custom</option>
                                    </select>
                                    <?php $__errorArgs = ['data.glass_paper'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="glass_paper-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="col-sm-8 prescription-custom-paper" <?php if($glassPaper !== 'custom'): ?> hidden <?php endif; ?>>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <label class="form-label" for="glass_custom_width">Width (mm)</label>
                                            <input class="<?php echo e(inputClass('data.glass_custom_width')); ?>" id="glass_custom_width"
                                                name="data[glass_custom_width]" type="number" min="50" max="1000" step="0.01" inputmode="decimal"
                                                value="<?php echo e(getValue('data.glass_custom_width', null, $values['glass_custom_width'])); ?>"
                                                <?php if($glassPaper === 'custom'): echo 'required'; endif; ?> <?php echo e(inputAttrs('data.glass_custom_width', 'glass_custom_width')); ?>>
                                            <?php $__errorArgs = ['data.glass_custom_width'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="glass_custom_width-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label" for="glass_custom_height">Height (mm)</label>
                                            <input class="<?php echo e(inputClass('data.glass_custom_height')); ?>" id="glass_custom_height"
                                                name="data[glass_custom_height]" type="number" min="50" max="1000" step="0.01" inputmode="decimal"
                                                value="<?php echo e(getValue('data.glass_custom_height', null, $values['glass_custom_height'])); ?>"
                                                <?php if($glassPaper === 'custom'): echo 'required'; endif; ?> <?php echo e(inputAttrs('data.glass_custom_height', 'glass_custom_height')); ?>>
                                            <?php $__errorArgs = ['data.glass_custom_height'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="glass_custom_height-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="glass_rx_pattern">Rx Number Pattern</label>
                            <input class="<?php echo e(inputClass('data.glass_rx_pattern')); ?>" id="glass_rx_pattern"
                                name="data[glass_rx_pattern]" type="text" maxlength="40" required
                                value="<?php echo e(getValue('data.glass_rx_pattern', null, $values['glass_rx_pattern'])); ?>"
                                <?php echo e(inputAttrs('data.glass_rx_pattern', 'glass_rx_pattern')); ?>>
                            <?php $__errorArgs = ['data.glass_rx_pattern'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="glass_rx_pattern-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <p class="form-text mb-0">Tokens: {SEQ:6}, {YYYY}, {YY}, {MM}, {DD}, {BRANCH}. SEQ uses the record ID.</p>
                        </div>
                        <div class="col-12">
                            <p class="form-label mb-2">Print Margin (mm)</p>
                            <div class="row g-2">
                                <div class="col-3">
                                    <label class="form-label small" for="glass_margin_top">Top</label>
                                    <input class="<?php echo e(inputClass('data.glass_margin_top')); ?>" id="glass_margin_top"
                                        name="data[glass_margin_top]" type="number" min="0" max="50" step="0.01" placeholder="Auto"
                                        value="<?php echo e(getValue('data.glass_margin_top', null, $values['glass_margin_top'])); ?>"
                                        <?php echo e(inputAttrs('data.glass_margin_top', 'glass_margin_top')); ?>>
                                    <?php $__errorArgs = ['data.glass_margin_top'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="glass_margin_top-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="col-3">
                                    <label class="form-label small" for="glass_margin_right">Right</label>
                                    <input class="<?php echo e(inputClass('data.glass_margin_right')); ?>" id="glass_margin_right"
                                        name="data[glass_margin_right]" type="number" min="0" max="50" step="0.01" placeholder="Auto"
                                        value="<?php echo e(getValue('data.glass_margin_right', null, $values['glass_margin_right'])); ?>"
                                        <?php echo e(inputAttrs('data.glass_margin_right', 'glass_margin_right')); ?>>
                                    <?php $__errorArgs = ['data.glass_margin_right'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="glass_margin_right-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="col-3">
                                    <label class="form-label small" for="glass_margin_bottom">Bottom</label>
                                    <input class="<?php echo e(inputClass('data.glass_margin_bottom')); ?>" id="glass_margin_bottom"
                                        name="data[glass_margin_bottom]" type="number" min="0" max="50" step="0.01" placeholder="Auto"
                                        value="<?php echo e(getValue('data.glass_margin_bottom', null, $values['glass_margin_bottom'])); ?>"
                                        <?php echo e(inputAttrs('data.glass_margin_bottom', 'glass_margin_bottom')); ?>>
                                    <?php $__errorArgs = ['data.glass_margin_bottom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="glass_margin_bottom-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="col-3">
                                    <label class="form-label small" for="glass_margin_left">Left</label>
                                    <input class="<?php echo e(inputClass('data.glass_margin_left')); ?>" id="glass_margin_left"
                                        name="data[glass_margin_left]" type="number" min="0" max="50" step="0.01" placeholder="Auto"
                                        value="<?php echo e(getValue('data.glass_margin_left', null, $values['glass_margin_left'])); ?>"
                                        <?php echo e(inputAttrs('data.glass_margin_left', 'glass_margin_left')); ?>>
                                    <?php $__errorArgs = ['data.glass_margin_left'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="glass_margin_left-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <p class="form-text mb-0">Blank uses paper default: A4 / Custom 10 mm, A5 8 mm.</p>
                        </div>
                    </div>
                </section>
            </div>
            <div class="d-flex gap-2 mt-4">
                <a class="btn btn-danger" href="<?php echo e(route('prescription.dashboard')); ?>">Cancel</a>
                <button class="btn btn-primary" type="submit">Save</button>
            </div>
        </fieldset>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Prescription\resources\views\settings\index.blade.php ENDPATH**/ ?>