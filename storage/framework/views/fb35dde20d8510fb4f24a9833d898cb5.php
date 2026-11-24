<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', [
        'title' => $pageTitle,
        'description' => 'Record income by head, date, and payment type.',
        'create' => ['model' => resourceModel($routePrefix.'.create'), 'url' => route($routePrefix.'.create'), 'label' => 'New income'],
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <section class="app-card overflow-hidden">
        <div class="app-card-heading d-flex align-items-center gap-2 px-3 px-md-4 py-3 border-bottom">
            <i class="fa-solid fa-arrow-trend-up text-body-secondary" aria-hidden="true"></i>
            <h2 class="h6 mb-0">Income</h2>
        </div>
        <form class="px-3 px-md-4 py-3 border-bottom" action="<?php echo e(route($routePrefix.'.index')); ?>" method="GET" aria-label="Filter income">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-md-6 col-xl-4">
                    <label class="form-label small" for="search">Search income</label>
                    <div class="app-input-wrap app-search-field">
                        <i class="fa-solid fa-magnifying-glass app-search-icon" aria-hidden="true"></i>
                        <input class="<?php echo e(inputClass('search')); ?>" id="search" name="search" type="search"
                            value="<?php echo e(getValue('search', null, $filters['search'] ?? '')); ?>" maxlength="80"
                            placeholder="ID, date, or note starts with..." <?php echo e(inputAttrs('search')); ?>>
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
                <div class="col-12 col-md-6 col-xl-4">
                    <label class="form-label small" for="filter_head_id">Income head</label>
                    <select class="<?php echo e(inputClass('filter_head_id', 'form-select')); ?>" id="filter_head_id" name="filter_head_id"
                        data-ajax-select data-url="<?php echo e(route('tenantcore.income-heads.options')); ?>"
                        data-placeholder="All income heads"
                        <?php echo e(inputAttrs('filter_head_id')); ?>>
                        <option value=""></option>
                        <?php if($selectedHead !== null): ?>
                            <option value="<?php echo e($selectedHead->id); ?>" selected><?php echo e($selectedHead->name); ?></option>
                        <?php endif; ?>
                    </select>
                    <?php $__errorArgs = ['filter_head_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="filter_head_id-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-12 col-xl-4">
                    <?php echo $__env->make('core::partials.filter-actions', ['routePrefix' => $routePrefix], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>
        </form>

        <?php if($records->isEmpty()): ?>
            <?php echo $__env->make('core::partials.empty', ['title' => 'No income found', 'description' => 'Create an income entry or change the filters.', 'icon' => 'arrow-trend-up'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <caption class="visually-hidden">Income dates, heads, amounts, payment types, and notes</caption>
                    <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Income heads</th>
                            <th class="text-end" scope="col">Amount</th>
                            <th scope="col">Payment type</th>
                            <th scope="col">Note</th>
                            <th class="text-end" scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $income): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <th class="fw-semibold text-nowrap" scope="row"><?php echo e($income->entry_date->toDateString()); ?></th>
                                <td class="text-break"><?php echo e($income->items->pluck('head.name')->implode(', ')); ?></td>
                                <td class="text-end text-nowrap"><?php echo e($income->amount); ?></td>
                                <td class="small text-break"><?php echo e($income->paymentType?->name); ?></td>
                                <td class="small text-break"><?php echo e($income->note ?? '-'); ?></td>
                                <td><?php echo $__env->make('core::partials.row-actions', ['model' => $income, 'routePrefix' => $routePrefix, 'actionLabel' => 'income entry'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <?php echo $__env->make('core::partials.pagination', ['paginator' => $records, 'filters' => $filters], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\TenantCore\resources\views\incomes\index.blade.php ENDPATH**/ ?>