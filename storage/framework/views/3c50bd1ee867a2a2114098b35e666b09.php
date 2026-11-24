

<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle, 'description' => 'Manage role names. Assign their access separately in Permissions.', 'create' => ['model' => resourceModel('core.roles.create'), 'url' => route('core.roles.create'), 'label' => 'New role']], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <section class="app-card overflow-hidden">
        <div class="app-card-heading d-flex align-items-center gap-2 px-3 px-md-4 py-3 border-bottom"><i class="fa-solid fa-shield-halved text-body-secondary" aria-hidden="true"></i><h2 class="h6 mb-0">Role directory</h2></div>
        <form class="px-3 px-md-4 py-3 border-bottom" action="<?php echo e(route('core.roles.index')); ?>" method="GET" aria-label="Filter roles">
            <div class="row g-2 align-items-end">
                <div class="col-md-8 col-xl-7">
                    <label class="form-label small" for="search">Search roles</label>
                    <div class="app-input-wrap app-search-field">
                        <i class="fa-solid fa-magnifying-glass app-search-icon" aria-hidden="true"></i>
                        <input class="<?php echo e(inputClass('search')); ?>" id="search" name="search" type="search" value="<?php echo e(getValue('search', null, $filters['search'] ?? '')); ?>" placeholder="Role name starts with…" maxlength="80" <?php echo e(inputAttrs('search')); ?>>
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
                <div class="col-md-4 col-xl-5"><?php echo $__env->make('core::partials.filter-actions', ['routePrefix' => 'core.roles'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
            </div>
        </form>
        <?php if($records->isEmpty()): ?>
            <?php echo $__env->make('core::partials.empty', ['title' => 'No roles found', 'description' => 'Try a different role name.', 'icon' => 'key'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <caption class="visually-hidden">Roles and their creation dates</caption>
                    <thead><tr><th scope="col">Role</th><th scope="col">Created</th><th class="text-end" scope="col">Actions</th></tr></thead>
                    <tbody>
                        <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <th scope="row">
                                    <div class="app-table-person d-flex align-items-center gap-3">
                                        <span class="app-table-avatar" aria-hidden="true"><i class="fa-solid fa-shield-halved"></i></span>
                                        <div class="fw-semibold"><?php echo e($role->name); ?></div>
                                    </div>
                                </th>
                                <td class="small text-body-secondary text-nowrap"><?php echo e($role->created_at?->format('d M Y') ?? '—'); ?></td>
                                <td>
                                    <div class="d-flex justify-content-end gap-2">
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewPermissions', $role)): ?>
                                            <a class="btn btn-outline-secondary btn-sm app-action-button" href="<?php echo e(route('core.permissions.index', ['role' => $role->id])); ?>" aria-label="Permissions for <?php echo e($role->name); ?>" title="Permissions for <?php echo e($role->name); ?>"><i class="fa-solid fa-key" aria-hidden="true"></i></a>
                                        <?php endif; ?>
                                        <?php echo $__env->make('core::partials.row-actions', ['model' => $role, 'routePrefix' => 'core.roles'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
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

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\resources\views\roles\index.blade.php ENDPATH**/ ?>