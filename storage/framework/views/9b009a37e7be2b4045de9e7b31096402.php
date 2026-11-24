<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle, 'description' => 'Manage tenant access periods and renewal settings.', 'create' => ['model' => resourceModel('core.subscriptions.create'), 'url' => route('core.subscriptions.create'), 'label' => 'Assign subscription']], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <section class="app-card overflow-hidden">
        <form class="px-3 px-md-4 py-3 border-bottom" action="<?php echo e(route($routePrefix.'.index')); ?>" method="GET" aria-label="Filter subscriptions">
            <div class="row g-2 align-items-end">
                <div class="col-md-8">
                    <label class="form-label small" for="status">Status</label>
                    <select class="<?php echo e(inputClass('status', 'form-select')); ?>" id="status" name="status" <?php echo e(inputAttrs('status')); ?>>
                        <option value="">All statuses</option>
                        <?php $__currentLoopData = $filterOptions['status']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($value); ?>" <?php echo e(getSelected($value, 'status', null, $filters['status'] ?? '')); ?>><?php echo e($label); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="status-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-4"><?php echo $__env->make('core::partials.filter-actions', ['routePrefix' => $routePrefix], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
            </div>
        </form>
        <?php if($records->isEmpty()): ?>
            <?php echo $__env->make('core::partials.empty', ['title' => 'No subscriptions found', 'description' => 'Try another status or assign a subscription to a tenant.', 'icon' => 'calendar-check'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <caption class="visually-hidden">Tenant subscriptions and current access periods</caption>
                    <thead><tr><th scope="col">Tenant</th><th scope="col">Plan</th><th scope="col">Status</th><th scope="col">Price</th><th scope="col">Period ends</th><th class="text-end" scope="col">Actions</th></tr></thead>
                    <tbody>
                        <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <th scope="row"><div class="fw-semibold"><?php echo e($subscription->tenant->name); ?></div><div class="small text-body-secondary fw-normal">#<?php echo e($subscription->id); ?></div></th>
                                <td><?php echo e($subscription->plan->name); ?></td>
                                <td><span class="app-status app-status-<?php echo e($subscription->status); ?>"><?php echo e($filterOptions['status'][$subscription->status]); ?></span><?php if($subscription->cancel_at_period_end): ?><div class="small text-body-secondary">Cancels at period end</div><?php endif; ?></td>
                                <td><?php echo e($subscription->currency); ?> <?php echo e($subscription->price); ?><div class="small text-body-secondary"><?php echo e(\Modules\Core\Models\Plan::INTERVALS[$subscription->billing_interval]); ?></div></td>
                                <td class="small text-nowrap"><?php echo e($subscription->current_period_ends_at?->format('d M Y H:i') ?? '—'); ?></td>
                                <td><?php echo $__env->make('core::partials.row-actions', ['model' => $subscription, 'routePrefix' => $routePrefix], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        <?php echo $__env->make('core::partials.pagination', ['paginator' => $records, 'filters' => $filters], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\resources\views\subscriptions\index.blade.php ENDPATH**/ ?>