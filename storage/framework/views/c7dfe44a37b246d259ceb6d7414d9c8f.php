<?php $__currentLoopData = $credentialFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $definition): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $fieldName = 'credentials.'.$field;
        $inputId = 'credential-'.$setting->group.'-'.$field;
        $isConfigured = $configuredCredentials[$field] ?? false;
        $fieldValue = $isConfigured ? '' : ($definition['default'] ?? '');
        $helpId = $isConfigured ? $inputId.'-help' : null;
    ?>
    <div class="<?php echo e(($definition['multiline'] ?? false) ? 'col-12' : 'col-md-6'); ?>">
        <label class="form-label" for="<?php echo e($inputId); ?>"><?php echo e($definition['label']); ?></label>
        <?php if($definition['multiline'] ?? false): ?>
            <textarea class="<?php echo e(inputClass($fieldName)); ?>" id="<?php echo e($inputId); ?>" name="credentials[<?php echo e($field); ?>]"
                rows="4" maxlength="<?php echo e($definition['max']); ?>" autocomplete="off" spellcheck="false"
                <?php echo e(inputAttrs($fieldName, $inputId, $helpId)); ?>><?php echo e($fieldValue); ?></textarea>
        <?php else: ?>
            <div class="app-input-wrap">
                <input class="<?php echo e(inputClass($fieldName)); ?> app-password-input" id="<?php echo e($inputId); ?>"
                    name="credentials[<?php echo e($field); ?>]" type="password" value="<?php echo e($fieldValue); ?>"
                    <?php if(isset($definition['min'])): ?> minlength="<?php echo e($definition['min']); ?>" <?php endif; ?>
                    maxlength="<?php echo e($definition['max']); ?>" autocomplete="new-password"
                    <?php echo e(inputAttrs($fieldName, $inputId, $helpId)); ?>>
                <button class="app-password-toggle" type="button" data-password-toggle aria-controls="<?php echo e($inputId); ?>"
                    aria-label="Show credential" aria-pressed="false" title="Show credential">
                    <i class="fa-solid fa-eye" aria-hidden="true"></i>
                </button>
            </div>
        <?php endif; ?>
        <?php $__errorArgs = [$fieldName];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback d-block" id="<?php echo e($inputId); ?>-error"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <?php if($isConfigured): ?>
            <div class="form-text" id="<?php echo e($helpId); ?>">Configured. Blank keeps existing.</div>
        <?php endif; ?>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\resources\views\settings\credentials.blade.php ENDPATH**/ ?>