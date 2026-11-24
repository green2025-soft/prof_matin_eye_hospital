

<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <header class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
        <h1 class="h4 mb-0"><?php echo e($pageTitle); ?></h1>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manageAccounts', $accountModel)): ?>
            <a class="btn btn-primary" href="<?php echo e(route('core.sms-accounts.create')); ?>"><i class="fa-solid fa-plus me-2" aria-hidden="true"></i>New account</a>
        <?php endif; ?>
    </header>
    <section class="app-card overflow-hidden">
        <form class="row g-2 align-items-end p-3 border-bottom" method="GET" action="<?php echo e(route('core.sms-accounts.index')); ?>">
            <div class="col-md-8">
                <label class="form-label" for="search">Tenant</label>
                <input class="form-control" id="search" name="search" value="<?php echo e($filters['search'] ?? ''); ?>" maxlength="80">
            </div>
            <div class="col-md-4"><?php echo $__env->make('core::partials.filter-actions', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
        </form>
        <div class="table-responsive overflow-y-hidden">
            <table class="table app-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Tenant</th>
                        <th>Status</th>
                        <th class="text-end">Balance</th>
                        <th class="text-end">Rate / part</th>
                        <th class="text-end">Monthly parts</th>
                        <th class="text-end">Allowed features</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tenant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php ($account = $tenant->smsAccount); ?>
                        <tr>
                            <th scope="row"><?php echo e($tenant->name); ?><div class="small fw-normal text-body-secondary"><?php echo e($tenant->slug); ?></div></th>
                            <td>
                                <?php if($account): ?>
                                    <span class="badge <?php echo e($account->enabled ? 'text-bg-success' : 'text-bg-secondary'); ?>"><?php echo e($account->enabled ? 'Enabled' : 'Disabled'); ?></span>
                                <?php else: ?>
                                    <span class="badge text-bg-secondary">Not configured</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end text-nowrap">BDT <?php echo e(moneyDecimal($account?->balance ?? 0)); ?></td>
                            <td class="text-end text-nowrap"><?php echo e($account ? moneyDecimal($account->rate) : '—'); ?></td>
                            <td class="text-end"><?php echo e($account ? ($account->monthly_limit ?? 'Unlimited') : '—'); ?></td>
                            <td class="text-end"><?php echo e(count($account?->allowed_features ?? [])); ?></td>
                            <td class="text-end">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manageAccounts', $account ?? $accountModel)): ?>
                                    <?php if($account): ?>
                                        <a class="btn btn-outline-primary btn-sm" href="<?php echo e(route('core.sms-accounts.edit', $account)); ?>">Edit</a>
                                    <?php else: ?>
                                        <a class="btn btn-outline-primary btn-sm" href="<?php echo e(route('core.sms-accounts.create', ['tenant_id' => $tenant->id])); ?>">Configure</a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="7" class="text-center text-body-secondary py-4">No tenants found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php echo $__env->make('core::partials.pagination', ['paginator' => $records], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\resources\views\sms-accounts\index.blade.php ENDPATH**/ ?>