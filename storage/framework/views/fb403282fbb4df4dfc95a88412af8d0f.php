<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $recentTitle = $central ? 'Recent tenants' : 'Recently added users';
        $recentDescription = $central ? 'The newest workspaces on your platform.' : 'The newest accounts in your workspace.';
        $recentRoutePrefix = $central ? 'core.tenants' : $accountRoutePrefix;
    ?>
    <header class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
        <div>
            
            <h1 class="h4 fw-bold mb-1">Dashboard</h1>
            
        </div>
        <div class="d-flex flex-wrap align-items-center gap-3">
            <time class="small text-body-secondary" datetime="<?php echo e($today->toDateString()); ?>">
                <i class="fa-solid fa-calendar-days me-2" aria-hidden="true"></i><?php echo e($today->format('l, d F Y')); ?>

            </time>
            <?php if(! $central && isset($permissions['module-dashboard.view'])): ?>
                <a class="btn btn-primary btn-sm" href="<?php echo e(route('tenantcore.module-dashboard.index')); ?>">
                    <i class="fa-solid fa-layer-group me-2" aria-hidden="true"></i>Modules
                </a>
            <?php endif; ?>
        </div>
    </header>

    <section class="mb-3" aria-labelledby="quick-actions-heading">
        <?php
            $quickActions = [
                'tenants.create' => [
                    'label' => 'Create tenant',
                    'icon' => 'building',
                    'url' => route('core.tenants.create'),
                    'tone' => 'primary',
                    'available' => $central,
                ],
                'accounts.create' => [
                    'label' => $central ? 'Add administrator' : 'Add user',
                    'icon' => 'user-plus',
                    'url' => route($accountRoutePrefix.'.create'),
                    'tone' => 'success',
                    'available' => true,
                ],
                'roles.create' => [
                    'label' => 'Create role',
                    'icon' => 'shield-halved',
                    'url' => route('core.roles.create'),
                    'tone' => 'warning',
                    'available' => true,
                ],
                'tenants.view' => [
                    'label' => 'Tenants',
                    'icon' => 'building',
                    'url' => route('core.tenants.index'),
                    'tone' => 'danger',
                    'available' => $central,
                ],
                'accounts.view' => [
                    'label' => $central ? 'Administrators' : 'Users',
                    'icon' => 'users',
                    'url' => route($accountRoutePrefix.'.index'),
                    'tone' => 'primary',
                    'available' => true,
                ],
                'roles.view' => [
                    'label' => 'Roles',
                    'icon' => 'shield-halved',
                    'url' => route('core.roles.index'),
                    'tone' => 'warning',
                    'available' => true,
                ],
                'permissions.view' => [
                    'label' => 'Permissions',
                    'icon' => 'key',
                    'url' => route('core.permissions.index'),
                    'tone' => 'info',
                    'available' => true,
                ],
                'audit-logs.view' => [
                    'label' => 'Audit logs',
                    'icon' => 'clock-rotate-left',
                    'url' => route('tenantcore.audit-logs.index'),
                    'tone' => 'primary',
                    'available' => ! $central,
                ],
                'branches.view' => [
                    'label' => 'Branches',
                    'icon' => 'building',
                    'url' => route('tenantcore.branches.index'),
                    'tone' => 'success',
                    'available' => ! $central && $hasBranches,
                ],
                'billing.view' => [
                    'label' => 'Billing',
                    'icon' => 'file-invoice-dollar',
                    'url' => route('tenantcore.billing.index'),
                    'tone' => 'danger',
                    'available' => ! $central,
                ],
                'settings.view' => [
                    'label' => 'Settings',
                    'icon' => 'gear',
                    'url' => route($central ? 'core.settings.index' : 'tenantcore.settings.index'),
                    'tone' => 'secondary',
                    'available' => true,
                ],
            ];
            $quickActions = array_filter(
                array_intersect_key($quickActions, $permissions),
                static fn (array $action): bool => $action['available'],
            );
        ?>
        <h2 class="h6 fw-semibold mb-2" id="quick-actions-heading">Quick actions</h2>
        <div class="row row-cols-2 row-cols-sm-3 row-cols-lg-4 row-cols-xl-6 justify-content-center g-3">
            <?php $__empty_1 = true; $__currentLoopData = $quickActions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="col">
                    <a class="btn btn-<?php echo e($action['tone']); ?> bg-gradient d-flex flex-column align-items-center justify-content-center gap-2 py-3 h-100"
                        href="<?php echo e($action['url']); ?>">
                        <i class="fa-solid fa-<?php echo e($action['icon']); ?> fs-4" aria-hidden="true"></i>
                        <span class="fw-semibold"><?php echo e($action['label']); ?></span>
                    </a>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-12 w-100">
                    <p class="app-card small text-body-secondary p-3 mb-0">No quick actions are available for your account.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="row row-cols-1 row-cols-sm-2 row-cols-xl-4 g-3 mb-3" aria-label="Workspace overview">
        <?php
            $metrics = [
                [
                    'label' => 'Tenants',
                    'value' => $tenantCount,
                    'caption' => 'Registered workspaces',
                    'icon' => 'building',
                    'tone' => 'primary',
                    'url' => route('core.tenants.index'),
                ],
                [
                    'label' => $central ? 'Administrators' : 'Users',
                    'value' => $accountCount,
                    'caption' => $central ? 'Platform accounts' : 'Workspace accounts',
                    'icon' => 'users',
                    'tone' => 'success',
                    'url' => route($accountRoutePrefix.'.index'),
                ],
                [
                    'label' => 'Roles',
                    'value' => $roleCount,
                    'caption' => 'Access roles in this workspace',
                    'icon' => 'shield-halved',
                    'tone' => 'warning',
                    'url' => route('core.roles.index'),
                ],
                [
                    'label' => 'Your permissions',
                    'value' => $permissionCount,
                    'caption' => 'Assigned access permissions',
                    'icon' => 'key',
                    'tone' => 'info',
                    'url' => null,
                ],
            ];
            $metrics = array_filter($metrics, static fn (array $metric): bool => $metric['value'] !== null);
        ?>
        <?php $__currentLoopData = $metrics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $metric): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col flex-grow-1">
                <article class="card d-flex flex-row align-items-center gap-3 bg-<?php echo e($metric['tone']); ?>-subtle text-<?php echo e($metric['tone']); ?>-emphasis border-0 rounded-3 h-100 p-3">
                    <span class="d-inline-flex flex-shrink-0 align-items-center justify-content-center text-bg-<?php echo e($metric['tone']); ?> rounded-circle p-3">
                        <i class="fa-solid fa-<?php echo e($metric['icon']); ?> fs-5" aria-hidden="true"></i>
                    </span>
                    <div class="flex-grow-1">
                        <p class="small fw-semibold mb-1"><?php echo e($metric['label']); ?></p>
                        <p class="h3 fw-bold mb-1"><?php echo e(number_format($metric['value'])); ?></p>
                        <p class="small text-body-secondary mb-0"><?php echo e($metric['caption']); ?></p>
                    </div>
                    <?php if($metric['url']): ?>
                        <a class="text-reset stretched-link align-self-start" href="<?php echo e($metric['url']); ?>" aria-label="View <?php echo e(strtolower($metric['label'])); ?>">
                            <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i>
                        </a>
                    <?php endif; ?>
                </article>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </section>

    <div class="row g-3 align-items-start">
        <div class="col-lg-8">
            <section class="app-card overflow-hidden" aria-labelledby="recent-heading">
                <header class="d-flex flex-wrap align-items-center justify-content-between gap-3 p-3 border-bottom bg-primary-subtle">
                    <div>
                        <h2 class="h6 fw-semibold mb-1" id="recent-heading"><?php echo e($recentTitle); ?></h2>
                        <p class="small text-body-secondary mb-0"><?php echo e($recentDescription); ?></p>
                    </div>
                    <?php if($recentRecords !== null): ?>
                        <a class="btn btn-primary btn-sm" href="<?php echo e(route($recentRoutePrefix.'.index')); ?>">
                            View all <i class="fa-solid fa-arrow-right ms-1" aria-hidden="true"></i>
                        </a>
                    <?php endif; ?>
                </header>

                <?php if($recentRecords === null): ?>
                    <div class="text-center p-4">
                        <i class="fa-solid fa-lock fs-3 text-body-secondary mb-3" aria-hidden="true"></i>
                        <h3 class="h6">This list is restricted</h3>
                        <p class="small text-body-secondary mb-0">Your account does not have access to this list.</p>
                    </div>
                <?php elseif($recentRecords->isEmpty()): ?>
                    <div class="text-center p-4">
                        <i class="fa-solid fa-<?php echo e($central ? 'building' : 'users'); ?> fs-3 text-body-secondary mb-3" aria-hidden="true"></i>
                        <h3 class="h6"><?php echo e($central ? 'No tenants yet' : 'No users yet'); ?></h3>
                        <p class="small text-body-secondary mb-0">New records will appear here when they are added.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive overflow-y-hidden">
                        <table class="table table-hover align-middle mb-0">
                            <caption class="visually-hidden"><?php echo e($recentTitle); ?>: five most recently added records</caption>
                            <thead class="table-light">
                                <tr>
                                    <th class="px-3 py-2" scope="col"><?php echo e($central ? 'Workspace' : 'User'); ?></th>
                                    <?php if($central): ?><th class="px-3 py-2" scope="col">Status</th><?php endif; ?>
                                    <th class="px-3 py-2" scope="col">Added</th>
                                    <th class="text-end px-3 py-2" scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $recentRecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <th class="px-3 py-3" scope="row">
                                            <div class="fw-semibold text-break"><?php echo e($record->name); ?></div>
                                            <div class="small text-body-secondary fw-normal text-break"><?php echo e($central ? $record->slug : $record->email); ?></div>
                                        </th>
                                        <?php if($central): ?>
                                            <td class="px-3 py-3">
                                                <span class="app-status app-status-<?php echo e($record->status); ?>"><?php echo e($recentStatusOptions[$record->status] ?? $record->status); ?></span>
                                            </td>
                                        <?php endif; ?>
                                        <td class="small text-nowrap px-3 py-3">
                                            <?php if($record->created_at): ?>
                                                <time datetime="<?php echo e($record->created_at->toDateString()); ?>"><?php echo e($record->created_at->format('d M Y')); ?></time>
                                            <?php else: ?>
                                                —
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end px-3 py-3">
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $record)): ?>
                                                <a class="btn btn-outline-primary btn-sm" href="<?php echo e(route($recentRoutePrefix.'.edit', $record)); ?>" aria-label="Edit <?php echo e($record->name); ?>">
                                                    <i class="fa-solid fa-pen" aria-hidden="true"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </section>
        </div>

        <aside class="col-lg-4">
            <section class="app-card overflow-hidden" aria-labelledby="your-account-heading">
                <header class="p-3 border-bottom bg-body-tertiary">
                    <h2 class="h6 fw-semibold mb-0" id="your-account-heading">
                        <i class="fa-solid fa-user text-primary me-2" aria-hidden="true"></i>Your account
                    </h2>
                </header>
                <div class="p-3">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="flex-shrink-0 bg-primary-subtle text-primary-emphasis rounded-3 px-3 py-2 fs-5 fw-bold" aria-hidden="true"><?php echo e($accountInitials); ?></span>
                        <div class="overflow-hidden">
                            <p class="fw-semibold mb-1 text-break"><?php echo e($actor->name); ?></p>
                            <p class="small text-body-secondary mb-0 text-break"><?php echo e($actor->email); ?></p>
                        </div>
                    </div>
                    <dl class="row gy-2 small mb-0">
                        <dt class="col-4 fw-normal text-body-secondary">Workspace</dt>
                        <dd class="col-8 text-break mb-0"><?php echo e($contextName); ?></dd>
                        <dt class="col-4 fw-normal text-body-secondary">Role</dt>
                        <dd class="col-8 mb-0"><?php echo $__env->make('core::partials.tags', ['labels' => $accountRoles, 'empty' => 'No roles assigned'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></dd>
                    </dl>
                    <?php if($accountEditUrl): ?>
                        <a class="btn btn-outline-primary btn-sm w-100 mt-3" href="<?php echo e($accountEditUrl); ?>">
                            <i class="fa-solid fa-user-pen me-2" aria-hidden="true"></i>Manage your account
                        </a>
                    <?php endif; ?>
                </div>
            </section>
        </aside>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\app\Providers/../../resources/views/home.blade.php ENDPATH**/ ?>