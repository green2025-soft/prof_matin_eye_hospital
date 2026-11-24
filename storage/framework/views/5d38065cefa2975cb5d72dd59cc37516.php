<form class="app-card overflow-hidden" action="<?php echo e($formAction); ?>" method="POST" data-permission-form>
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>
    <input name="<?php echo e($markerName); ?>" type="hidden" value="1">
    <div class="app-permission-summary app-card-heading d-flex flex-wrap align-items-center justify-content-between gap-3 px-3 px-md-4 py-3 border-bottom">
        <div>
            <h2 class="h6 mb-1"><?php echo e($recordName); ?></h2>
            <output class="small text-body-secondary" data-permission-count aria-live="polite"><?php echo e(count($selectedPermissions)); ?> of <?php echo e($permissionCount); ?> selected</output>
        </div>
        <label class="d-flex align-items-center gap-2 small fw-semibold">
            <input class="form-check-input mt-0" type="checkbox" data-permission-visible-toggle <?php if(! $canManagePermissions): echo 'disabled'; endif; ?>>
            Select visible
        </label>
    </div>
    <?php echo e($slot); ?>

    <div class="px-3 px-md-4 py-3 border-bottom">
        <div class="app-permission-toolbar row align-items-end">
            <div class="col-md">
                <label class="form-label small" for="permission-area">Area</label>
                <select class="form-select" id="permission-area" data-permission-area>
                    <option value="">All areas</option>
                    <?php $__currentLoopData = $areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($area['key']); ?>"><?php echo e($area['label']); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md">
                <label class="form-label small" for="permission-group">Group</label>
                <select class="form-select" id="permission-group" data-permission-group>
                    <option value="">All groups</option>
                    <?php $__currentLoopData = $areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $__currentLoopData = $area['groups']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($group['key']); ?>" data-area-key="<?php echo e($area['key']); ?>"><?php echo e($area['label']); ?> / <?php echo e($group['label']); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label small" for="permission-search">Search permissions</label>
                <div class="app-input-wrap app-search-field">
                    <i class="fa-solid fa-magnifying-glass app-search-icon" aria-hidden="true"></i>
                    <input class="form-control" id="permission-search" type="search" placeholder="Search an action or permission name..." data-permission-search>
                </div>
            </div>
        </div>
        <p class="form-text mb-0 mt-2" id="permissions-help">
            <?php echo e($canManagePermissions ? 'Choose individual actions or use the area and group checkboxes. Filters only change what is visible.' : 'You have read-only access. These permissions cannot be changed here.'); ?>

        </p>
        <?php $__errorArgs = [$fieldName];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback d-block" id="<?php echo e($fieldName); ?>-error"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <?php $__errorArgs = [$fieldName.'.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <?php $__errorArgs = [$markerName];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <fieldset <?php if(! $canManagePermissions): echo 'disabled'; endif; ?> <?php echo e(inputAttrs($fieldName, null, 'permissions-help')); ?>>
        <legend class="visually-hidden">Permissions for <?php echo e($recordName); ?></legend>
        <div class="table-responsive" tabindex="0" role="region" aria-label="Permission matrix">
            <table class="table app-table app-permission-matrix align-middle mb-0">
                <caption class="visually-hidden">Permission areas, groups, and actions for <?php echo e($recordName); ?></caption>
                <thead>
                    <tr>
                        <th scope="col">Area</th>
                        <th scope="col">Group</th>
                        <th scope="col">Permissions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $__currentLoopData = $area['groups']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr data-permission-row data-permission-area-key="<?php echo e($area['key']); ?>"
                                data-permission-group-key="<?php echo e($group['key']); ?>">
                                <th class="app-permission-area" scope="row">
                                    <label class="d-flex align-items-center gap-2">
                                        <input class="form-check-input mt-0" type="checkbox" data-permission-area-toggle data-area-key="<?php echo e($area['key']); ?>" aria-label="Select area <?php echo e($area['label']); ?>">
                                        <span><?php echo e($area['label']); ?></span>
                                    </label>
                                </th>
                                <th class="app-permission-group" scope="row">
                                    <label class="d-flex align-items-center gap-2">
                                        <input class="form-check-input mt-0" type="checkbox" data-permission-group-toggle data-group-key="<?php echo e($group['key']); ?>" aria-label="Select group <?php echo e($group['label']); ?>">
                                        <span><?php echo e($group['label']); ?></span>
                                    </label>
                                </th>
                                <td>
                                    <div class="app-permission-options d-flex flex-wrap gap-2">
                                        <?php $__currentLoopData = $group['permissions']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <label class="app-permission-option app-check-option d-flex align-items-center gap-2" for="permission-<?php echo e($permission['id']); ?>" title="<?php echo e($permission['name']); ?>">
                                                <input class="<?php echo e(inputClass($fieldName, 'form-check-input')); ?>"
                                                    id="permission-<?php echo e($permission['id']); ?>" name="<?php echo e($fieldName); ?>[]"
                                                    type="checkbox" value="<?php echo e($permission['id']); ?>"
                                                    <?php echo e(getChecked($permission['id'], $fieldName, null, $selectedPermissions)); ?>

                                                    <?php echo e(inputAttrs($fieldName)); ?> data-permission-item>
                                                <span class="small"><?php echo e($permission['label']); ?></span>
                                            </label>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <tr data-permission-empty <?php if($areas !== []): ?> hidden <?php endif; ?>>
                        <td class="text-center small text-body-secondary py-4" colspan="3">No permissions match these filters.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </fieldset>
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 px-3 px-md-4 py-3 border-top">
        <span class="app-permission-state small text-body-secondary" data-permission-state><?php echo e(! $canManagePermissions ? 'Read-only access' : (getValue($markerName) ? 'Unsaved changes' : 'All changes saved')); ?></span>
        <?php if($canManagePermissions): ?>
            <button class="btn btn-primary" type="submit" data-permission-save>
                <i class="fa-solid fa-floppy-disk me-2" aria-hidden="true"></i>Save
            </button>
        <?php endif; ?>
    </div>
</form>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\resources\views\permissions\matrix.blade.php ENDPATH**/ ?>