<?php $__env->startSection('title', $pageTitle); ?>

<?php $__env->startSection('content'); ?>
    <?php ($jsonOptions = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR); ?>

    <?php echo $__env->make('core::partials.heading', ['title' => $pageTitle, 'description' => 'Review the activity details and recorded changes.'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="mb-4">
        <a class="btn btn-outline-secondary btn-sm" href="<?php echo e(route($routePrefix.'.index')); ?>"><i class="fa-solid fa-arrow-left me-2" aria-hidden="true"></i>Audit Logs</a>
    </div>

    <section class="app-card p-3 p-md-4 mb-4" aria-labelledby="audit-log-details-heading">
        <h2 class="h6 mb-3" id="audit-log-details-heading">Activity details</h2>
        <dl class="row gy-2 mb-0">
            <dt class="col-sm-3">Time</dt>
            <dd class="col-sm-9 mb-0"><time datetime="<?php echo e($auditLog->created_at?->toIso8601String()); ?>"><?php echo e($auditLog->created_at?->format('d M Y H:i:s')); ?></time></dd>
            <dt class="col-sm-3">Actor</dt>
            <dd class="col-sm-9 text-break mb-0"><?php echo e($auditLog->actor_name ?? 'System'); ?><?php if($auditLog->actor_id !== null): ?> <span class="text-body-secondary">(<?php echo e(\Illuminate\Support\Str::headline(\Illuminate\Support\Str::singular($auditLog->actor_type))); ?> #<?php echo e($auditLog->actor_id); ?>)</span><?php endif; ?></dd>
            <dt class="col-sm-3">Action</dt>
            <dd class="col-sm-9 mb-0"><?php echo e($eventLabel); ?></dd>
            <dt class="col-sm-3">Resource</dt>
            <dd class="col-sm-9 text-break mb-0"><?php echo e($auditLog->resource); ?></dd>
            <dt class="col-sm-3">Record</dt>
            <dd class="col-sm-9 text-break mb-0"><?php echo e($auditLog->record_name ?? '-'); ?><?php if($auditLog->record_id !== null): ?> <span class="text-body-secondary">(#<?php echo e($auditLog->record_id); ?>)</span><?php endif; ?></dd>
            <?php if($hasBranches): ?>
                <dt class="col-sm-3">Branch</dt>
                <dd class="col-sm-9 text-break mb-0">
                    <?php if($auditLog->branch_id !== null && (int) $auditLog->branch_id !== (int) $defaultBranchId): ?>
                        <?php echo e($auditLog->branch_name ?? 'Branch'); ?> <span class="text-body-secondary">(#<?php echo e($auditLog->branch_id); ?>)</span>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </dd>
            <?php endif; ?>
            <dt class="col-sm-3">IP address</dt>
            <dd class="col-sm-9 text-break mb-0"><?php echo e($auditLog->ip_address ?? '-'); ?></dd>
            <dt class="col-sm-3">User agent</dt>
            <dd class="col-sm-9 small text-break mb-0"><?php echo e($auditLog->user_agent ?? '-'); ?></dd>
        </dl>
    </section>

    <div class="row g-4">
        <div class="col-lg-6">
            <section class="app-card p-3 p-md-4" aria-labelledby="audit-log-old-values-heading">
                <h2 class="h6 mb-3" id="audit-log-old-values-heading">Old values</h2>
                <pre class="small overflow-auto mb-0"><code><?php echo e(json_encode($auditLog->old_values, $jsonOptions)); ?></code></pre>
            </section>
        </div>
        <div class="col-lg-6">
            <section class="app-card p-3 p-md-4" aria-labelledby="audit-log-new-values-heading">
                <h2 class="h6 mb-3" id="audit-log-new-values-heading">New values</h2>
                <pre class="small overflow-auto mb-0"><code><?php echo e(json_encode($auditLog->new_values, $jsonOptions)); ?></code></pre>
            </section>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\TenantCore\resources\views\audit-logs\show.blade.php ENDPATH**/ ?>