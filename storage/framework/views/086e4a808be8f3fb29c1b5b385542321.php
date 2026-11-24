<?php $pageTitle = 'Tenant' ?>

<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle??'', 'description' => 'Manage workspaces and their domains.', 'create' => ['model' => resourceModel('core.tenants.create'), 'url' => route('core.tenants.create'), 'label' => 'New Tenant']], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <section class="app-card overflow-hidden">
        <div class="app-card-heading d-flex align-items-center gap-2 px-3 px-md-4 py-3 border-bottom"><i class="fa-solid fa-building text-body-secondary" aria-hidden="true"></i><h2 class="h6 mb-0">Workspace directory</h2></div>
        <form class="px-3 px-md-4 py-3 border-bottom" action="<?php echo e(route('core.tenants.index')); ?>" method="GET" aria-label="Filter tenants">
            <div class="row g-2 align-items-end">
                <div class="col-lg-6">
                    <label class="form-label small" for="search">Search workspaces</label>
                    <div class="app-input-wrap app-search-field">
                        <i class="fa-solid fa-magnifying-glass app-search-icon" aria-hidden="true"></i>
                        <input class="<?php echo e(inputClass('search')); ?>" id="search" name="search" type="search" value="<?php echo e(getValue('search', null, $filters['search'] ?? '')); ?>" placeholder="Workspace name starts with…" maxlength="80" <?php echo e(inputAttrs('search')); ?>>
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
                <div class="col-sm-6 col-lg-3">
                    <label class="form-label" for="filter-status">Status</label>
                    <select class="<?php echo e(inputClass('status', 'form-select')); ?>" id="filter-status" name="status" <?php echo e(inputAttrs('status')); ?>>
                        <?php $__currentLoopData = ['' => 'All statuses'] + $filterOptions['status']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value); ?>" <?php echo e(getSelected($value, 'status', null, $filters['status'] ?? '')); ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                <div class="col-sm-6 col-lg-3"><?php echo $__env->make('core::partials.filter-actions', ['routePrefix' => 'core.tenants'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
            </div>
        </form>
        <?php if($records->isEmpty()): ?>
            <?php echo $__env->make('core::partials.empty', ['title' => 'No tenants found', 'description' => 'Try different filters.', 'icon' => 'building'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <caption class="visually-hidden">Registered tenant workspaces</caption>
                    <thead><tr><th scope="col">Workspace</th><th scope="col">Domains</th><th scope="col">Administrator</th><th scope="col">Status</th><th class="text-end" scope="col">Actions</th></tr></thead>
                    <tbody>
                        <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tenant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <th scope="row">
                                    <div class="app-table-person d-flex align-items-center gap-3">
                                        <?php if($tenant->logo_url): ?>
                                            <img class="app-table-avatar rounded-circle object-fit-cover" src="<?php echo e($tenant->logo_url); ?>" width="40" height="40" alt="">
                                        <?php else: ?>
                                            <span class="app-table-avatar" aria-hidden="true"><i class="fa-solid fa-building"></i></span>
                                        <?php endif; ?>
                                        <div>
                                            <div class="fw-semibold"><?php echo e($tenant->name); ?></div>
                                            <div class="small text-body-secondary fw-normal"><?php echo e($tenant->slug); ?></div>
                                            <?php if($tenant->has_branches): ?>
                                                <span class="app-tag">Has branches</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </th>
                                <td><?php echo $__env->make('core::partials.tags', ['labels' => $tenant->domains->pluck('domain'), 'empty' => ''], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></td>
                                <td class="small">
                                    <div><?php echo e($tenant->owner_email); ?></div>
                                    <?php if($tenant->phone): ?>
                                        <div class="text-body-secondary"><?php echo e($tenant->phone); ?></div>
                                    <?php endif; ?>
                                </td>
                                <td><span class="app-status app-status-<?php echo e($tenant->status); ?>"><?php echo e($filterOptions['status'][$tenant->status] ?? $tenant->status); ?></span></td>
                                <td>
                                    <div class="d-flex justify-content-end align-items-center gap-2 text-nowrap">
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('enter', $tenant)): ?>
                                            <?php if($tenant->domains->isNotEmpty()): ?>
                                                <form action="<?php echo e(route('core.tenants.enter', $tenant)); ?>" target="_blank" method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <button class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-2" type="submit" aria-label="Open <?php echo e($tenant->name); ?> dashboard" title="Open dashboard"><i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i><span>Dashboard</span></button>
                                                </form>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php echo $__env->make('core::partials.row-actions', [
                                            'model' => $tenant,
                                            'routePrefix' => 'core.tenants',
                                            'deleteConfirmation' => 'Permanently delete '.$tenant->name.'? '
                                                .'Registration, domains, uploads, subscriptions, invoices and payments will be removed. '
                                                .'The database will be deleted only when access, ownership and deletion permission are verified. '
                                                .'This cannot be undone.',
                                        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        <?php echo $__env->make('core::partials.pagination', ['paginator' => $records, 'filters' => $filters], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\app\Providers/../../resources/views/tenants/index.blade.php ENDPATH**/ ?>