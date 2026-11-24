<?php
    $field = 'medicines.'.$medicineIndex;
    $name = 'medicines['.$medicineIndex.']';
    $errorId = str_replace('.', '-', $field);
    $controlClass = 'form-control form-control-sm';
    $doseValue = data_get($medicine, 'dose');
    $instructionValue = data_get($medicine, 'instructions');
    $instructionChoices = ['খাওয়ার পরে', 'খাওয়ার আগে', 'Chewable'];
    $productId = data_get($medicine, 'product_id');
    $product = $medicineProducts->get($productId);
    $genericName = data_get($product, 'category.name', '');
    $dosageType = data_get($medicine, 'dosage_type') ?? data_get($product, 'dosage.name', '');
?>

<li class="prescription-medicine-row d-flex flex-column mt-3" id="<?php echo e($errorId); ?>-row">
    <div class="app-prescription-rx-card-heading d-flex align-items-center">
        <span class="prescription-medicine-number" aria-hidden="true"></span>
        <div class="flex-grow-1">
            <label class="app-prescription-medicine-name d-flex align-items-center">
                <span class="visually-hidden">Medicine</span>
                <input type="hidden" name="<?php echo e($name); ?>[product_id]" form="prescription-form"
                    value="<?php echo e($productId); ?>">
                <input name="<?php echo e($name); ?>[medicine_name]" form="prescription-form" required maxlength="255"
                    class="<?php echo e(inputClass($field.'.medicine_name', $controlClass)); ?>"
                    value="<?php echo e(data_get($medicine, 'medicine_name')); ?>"
                    <?php echo e(inputAttrs($field.'.medicine_name')); ?>>
                <?php $__errorArgs = [$field.'.medicine_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="invalid-feedback" id="<?php echo e($errorId); ?>-medicine_name-error">
                        <?php echo e($message); ?>

                    </span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <?php $__errorArgs = [$field.'.product_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </label>
            <div class="small text-body-secondary prescription-medicine-generic" hidden>
                <?php echo e($genericName); ?>

            </div>
        </div>
        <button class="btn btn-outline-danger btn-sm prescription-medicine-remove" type="button"
            aria-label="Remove medicine">
            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
        </button>
    </div>

    <div class="app-prescription-rx-card-controls d-grid align-items-start">
        <div class="prescription-dosage-field">
            <label class="form-label" for="<?php echo e($errorId); ?>-dosage-type">Type</label>
            <select class="<?php echo e(inputClass($field.'.dosage_type', 'form-select form-select-sm')); ?> prescription-dosage-type"
                id="<?php echo e($errorId); ?>-dosage-type" name="<?php echo e($name); ?>[dosage_type]" form="prescription-form"
                <?php echo e(inputAttrs($field.'.dosage_type')); ?>>
                <?php if(! filled($dosageType) || ! array_key_exists($dosageType, $dosageTypes)): ?>
                    <option value="<?php echo e($dosageType); ?>" selected hidden><?php echo e($dosageType); ?></option>
                <?php endif; ?>
                <?php $__currentLoopData = $dosageTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($value); ?>" <?php if($dosageType === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = [$field.'.dosage_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="prescription-frequency-field d-grid align-content-start text-start">
            <label class="form-label prescription-frequency-label" for="<?php echo e($errorId); ?>-frequency">Dose</label>
            <input id="<?php echo e($errorId); ?>-frequency" name="<?php echo e($name); ?>[frequency]" form="prescription-form"
                type="text" maxlength="100" placeholder="1+0+1" autocomplete="off"
                class="<?php echo e(inputClass($field.'.frequency', $controlClass)); ?> prescription-frequency-value"
                value="<?php echo e(data_get($medicine, 'frequency')); ?>" <?php echo e(inputAttrs($field.'.frequency')); ?>>
            <?php $__errorArgs = [$field.'.frequency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback d-block" id="<?php echo e($errorId); ?>-frequency-error"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <div class="prescription-application-dose" hidden>
                <div class="row g-2">
                    <div class="col-7">
                        <label class="form-label" for="<?php echo e($errorId); ?>-application-amount">পরিমাণ</label>
                        <div class="input-group input-group-sm flex-nowrap">
                            <input class="form-control prescription-application-amount" type="text" inputmode="decimal"
                                id="<?php echo e($errorId); ?>-application-amount" form="prescription-form" maxlength="30" autocomplete="off">
                            <span class="input-group-text prescription-application-unit">ফোঁটা করে</span>
                        </div>
                    </div>
                    <div class="col-5">
                        <label class="form-label" for="<?php echo e($errorId); ?>-application-times">দিনে</label>
                        <div class="input-group input-group-sm flex-nowrap">
                            <input class="form-control prescription-application-times" type="text" inputmode="numeric"
                                id="<?php echo e($errorId); ?>-application-times" form="prescription-form" maxlength="3" pattern="[0-9০-৯]+" autocomplete="off">
                            <span class="input-group-text">বার</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dropdown prescription-schedule-menu">
                <button class="btn btn-sm dropdown-toggle prescription-frequency-mode" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false" value="3">Schedule</button>
                <ul class="dropdown-menu" role="menu">
                    <?php $__currentLoopData = [3, 4, 5, 6]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $periodCount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <button class="dropdown-item prescription-schedule-count" type="button"
                                role="menuitemradio" aria-checked="false"
                                value="<?php echo e($periodCount); ?>"><?php echo e($periodCount); ?></button>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <button class="dropdown-item prescription-schedule-count" type="button"
                            role="menuitemradio" aria-checked="false" value="custom">Custom frequency</button>
                    </li>
                </ul>
            </div>
            <div class="prescription-schedule-controls" role="group" aria-label="Daily schedule"></div>
            <div class="app-prescription-food-options" role="group" aria-label="Food instructions">
                <button class="prescription-food-instruction" type="button" aria-pressed="false">
                    খাওয়ার পরে
                </button>
                <button class="prescription-food-instruction" type="button" aria-pressed="false" tabindex="-1">
                    খাওয়ার আগে
                </button>
            </div>
            <div class="prescription-instruction-selection" aria-live="polite"></div>
        </div>

        <div class="app-prescription-rx-card-right d-grid align-items-start text-start">
            <div class="prescription-dose-field">
                <label class="form-label" for="<?php echo e($errorId); ?>-dose">Unit</label>
                <div class="dropdown prescription-unit-picker">
                    <button id="<?php echo e($errorId); ?>-dose" type="button" value="<?php echo e($doseValue); ?>"
                        class="<?php echo e(inputClass($field.'.dose', 'btn btn-sm dropdown-toggle prescription-unit-toggle')); ?>"
                        aria-expanded="false" aria-controls="prescription-unit-palette"
                        aria-keyshortcuts="F4" title="Unit (F4)"
                        <?php echo e(inputAttrs($field.'.dose')); ?>>
                        <span class="prescription-unit-label"><?php echo e(filled($doseValue) ? $doseValue : 'select'); ?></span>
                    </button>
                </div>
                <input type="hidden" name="<?php echo e($name); ?>[dose]" form="prescription-form" value="<?php echo e($doseValue); ?>">
                <?php $__errorArgs = [$field.'.dose'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback d-block" id="<?php echo e($errorId); ?>-dose-error"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="prescription-duration-field">
                <label class="form-label" for="<?php echo e($errorId); ?>-duration-count">Duration</label>
                <div class="prescription-duration-controls d-flex align-items-center">
                    <input class="form-control form-control-sm prescription-duration-count" type="number"
                        id="<?php echo e($errorId); ?>-duration-count" form="prescription-form" min="1" step="1">
                    <select class="form-select form-select-sm prescription-duration-unit" aria-label="Duration unit">
                        <option value="days">দিন</option>
                        <option value="weeks">সপ্তাহ</option>
                        <option value="months">মাস</option>
                        <option value="years">বছর</option>
                        <option value="continue">চলবে</option>
                        <option value="custom">Custom</option>
                    </select>
                </div>
                <label class="visually-hidden" for="<?php echo e($errorId); ?>-duration">Custom duration</label>
                <input id="<?php echo e($errorId); ?>-duration" name="<?php echo e($name); ?>[duration]" form="prescription-form"
                    maxlength="100" hidden
                    class="<?php echo e(inputClass($field.'.duration', $controlClass)); ?> prescription-duration-value"
                    value="<?php echo e(data_get($medicine, 'duration')); ?>" <?php echo e(inputAttrs($field.'.duration')); ?>>
                <?php $__errorArgs = [$field.'.duration'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback d-block" id="<?php echo e($errorId); ?>-duration-error"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="app-prescription-instruction-row w-100">
                <label class="visually-hidden" for="<?php echo e($errorId); ?>-instruction-search">
                    Search medicine instructions
                </label>
                <select id="<?php echo e($errorId); ?>-instruction-search"
                    class="<?php echo e(inputClass($field.'.instructions', 'form-select')); ?> prescription-instruction-search"
                    data-url="<?php echo e(route('prescription.options.selector', ['section' => 'medicine_instruction'])); ?>"
                    <?php if($canCreateOptions): ?> data-create-url="<?php echo e(route('prescription.options.store')); ?>" <?php endif; ?>
                    <?php echo e(inputAttrs($field.'.instructions')); ?>>
                    <option value=""></option>
                    <?php $__currentLoopData = $instructionChoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $choice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($choice); ?>" <?php if($instructionValue === $choice): echo 'selected'; endif; ?>><?php echo e($choice); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if(filled($instructionValue) && ! in_array($instructionValue, $instructionChoices, true)): ?>
                        <option value="<?php echo e($instructionValue); ?>" selected><?php echo e($instructionValue); ?></option>
                    <?php endif; ?>
                </select>
                <input type="hidden" name="<?php echo e($name); ?>[instructions]" form="prescription-form"
                    value="<?php echo e($instructionValue); ?>">
                <div class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                    'prescription-instruction-error',
                    'invalid-feedback d-block' => $errors->has($field.'.instructions'),
                ]); ?>"
                    id="<?php echo e($errorId); ?>-instructions-error" role="alert">
                    <?php $__errorArgs = [$field.'.instructions'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><?php echo e($message); ?><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>
    </div>
</li>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Prescription\resources\views\prescriptions\medicine-row.blade.php ENDPATH**/ ?>