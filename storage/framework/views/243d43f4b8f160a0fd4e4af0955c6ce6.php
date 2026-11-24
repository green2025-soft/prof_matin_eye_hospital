<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle, 'description' => 'Review account activity and record changes.'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <section class="app-card overflow-hidden">
        <div class="app-card-heading d-flex align-items-center gap-2 px-3 px-md-4 py-3 border-bottom">
            <i class="fa-solid fa-clock-rotate-left text-body-secondary" aria-hidden="true"></i>
            <h2 class="h6 mb-0">Audit Logs</h2>
        </div>
        <form class="px-3 px-md-4 py-3 border-bottom" action="<?php echo e(route($routePrefix.'.index')); ?>" method="GET" aria-label="Filter audit logs">
            <div class="row g-3 align-items-end">
                <div class="col-lg-6">
                    <label class="form-label small" for="search">Search activity</label>
                    <div class="app-input-wrap app-search-field">
                        <i class="fa-solid fa-magnifying-glass app-search-icon" aria-hidden="true"></i>
                        <input class="<?php echo e(inputClass('search')); ?>" id="search" name="search" type="search" value="<?php echo e(getValue('search', null, $filters['search'] ?? '')); ?>" maxlength="80" placeholder="Account or record name starts with..." <?php echo e(inputAttrs('search')); ?>>
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
                    <label class="form-label small" for="event">Action</label>
                    <select class="<?php echo e(inputClass('event', 'form-select')); ?>" id="event" name="event" <?php echo e(inputAttrs('event')); ?>>
                        <option value="">All actions</option>
                        <?php $__currentLoopData = $eventOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value); ?>" <?php echo e(getSelected($value, 'event', null, $filters['event'] ?? '')); ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['event'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="event-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <?php if($hasBranches): ?>
                    <div class="col-sm-6 col-lg-3">
                        <label class="form-label small" for="branch_id">Branch</label>
                        <select class="<?php echo e(inputClass('branch_id', 'form-select')); ?>" id="branch_id" name="branch_id"
                            data-ajax-select data-url="<?php echo e(route('tenantcore.audit-logs.branches')); ?>"
                            data-placeholder="All branches" <?php echo e(inputAttrs('branch_id')); ?>>
                            <option value=""></option>
                            <?php if($selectedBranch !== null): ?>
                                <option value="<?php echo e($selectedBranch->id); ?>" selected><?php echo e($selectedBranch->name); ?></option>
                            <?php endif; ?>
                        </select>
                        <?php $__errorArgs = ['branch_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="branch_id-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                <?php endif; ?>
                <div class="col-sm-6 col-lg-4">
                    <label class="form-label small" for="date_from">From date</label>
                    <div class="input-group" data-datepicker>
                        <input class="<?php echo e(inputClass('date_from')); ?>" id="date_from" name="date_from" type="text"
                            inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD"
                            value="<?php echo e(getValue('date_from', null, $filters['date_from'] ?? '')); ?>"
                            data-input <?php echo e(inputAttrs('date_from')); ?>>
                        <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="date_from" aria-label="Choose from date" title="Choose from date">
                            <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
                        </button>
                        <button class="btn btn-outline-secondary" type="button" data-clear aria-controls="date_from" aria-label="Clear from date" title="Clear from date">
                            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                        </button>
                    </div>
                    <?php $__errorArgs = ['date_from'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="date_from-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <label class="form-label small" for="date_to">To date</label>
                    <div class="input-group" data-datepicker>
                        <input class="<?php echo e(inputClass('date_to')); ?>" id="date_to" name="date_to" type="text"
                            inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD"
                            value="<?php echo e(getValue('date_to', null, $filters['date_to'] ?? '')); ?>"
                            data-input <?php echo e(inputAttrs('date_to')); ?>>
                        <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="date_to" aria-label="Choose to date" title="Choose to date">
                            <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
                        </button>
                        <button class="btn btn-outline-secondary" type="button" data-clear aria-controls="date_to" aria-label="Clear to date" title="Clear to date">
                            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                        </button>
                    </div>
                    <?php $__errorArgs = ['date_to'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block" id="date_to-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-lg-4">
                    <?php echo $__env->make('core::partials.filter-actions', ['routePrefix' => $routePrefix], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>
        </form>

        <?php if($records->isEmpty()): ?>
            <?php echo $__env->make('core::partials.empty', ['title' => 'No audit logs found', 'description' => 'Try different filters or clear the filters to see all activity.', 'icon' => 'clock-rotate-left'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <caption class="visually-hidden">Account activity and record changes</caption>
                    <thead>
                        <tr>
                            <th scope="col">Time</th>
                            <th scope="col">Actor</th>
                            <th scope="col">Action</th>
                            <th scope="col">Resource</th>
                            <th scope="col">Record</th>
                            <?php if($hasBranches): ?><th scope="col">Branch</th><?php endif; ?>
                            <th class="text-end" scope="col">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $auditLog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="small text-nowrap">
                                    <time datetime="<?php echo e($auditLog->created_at?->toIso8601String()); ?>"><?php echo e($auditLog->created_at?->format('d M Y H:i:s')); ?></time>
                                </td>
                                <td class="small text-break">
                                    <div><?php echo e($auditLog->actor_name ?? 'System'); ?></div>
                                    <?php if($auditLog->actor_id !== null): ?>
                                        <div class="text-body-secondary"><?php echo e(\Illuminate\Support\Str::headline(\Illuminate\Support\Str::singular($auditLog->actor_type))); ?> #<?php echo e($auditLog->actor_id); ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="small"><?php echo e($eventOptions[$auditLog->event] ?? $auditLog->event); ?></td>
                                <td class="small text-break"><?php echo e($auditLog->resource); ?></td>
                                <td class="small text-break">
                                    <div><?php echo e($auditLog->record_name ?? '-'); ?></div>
                                    <?php if($auditLog->record_id !== null): ?>
                                        <div class="text-body-secondary">#<?php echo e($auditLog->record_id); ?></div>
                                    <?php endif; ?>
                                </td>
                                <?php if($hasBranches): ?>
                                    <td class="small text-break">
                                        <?php if($auditLog->branch_id !== null && (int) $auditLog->branch_id !== (int) $defaultBranchId): ?>
                                            <div><a href="<?php echo e(route($routePrefix.'.index', ['branch_id' => $auditLog->branch_id])); ?>"><?php echo e($auditLog->branch_name ?? 'Branch'); ?></a></div>
                                            <div class="text-body-secondary">#<?php echo e($auditLog->branch_id); ?></div>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                <?php endif; ?>
                                <td class="text-end">
                                    <a class="btn btn-outline-secondary btn-sm text-nowrap" href="<?php echo e(route($routePrefix.'.show', $auditLog)); ?>" aria-label="View audit log #<?php echo e($auditLog->id); ?>"><i class="fa-solid fa-eye me-2" aria-hidden="true"></i>Details</a>
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

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\TenantCore\resources\views\audit-logs\index.blade.php ENDPATH**/ ?>