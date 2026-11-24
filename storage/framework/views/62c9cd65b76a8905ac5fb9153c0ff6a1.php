

<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle, 'description' => 'Select a plan and choose the tenant actions included in it. Account roles still decide who can use them.'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div data-permission-editor data-permission-readonly="<?php echo e($canManagePermissions ? '0' : '1'); ?>"
        data-permission-total="<?php echo e($permissionCount); ?>" data-permission-initial="<?php echo e(implode(',', $selectedPermissions)); ?>"
        data-permission-unrestricted-initial="<?php echo e($unrestrictedRouteAccess ? '1' : '0'); ?>">
        <?php echo $__env->make('core::permissions.picker', [
            'recordLabel' => 'plan',
            'recordIcon' => 'layer-group',
            'records' => $plans,
            'selectedRecord' => $selectedPlan,
            'searchValue' => $planSearch,
            'hasMoreRecords' => $hasMorePlans,
            'pickerAction' => route($routePrefix.'.index'),
            'searchField' => 'plan_search',
            'recordField' => 'plan',
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <?php if($selectedPlan === null): ?>
            <section class="app-card">
                <?php echo $__env->make('core::partials.empty', ['title' => 'No plan selected', 'description' => 'Choose a plan above to view its permissions.', 'icon' => 'key'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </section>
        <?php else: ?>
            <?php $__env->startComponent('core::permissions.matrix', [
                'recordName' => $selectedPlan->name,
                'formAction' => route($routePrefix.'.update', $selectedPlan),
                'fieldName' => 'route_permissions',
                'markerName' => 'route_permissions_present',
                'canManagePermissions' => $canManagePermissions,
                'selectedPermissions' => $selectedPermissions,
                'permissionCount' => $permissionCount,
                'areas' => $areas,
            ]); ?>
                <div class="px-3 px-md-4 py-3 border-bottom">
                    <input name="unrestricted_route_access" type="hidden" value="0">
                    <label class="form-check d-flex align-items-center gap-2 mb-2" for="unrestricted-route-access">
                        <input class="<?php echo e(inputClass('unrestricted_route_access', 'form-check-input')); ?>"
                            id="unrestricted-route-access" name="unrestricted_route_access" type="checkbox" value="1"
                            data-permission-unrestricted <?php if(! $canManagePermissions): echo 'disabled'; endif; ?>
                            <?php echo e(getChecked(1, 'unrestricted_route_access', null, $unrestrictedRouteAccess)); ?>

                            <?php echo e(inputAttrs('unrestricted_route_access', 'unrestricted-route-access', 'plan-routes-help')); ?>>
                        Allow all current and future routes
                    </label>
                    <p class="form-text mb-0" id="plan-routes-help">
                        Uncheck to use only the selected actions; no selections means no optional routes. Account roles still apply.
                        Dashboard, sign-in, password recovery, and Billing remain available for subscription and payment recovery.
                    </p>
                    <?php $__errorArgs = ['unrestricted_route_access'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback d-block" id="unrestricted-route-access-error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            <?php echo $__env->renderComponent(); ?>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\resources\views\plan-permissions\index.blade.php ENDPATH**/ ?>