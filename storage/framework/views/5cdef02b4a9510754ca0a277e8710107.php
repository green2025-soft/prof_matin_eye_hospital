

<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', [
        'title' => $pageTitle,
        'description' => 'Draft, issue, and review workspace invoices.',
        'create' => [
            'model' => resourceModel('core.invoices.create'),
            'url' => route('core.invoices.create'),
            'label' => 'New invoice',
        ],
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', resourceModel('core.invoices.create'))): ?>
        <div class="d-flex justify-content-end mb-3">
            <form action="<?php echo e(route('core.invoices.generate')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button class="btn btn-outline-primary" type="submit"><i class="fa-solid fa-file-invoice me-2" aria-hidden="true"></i>Generate</button>
            </form>
        </div>
    <?php endif; ?>

    <?php echo $__env->make('core::invoices.summary', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <section class="app-card overflow-hidden">
        <form class="p-3 border-bottom" action="<?php echo e(route('core.invoices.index')); ?>" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label" for="search">Invoice number</label>
                    <input class="<?php echo e(inputClass('search')); ?>" id="search" name="search" type="search"
                        value="<?php echo e(getValue('search', null, $filters['search'] ?? '')); ?>" placeholder="Number starts with..."
                        <?php echo e(inputAttrs('search')); ?>>
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
                <div class="col-md-3">
                    <label class="form-label" for="status">Status</label>
                    <select class="<?php echo e(inputClass('status', 'form-select')); ?>" id="status" name="status" <?php echo e(inputAttrs('status')); ?>>
                        <option value="">All statuses</option>
                        <?php $__currentLoopData = $filterOptions['status']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value); ?>" <?php echo e(getSelected($value, 'status', null, $filters['status'] ?? '')); ?>>
                                <?php echo e($label); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback d-block" id="status-error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-4">
                    <?php echo $__env->make('core::partials.filter-actions', ['routePrefix' => 'core.invoices'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>
        </form>

        <?php if($records->isEmpty()): ?>
            <?php echo $__env->make('core::partials.empty', [
                'title' => 'No invoices found',
                'description' => 'Create a draft invoice or change the filters.',
                'icon' => 'file-invoice',
            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php else: ?>
            <?php echo $__env->make('core::invoices.table', [
                'records' => $records,
                'statusOptions' => $filterOptions['status'],
                'invoiceRoutePrefix' => 'core.invoices',
                'showWorkspace' => true,
            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endif; ?>

        <?php echo $__env->make('core::partials.pagination', ['paginator' => $records, 'filters' => $filters], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </section>
    <?php echo $__env->make('core::invoices.manual-submissions', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\resources\views\invoices\index.blade.php ENDPATH**/ ?>