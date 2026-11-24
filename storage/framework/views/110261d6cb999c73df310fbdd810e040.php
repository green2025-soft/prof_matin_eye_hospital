

<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle, 'description' => 'Assign a plan and control renewal. Paid access starts after invoice settlement.'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php if(! $hasEligibleTenants): ?>
        <section class="app-card p-3 p-md-4">
            <h2 class="h5">New workspaces already have a subscription</h2>
            <p class="text-body-secondary">
                The Starter subscription is assigned automatically during setup. To choose another plan, update the existing subscription.
            </p>
            <?php if($existingSubscriptions->isNotEmpty()): ?>
                <div class="list-group mb-3">
                    <?php $__currentLoopData = $existingSubscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $existingSubscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="list-group-item d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div class="text-break">
                                <div class="fw-semibold"><?php echo e($existingSubscription->tenant->name); ?></div>
                                <div class="small text-body-secondary">
                                    <?php echo e($existingSubscription->plan->name); ?> · <?php echo e($statuses[$existingSubscription->status]); ?>

                                </div>
                            </div>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $existingSubscription)): ?>
                                <a class="btn btn-outline-primary btn-sm"
                                    href="<?php echo e(route('core.subscriptions.edit', $existingSubscription)); ?>">Change plan</a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
            <?php if($subscriptionsUrl): ?>
                <a class="btn btn-primary" href="<?php echo e($subscriptionsUrl); ?>">Manage existing subscriptions</a>
            <?php endif; ?>
        </section>
    <?php elseif(! $hasPlans): ?>
        <section class="app-card p-3 p-md-4">
            <h2 class="h5">An active plan is required</h2>
            <p class="text-body-secondary">Create or activate a plan before assigning a subscription.</p>
            <?php if($createPlanUrl): ?>
                <a class="btn btn-primary" href="<?php echo e($createPlanUrl); ?>">Create a plan</a>
            <?php endif; ?>
        </section>
    <?php else: ?>
        <form class="app-card p-3 p-md-4" action="<?php echo e($formAction); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php if($formMethod !== 'POST'): ?> <?php echo method_field($formMethod); ?> <?php endif; ?>
            <section class="app-form-section" aria-labelledby="subscription-details-heading">
                <h2 class="app-form-section-title" id="subscription-details-heading">Subscription details</h2>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="tenant_id">Tenant <span class="text-danger" aria-hidden="true">*</span></label>
                        <?php if($subscription->exists): ?>
                            <input class="form-control" id="tenant_id" type="text" value="<?php echo e($subscription->tenant->name); ?>" readonly>
                        <?php else: ?>
                            <input class="<?php echo e(inputClass('tenant_id')); ?>" id="tenant_id" name="tenant_id" type="number" value="<?php echo e(getValue('tenant_id')); ?>" list="subscription-tenants" min="1" step="1" required <?php echo e(inputAttrs('tenant_id', null, 'tenant-help')); ?>>
                            <datalist id="subscription-tenants"><?php $__currentLoopData = $tenants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tenant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($tenant->id); ?>"><?php echo e($tenant->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></datalist>
                            <p class="form-text" id="tenant-help">Choose a workspace or enter its numeric tenant ID.</p>
                            <?php $__errorArgs = ['tenant_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="tenant_id-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="plan_id">Plan <span class="text-danger" aria-hidden="true">*</span></label>
                        <input class="<?php echo e(inputClass('plan_id')); ?>" id="plan_id" name="plan_id" type="number"
                            value="<?php echo e(getValue('plan_id', $subscription)); ?>" list="subscription-plans" min="1" step="1" required
                            <?php echo e(inputAttrs('plan_id', null, 'plan-help')); ?>>
                        <datalist id="subscription-plans">
                            <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($plan->id); ?>"><?php echo e($plan->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </datalist>
                        <p class="form-text" id="plan-help">
                            Choose a suggestion or enter a plan ID from Plans. Change plans after the current access period ends; resolve existing unpaid invoices first.
                        </p>
                        <?php $__errorArgs = ['plan_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="plan_id-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="renewal">Renewal <span class="text-danger" aria-hidden="true">*</span></label>
                        <select class="<?php echo e(inputClass('renewal', 'form-select')); ?>" id="renewal" name="renewal" required <?php echo e(inputAttrs('renewal', null, 'renewal-help')); ?>>
                            <option value="renew" <?php echo e(getSelected('renew', 'renewal', null, $renewal)); ?>>Renew automatically</option>
                            <?php if($subscription->exists): ?>
                                <option value="period_end" <?php echo e(getSelected('period_end', 'renewal', null, $renewal)); ?>>Cancel at the end of the current period</option>
                                <option value="now" <?php echo e(getSelected('now', 'renewal', null, $renewal)); ?>>Cancel access now</option>
                            <?php endif; ?>
                        </select>
                        <p class="form-text" id="renewal-help">Free plans renew without payment. Paid renewals create an invoice; payment is never charged automatically.</p>
                        <?php $__errorArgs = ['renewal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="renewal-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </section>
            <?php if($subscription->exists): ?>
                <section class="app-form-section" aria-labelledby="subscription-period-heading">
                    <h2 class="app-form-section-title" id="subscription-period-heading">Current billing period</h2>
                    <dl class="row small mb-0">
                        <dt class="col-sm-4">Status</dt><dd class="col-sm-8"><?php echo e($statuses[$subscription->status]); ?></dd>
                        <dt class="col-sm-4">Price</dt><dd class="col-sm-8"><?php echo e($subscription->currency); ?> <?php echo e($subscription->price); ?> / <?php echo e(\Modules\Core\Models\Plan::INTERVALS[$subscription->billing_interval]); ?></dd>
                        <dt class="col-sm-4">Started</dt><dd class="col-sm-8"><?php echo e($subscription->starts_at?->format('d M Y H:i') ?? '—'); ?></dd>
                        <dt class="col-sm-4">Period</dt><dd class="col-sm-8"><?php echo e($subscription->current_period_starts_at?->format('d M Y H:i') ?? '—'); ?> — <?php echo e($subscription->current_period_ends_at?->format('d M Y H:i') ?? '—'); ?></dd>
                        <dt class="col-sm-4">Trial ends</dt><dd class="col-sm-8"><?php echo e($subscription->trial_ends_at?->format('d M Y H:i') ?? '—'); ?></dd>
                    </dl>
                </section>
            <?php endif; ?>
            <?php echo $__env->make('core::partials.form-actions', ['model' => $subscription, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </form>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\resources\views\subscriptions\form.blade.php ENDPATH**/ ?>