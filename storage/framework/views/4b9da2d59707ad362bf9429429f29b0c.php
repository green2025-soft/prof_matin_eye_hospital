

<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <header class="app-selection-intro d-flex align-items-start align-items-sm-center justify-content-between gap-3 mb-4">
        <div>
            <h1>Choose your branch</h1>
            <p class="mb-0">Select a branch to continue working.</p>
        </div>
        <span class="app-selection-count d-inline-flex flex-shrink-0 align-items-center">
            <strong><?php echo e(number_format($records->total())); ?></strong><span><?php echo e($records->total() === 1 ? 'branch' : 'branches'); ?></span>
        </span>
    </header>

    <form class="app-selection-search d-flex flex-wrap flex-sm-nowrap align-items-start"
        action="<?php echo e(route($routePrefix.'.index')); ?>" method="GET" aria-label="Find a branch">
        <div class="app-selection-search-field">
            <label class="visually-hidden" for="branch-search">Search branches</label>
            <div class="app-input-wrap app-search-field">
                <i class="fa-solid fa-magnifying-glass app-search-icon" aria-hidden="true"></i>
                <input class="<?php echo e(inputClass('search')); ?>" id="branch-search" name="search" type="search" value="<?php echo e(getValue('search', null, $filters['search'] ?? '')); ?>" maxlength="80" placeholder="Search by branch name..." <?php echo e(inputAttrs('search', 'branch-search')); ?>>
            </div>
            <?php $__errorArgs = ['search'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="branch-search-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <?php echo $__env->make('core::partials.filter-actions', ['routePrefix' => $routePrefix], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>

    <?php if($records->isEmpty()): ?>
        <section class="app-card">
            <?php echo $__env->make('core::partials.empty', ['title' => 'No matching branches', 'description' => 'Try another branch name or clear the filters.', 'icon' => 'location-dot'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </section>
    <?php else: ?>
        <div class="app-selection-grid row row-cols-1 row-cols-sm-2 row-cols-xl-3">
            <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php ($isActive = $activeBranchId !== null && (string) $activeBranchId === (string) $branch->id); ?>
                <form class="col d-flex" action="<?php echo e(route('tenantcore.branch-dashboard.store', $branch->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button class="app-selection-card card w-100 h-100 overflow-hidden p-0 text-start text-decoration-none<?php echo e($isActive ? ' active' : ''); ?>"
                        type="submit" aria-label="Open <?php echo e($branch->name); ?> workspace" aria-describedby="branch-address-<?php echo e($branch->id); ?>"
                        <?php if($isActive): ?> aria-current="true" <?php endif; ?>>
                        <span class="d-flex flex-column flex-grow-1 p-4">
                            <span class="app-selection-card-head d-flex align-items-center">
                                <span class="app-selection-mark d-inline-flex flex-shrink-0 align-items-center justify-content-center overflow-hidden">
                                    <?php if($branch->logo_path): ?>
                                        <img class="mw-100 object-fit-contain" src="<?php echo e($branch->logo_url); ?>" width="44" height="44" alt="">
                                    <?php else: ?>
                                        <i class="fa-solid fa-building" aria-hidden="true"></i>
                                    <?php endif; ?>
                                </span>
                                <span class="app-selection-name"><?php echo e($branch->name); ?></span>
                                <?php if($isActive): ?>
                                    <span class="app-selection-active d-inline-flex flex-shrink-0 align-items-center justify-content-center ms-auto rounded-circle" title="Active branch">
                                        <i class="fa-solid fa-check" aria-hidden="true"></i><span class="visually-hidden">Active branch</span>
                                    </span>
                                <?php endif; ?>
                            </span>
                            <span class="app-selection-description" id="branch-address-<?php echo e($branch->id); ?>"><?php echo e($branch->address); ?></span>
                            <?php if($branch->mobile_no || $branch->telephone_no || $branch->email): ?>
                                <span class="app-selection-contacts d-flex flex-column gap-2 mt-3">
                                    <?php if($branch->mobile_no || $branch->telephone_no): ?>
                                        <span class="d-flex align-items-baseline">
                                            <i class="fa-solid fa-phone flex-shrink-0 text-center" aria-hidden="true"></i>
                                            <span><?php echo e($branch->mobile_no ?: $branch->telephone_no); ?><?php if($branch->mobile_no && $branch->telephone_no): ?> &middot; <?php echo e($branch->telephone_no); ?><?php endif; ?></span>
                                        </span>
                                    <?php endif; ?>
                                    <?php if($branch->email): ?>
                                        <span class="d-flex align-items-baseline">
                                            <i class="fa-solid fa-envelope flex-shrink-0 text-center" aria-hidden="true"></i><span><?php echo e($branch->email); ?></span>
                                        </span>
                                    <?php endif; ?>
                                </span>
                            <?php endif; ?>
                        </span>
                        <span class="app-selection-open d-flex align-items-center justify-content-between gap-3 px-4">
                            <span>Open</span><i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                        </span>
                    </button>
                </form>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>

    <div class="app-card app-selection-pagination mt-4 overflow-hidden">
        <?php echo $__env->make('core::partials.pagination', ['paginator' => $records, 'filters' => $filters], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules/TenantCore\resources/views/branch-dashboard/index.blade.php ENDPATH**/ ?>