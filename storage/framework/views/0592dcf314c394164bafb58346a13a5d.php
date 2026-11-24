<?php $__env->startSection('title', 'Update Application'); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('core::partials.heading', ['title' => 'Update Application'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php
        $statusLabels = [
            'idle' => 'Ready',
            'running' => 'In progress',
            'failed' => 'Stopped',
            'completed' => 'Completed',
            'completed_with_errors' => 'Completed with errors',
        ];
        $continueUpdate = in_array($deployment['status'], ['running', 'failed'], true);
    ?>

    <section class="card">
        <div class="card-body">
            <p>Clear old framework caches, run pending central and tenant migrations, sync permissions and defaults, then rebuild production caches.</p>
            <p class="text-body-secondary" id="deployment-instructions">
                Upload the application code, built assets, and vendor files first. Back up the central and tenant databases before running.
                After a new upload, use Run Update. Use Continue Update only to resume the same uploaded version.
            </p>

            <form id="deployment-form" action="<?php echo e(route('core.deploy.store')); ?>" method="POST"
                data-run-url="<?php echo e(route('core.deploy.run')); ?>" aria-describedby="deployment-instructions deployment-keep-open">
                <?php echo csrf_field(); ?>
                <div class="d-flex flex-wrap gap-2">
                    <button class="btn btn-primary" type="submit" id="deployment-submit" name="restart" value="1">
                        <span class="spinner-border spinner-border-sm me-2" id="deployment-spinner" aria-hidden="true" hidden></span>
                        <span id="deployment-submit-label">Run Update</span>
                    </button>
                    <button class="btn btn-outline-secondary" type="submit" id="deployment-continue" name="restart" value="0"
                        <?php if(! $continueUpdate): ?> hidden <?php endif; ?>>Continue Update</button>
                </div>
                <p class="small text-body-secondary mt-2 mb-4" id="deployment-keep-open">
                    Keep this page open until the update finishes. An interrupted update can be continued.
                </p>
            </form>

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                <span class="badge text-bg-secondary" id="deployment-status"><?php echo e($statusLabels[$deployment['status']]); ?></span>
                <span class="small" id="deployment-progress-value"><?php echo e($deployment['progress']); ?>%</span>
            </div>
            <div class="progress mb-3" id="deployment-progress" role="progressbar" aria-label="Update progress"
                aria-valuenow="<?php echo e($deployment['progress']); ?>" aria-valuemin="0" aria-valuemax="100">
                <div class="progress-bar" id="deployment-progress-bar" style="width: <?php echo e($deployment['progress']); ?>%"></div>
            </div>
            <p id="deployment-message" role="status" aria-live="polite" aria-atomic="true"><?php echo e($deployment['message']); ?></p>

            <dl class="row small mb-0">
                <dt class="col-sm-3 col-lg-2">Tenants processed</dt>
                <dd class="col-sm-9 col-lg-10" id="deployment-tenants"><?php echo e($deployment['tenant_processed']); ?> of <?php echo e($deployment['tenant_total']); ?></dd>
                <dt class="col-sm-3 col-lg-2">Started</dt>
                <dd class="col-sm-9 col-lg-10" id="deployment-started"><?php echo e($deployment['started_at'] ?? 'Not started'); ?></dd>
                <dt class="col-sm-3 col-lg-2">Finished</dt>
                <dd class="col-sm-9 col-lg-10" id="deployment-finished"><?php echo e($deployment['finished_at'] ?? 'Not finished'); ?></dd>
            </dl>
        </div>

        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <caption class="visually-hidden">Update results</caption>
                <thead>
                    <tr>
                        <th scope="col" class="ps-3">Update step</th>
                        <th scope="col">Result</th>
                        <th scope="col" class="pe-3">Details</th>
                    </tr>
                </thead>
                <tbody id="deployment-results">
                    <?php $__empty_1 = true; $__currentLoopData = $deployment['results']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <th scope="row" class="ps-3 fw-normal"><?php echo e($result['label']); ?></th>
                            <td>
                                <span class="badge <?php echo e($result['success'] ? 'text-bg-success' : 'text-bg-danger'); ?>">
                                    <?php echo e($result['success'] ? 'Success' : 'Failed'); ?>

                                </span>
                            </td>
                            <td class="pe-3 text-break"><?php echo e($result['message']); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="3" class="p-3 text-body-secondary">No update steps have run yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\resources\views\deployment\index.blade.php ENDPATH**/ ?>