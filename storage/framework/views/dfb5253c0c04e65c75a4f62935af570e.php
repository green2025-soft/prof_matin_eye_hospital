

<?php $__env->startSection('title', $isTenantUser ? 'Users' : $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', [
        'title' => $isTenantUser ? 'Users' : $pageTitle,
        'description' => $isTenantUser ? 'Manage users and the access they have.' : 'Manage accounts and the access they have.',
        'create' => ['model' => $accountModel, 'url' => route($routePrefix.'.create'), 'label' => $isTenantUser ? 'New user' : 'New account'],
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <section class="app-card overflow-hidden">
        <div class="app-card-heading d-flex align-items-center gap-2 px-3 px-md-4 py-3 border-bottom"><i class="fa-solid fa-users text-body-secondary" aria-hidden="true"></i><h2 class="h6 mb-0"><?php echo e($isTenantUser ? 'User directory' : 'Account directory'); ?></h2></div>
        <form class="px-3 px-md-4 py-3 border-bottom" action="<?php echo e(route($routePrefix.'.index')); ?>" method="GET" aria-label="<?php echo e($isTenantUser ? 'Filter users' : 'Filter accounts'); ?>">
            <div class="row g-2 align-items-end">
                <div class="col-md-8 col-xl-7">
                    <label class="form-label small" for="search"><?php echo e($isTenantUser ? 'Search users' : 'Search accounts'); ?></label>
                    <div class="app-input-wrap app-search-field">
                        <i class="fa-solid fa-magnifying-glass app-search-icon" aria-hidden="true"></i>
                        <input class="<?php echo e(inputClass('search')); ?>" id="search" name="search" type="search" value="<?php echo e(getValue('search', null, $filters['search'] ?? '')); ?>" placeholder="Name or email starts with…" maxlength="80" <?php echo e(inputAttrs('search')); ?>>
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
                <div class="col-md-4 col-xl-5"><?php echo $__env->make('core::partials.filter-actions', ['routePrefix' => $routePrefix], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
            </div>
        </form>
        <?php if($records->isEmpty()): ?>
            <?php echo $__env->make('core::partials.empty', ['title' => $isTenantUser ? 'No users found' : 'No accounts found', 'description' => 'Try another search or clear the filters.', 'icon' => 'users'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <caption class="visually-hidden"><?php echo e($hasBranches ? 'Users, assigned roles, and branch access' : 'User accounts and assigned roles'); ?></caption>
                    <thead>
                        <tr>
                            <th scope="col"><?php echo e($isTenantUser ? 'User' : 'Account'); ?></th>
                            <th scope="col">Roles</th>
                            <?php if($hasBranches): ?>
                                <th scope="col">Branch</th>
                            <?php endif; ?>
                            <?php if($isTenantUser): ?>
                                <th scope="col">Redirect URL</th>
                            <?php endif; ?>
                            <th scope="col">Created</th>
                            <th class="text-end" scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <th scope="row">
                                    <div class="app-table-person d-flex align-items-center gap-3">
                                        <span class="app-table-avatar" aria-hidden="true"><?php echo e(mb_strtoupper(mb_substr($account->name, 0, 1))); ?></span>
                                        <div><div class="fw-semibold"><?php echo e($account->name); ?></div><div class="small text-body-secondary fw-normal"><?php echo e($account->email); ?></div></div>
                                    </div>
                                </th>
                                <td><?php echo $__env->make('core::partials.tags', ['labels' => $account->roles->pluck('name'), 'empty' => 'No roles'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></td>
                                <?php if($hasBranches): ?>
                                    <td class="small text-body-secondary"><?php echo e($account->branch?->name ?? 'None'); ?></td>
                                <?php endif; ?>
                                <?php if($isTenantUser): ?>
                                    <td class="small text-body-secondary text-break"><?php echo e($account->redirect_url ?: 'Dashboard'); ?></td>
                                <?php endif; ?>
                                <td class="small text-body-secondary text-nowrap"><?php echo e($account->created_at?->format('d M Y') ?? '—'); ?></td>
                                <td><?php echo $__env->make('core::partials.row-actions', ['model' => $account, 'routePrefix' => $routePrefix], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        <?php echo $__env->make('core::partials.pagination', ['paginator' => $records, 'filters' => $filters], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\resources\views\accounts\index.blade.php ENDPATH**/ ?>