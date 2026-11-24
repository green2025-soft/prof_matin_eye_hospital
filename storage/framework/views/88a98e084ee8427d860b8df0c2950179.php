

<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', [
        'title' => $pageTitle,
        'description' => 'Review your subscription, invoices, and payment submissions.',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php if($currentSubscription): ?>
        <section class="app-card p-3 p-md-4 mb-4" aria-labelledby="subscription-heading">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
                <div>
                    <div class="small text-body-secondary mb-1">Current plan</div>
                    <h2 class="h5 mb-0" id="subscription-heading"><?php echo e($currentSubscription['planName']); ?></h2>
                </div>
                <span class="app-tag"><?php echo e($currentSubscription['status']); ?></span>
            </div>
            <dl class="row gy-2 mb-0">
                <dt class="col-sm-4 col-lg-2">Price</dt>
                <dd class="col-sm-8 col-lg-4">
                    <?php echo e($currentSubscription['currency']); ?> <?php echo e($currentSubscription['price']); ?>

                    <span class="text-body-secondary">/ <?php echo e($currentSubscription['interval']); ?></span>
                </dd>
                <dt class="col-sm-4 col-lg-2">Account limit</dt>
                <dd class="col-sm-8 col-lg-4"><?php echo e($currentSubscription['userLimit'] ?? 'Unlimited'); ?></dd>
                <dt class="col-sm-4 col-lg-2">Current period</dt>
                <dd class="col-sm-8 col-lg-10">
                    <?php echo e($currentSubscription['periodStart'] ?? 'Not started'); ?>

                    <?php if($currentSubscription['periodEnd']): ?>
                        to <?php echo e($currentSubscription['periodEnd']); ?>

                    <?php endif; ?>
                </dd>
                <?php if($currentSubscription['trialEnd']): ?>
                    <dt class="col-sm-4 col-lg-2">Trial ends</dt>
                    <dd class="col-sm-8 col-lg-10"><?php echo e($currentSubscription['trialEnd']); ?></dd>
                <?php endif; ?>
                <?php if($currentSubscription['features'] !== []): ?>
                    <dt class="col-sm-4 col-lg-2">Included features</dt>
                    <dd class="col-sm-8 col-lg-10">
                        <div class="d-flex flex-wrap gap-2">
                            <?php $__currentLoopData = $currentSubscription['features']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="app-tag"><?php echo e($feature); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </dd>
                <?php endif; ?>
            </dl>
            <?php if($currentSubscription['cancelAtPeriodEnd']): ?>
                <p class="small text-body-secondary border-top mt-3 pt-3 mb-0">
                    This subscription is scheduled to end after the current billing period.
                </p>
            <?php endif; ?>
        </section>
    <?php else: ?>
        <div class="alert alert-secondary" role="status">
            A subscription has not been assigned to this workspace. Contact the platform administrator.
        </div>
    <?php endif; ?>

    <?php echo $__env->make('core::invoices.summary', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <section class="app-card overflow-hidden" aria-labelledby="billing-invoices-heading">
        <div class="app-card-heading p-3 border-bottom">
            <h2 class="h6 mb-0" id="billing-invoices-heading">Invoices</h2>
        </div>
        <form class="p-3 border-bottom" action="<?php echo e(route($routePrefix.'.index')); ?>" method="GET">
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
                    <?php echo $__env->make('core::partials.filter-actions', ['routePrefix' => $routePrefix], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>
        </form>
        <?php if($records->isEmpty()): ?>
            <?php echo $__env->make('core::partials.empty', [
                'title' => 'No invoices found',
                'description' => 'Issued invoices appear here. Try changing the filters.',
                'icon' => 'file-invoice',
            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php else: ?>
            <?php echo $__env->make('core::invoices.table', [
                'records' => $records,
                'statusOptions' => $filterOptions['status'],
                'invoiceRoutePrefix' => $invoiceRoutePrefix,
                'showWorkspace' => false,
            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endif; ?>
        <?php echo $__env->make('core::partials.pagination', ['paginator' => $records, 'filters' => $filters], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </section>

    <?php echo $__env->make('core::invoices.manual-submissions', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\resources\views\billing\index.blade.php ENDPATH**/ ?>