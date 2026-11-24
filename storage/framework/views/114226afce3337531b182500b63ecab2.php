<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', [
        'title' => $pageTitle,
        'description' => 'Manage branch locations and contact details.',
        'create' => ['model' => resourceModel($routePrefix.'.create'), 'url' => route($routePrefix.'.create'), 'label' => 'New branch'],
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <section class="app-card overflow-hidden">
        <div class="app-card-heading d-flex align-items-center gap-2 px-3 px-md-4 py-3 border-bottom">
            <i class="fa-solid fa-building text-body-secondary" aria-hidden="true"></i>
            <h2 class="h6 mb-0">Branch directory</h2>
        </div>
        <form class="px-3 px-md-4 py-3 border-bottom" action="<?php echo e(route($routePrefix.'.index')); ?>" method="GET" aria-label="Filter branches">
            <div class="row g-2 align-items-end">
                <div class="col-md-8 col-xl-7">
                    <label class="form-label small" for="search">Search branches</label>
                    <div class="app-input-wrap app-search-field">
                        <i class="fa-solid fa-magnifying-glass app-search-icon" aria-hidden="true"></i>
                        <input class="<?php echo e(inputClass('search')); ?>" id="search" name="search" type="search"
                            value="<?php echo e(getValue('search', null, $filters['search'] ?? '')); ?>"
                            placeholder="Branch name starts with..." maxlength="80" <?php echo e(inputAttrs('search')); ?>>
                    </div>
                    <?php $__errorArgs = ['search'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback d-block" id="search-error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-4 col-xl-5">
                    <?php echo $__env->make('core::partials.filter-actions', ['routePrefix' => $routePrefix], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>
        </form>
        <?php if($records->isEmpty()): ?>
            <?php echo $__env->make('core::partials.empty', ['title' => 'No branches found', 'description' => 'Try another branch name or clear the filters.', 'icon' => 'building'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <caption class="visually-hidden">Branches, addresses, and contact details</caption>
                    <thead>
                        <tr>
                            <th scope="col">Branch</th>
                            <th scope="col">Address</th>
                            <th scope="col">Contact</th>
                            <th class="text-end" scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <th scope="row">
                                    <div class="app-table-person d-flex align-items-center gap-3">
                                        <?php if($branch->logo_path): ?>
                                            <img class="app-brand-logo" src="<?php echo e($branch->logo_url); ?>" alt="" width="36" height="36">
                                        <?php else: ?>
                                            <span class="app-table-avatar" aria-hidden="true">
                                                <i class="fa-solid fa-building"></i>
                                            </span>
                                        <?php endif; ?>
                                        <div>
                                            <div class="fw-semibold"><?php echo e($branch->name); ?></div>
                                            <div class="small text-body-secondary fw-normal">
                                                <?php echo e($branch->created_at?->format('d M Y') ?? ''); ?>

                                            </div>
                                        </div>
                                    </div>
                                </th>
                                <td class="small text-body-secondary text-break"><?php echo e($branch->address); ?></td>
                                <td class="small text-break">
                                    <?php if($branch->mobile_no): ?>
                                        <div><span class="text-body-secondary">Mobile:</span> <?php echo e($branch->mobile_no); ?></div>
                                    <?php endif; ?>
                                    <?php if($branch->telephone_no): ?>
                                        <div><span class="text-body-secondary">Telephone:</span> <?php echo e($branch->telephone_no); ?></div>
                                    <?php endif; ?>
                                    <?php if($branch->email): ?>
                                        <div><?php echo e($branch->email); ?></div>
                                    <?php endif; ?>
                                    <?php if(! $branch->mobile_no && ! $branch->telephone_no && ! $branch->email): ?>
                                        <span class="text-body-secondary">Not provided</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php echo $__env->make('core::partials.row-actions', ['model' => $branch, 'routePrefix' => $routePrefix], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
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

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\TenantCore\resources\views\branches\index.blade.php ENDPATH**/ ?>