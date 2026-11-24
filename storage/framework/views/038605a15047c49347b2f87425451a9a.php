<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle, 'description' => 'Manage subscription pricing, limits, and available features.', 'create' => ['model' => resourceModel('core.plans.create'), 'url' => route('core.plans.create'), 'label' => 'New plan']], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <section class="app-card overflow-hidden">
        <form class="px-3 px-md-4 py-3 border-bottom" action="<?php echo e(route($routePrefix.'.index')); ?>" method="GET" aria-label="Filter plans">
            <div class="row g-2 align-items-end">
                <div class="col-lg-5">
                    <label class="form-label small" for="search">Search plans</label>
                    <div class="app-input-wrap app-search-field">
                        <i class="fa-solid fa-magnifying-glass app-search-icon" aria-hidden="true"></i>
                        <input class="<?php echo e(inputClass('search')); ?>" id="search" name="search" type="search" value="<?php echo e(getValue('search', null, $filters['search'] ?? '')); ?>" placeholder="Name or slug starts with..." maxlength="80" <?php echo e(inputAttrs('search')); ?>>
                    </div>
                    <?php $__errorArgs = ['search'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="search-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <label class="form-label small" for="billing_interval">Interval</label>
                    <select class="<?php echo e(inputClass('billing_interval', 'form-select')); ?>" id="billing_interval" name="billing_interval" <?php echo e(inputAttrs('billing_interval')); ?>>
                        <option value="">All intervals</option>
                        <?php $__currentLoopData = \Modules\Core\Models\Plan::INTERVALS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value); ?>" <?php echo e(getSelected($value, 'billing_interval', null, $filters['billing_interval'] ?? '')); ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['billing_interval'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="billing_interval-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <label class="form-label small" for="is_active">Availability</label>
                    <select class="<?php echo e(inputClass('is_active', 'form-select')); ?>" id="is_active" name="is_active" <?php echo e(inputAttrs('is_active')); ?>>
                        <option value="">All plans</option>
                        <option value="1" <?php echo e(getSelected(1, 'is_active', null, $filters['is_active'] ?? '')); ?>>Active</option>
                        <option value="0" <?php echo e(getSelected(0, 'is_active', null, $filters['is_active'] ?? '')); ?>>Inactive</option>
                    </select>
                    <?php $__errorArgs = ['is_active'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="is_active-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-lg-3"><?php echo $__env->make('core::partials.filter-actions', ['routePrefix' => $routePrefix], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
            </div>
        </form>
        <?php if($records->isEmpty()): ?>
            <?php echo $__env->make('core::partials.empty', ['title' => 'No plans found', 'description' => 'Try different filters or create a plan.', 'icon' => 'layer-group'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <caption class="visually-hidden">Subscription plan catalog</caption>
                    <thead><tr><th scope="col">Plan</th><th scope="col">Price</th><th scope="col">Trial</th><th scope="col">Users</th><th scope="col">Availability</th><th class="text-end" scope="col">Actions</th></tr></thead>
                    <tbody>
                        <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <th scope="row"><div class="fw-semibold"><?php echo e($plan->name); ?></div><div class="small text-body-secondary fw-normal"><?php echo e($plan->slug); ?></div></th>
                                <td><?php echo e($plan->currency); ?> <?php echo e($plan->price); ?><div class="small text-body-secondary"><?php echo e(\Modules\Core\Models\Plan::INTERVALS[$plan->billing_interval]); ?></div></td>
                                <td><?php echo e($plan->trial_days); ?> days</td>
                                <td><?php echo e($plan->user_limit ?? 'Unlimited'); ?></td>
                                <td><span class="app-status app-status-<?php echo e($plan->is_active ? 'active' : 'suspended'); ?>"><?php echo e($plan->is_active ? 'Active' : 'Inactive'); ?></span></td>
                                <td><?php echo $__env->make('core::partials.row-actions', ['model' => $plan, 'routePrefix' => $routePrefix], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        <?php echo $__env->make('core::partials.pagination', ['paginator' => $records, 'filters' => $filters], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\resources\views\plans\index.blade.php ENDPATH**/ ?>