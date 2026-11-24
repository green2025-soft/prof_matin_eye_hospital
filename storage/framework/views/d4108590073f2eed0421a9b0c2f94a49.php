<?php $__env->startSection('title', 'Chart of Accounts'); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $levelLabels = [2 => 'Group', 3 => 'Subgroup', 4 => 'Account group', 5 => 'Ledger'];
        $groupColors = ['asset' => 'primary', 'liability' => 'danger', 'equity' => 'info', 'income' => 'success', 'expense' => 'warning'];
    ?>

    <?php echo $__env->make('core::partials.heading', [
        'title' => 'Chart of Accounts',
        'create' => [
            'model' => resourceModel($routePrefix.'.create'),
            'url' => route($routePrefix.'.create'),
            'label' => 'New account',
        ],
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <section class="app-card p-3 mb-3" aria-label="Find and browse accounts">
        <div class="row g-3 align-items-end">
            <div class="col-lg">
                <label class="form-label" for="account-chart-search">Find an account</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass text-body-secondary" aria-hidden="true"></i></span>
                    <input class="form-control" id="account-chart-search" type="search" maxlength="80"
                        value="<?php echo e($filters['search'] ?? ''); ?>" placeholder="Account name or code"
                        aria-describedby="account-chart-help account-chart-count">
                    <button class="btn btn-outline-secondary account-chart-clear" type="button">Clear</button>
                </div>
            </div>
            <div class="col-lg-auto">
                <div class="d-flex flex-wrap gap-2">
                    <button class="btn btn-outline-primary" id="account-chart-expand" type="button">
                        <i class="fa-solid fa-angles-down me-2" aria-hidden="true"></i>Expand all
                    </button>
                    <button class="btn btn-outline-secondary" id="account-chart-collapse" type="button">
                        <i class="fa-solid fa-angles-up me-2" aria-hidden="true"></i>Collapse all
                    </button>
                </div>
            </div>
        </div>
        <div class="d-flex flex-wrap justify-content-between gap-2 mt-2">
            <p class="small text-body-secondary mb-0" id="account-chart-help">Open a group to browse its accounts. Search also shows each account's parent groups.</p>
            <p class="small text-body-secondary mb-0" id="account-chart-count" role="status" aria-live="polite" aria-atomic="true">
                <?php echo e($records->whereNotNull('parent_id')->count()); ?> accounts
            </p>
        </div>
    </section>

    <div id="account-chart" data-session-key="<?php echo e('accounting.chart.'.hash('sha256', session()->getId())); ?>">
        <?php $__empty_1 = true; $__currentLoopData = $records->groupBy('type'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $accounts): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $root = $accounts->firstWhere('parent_id', null);
                $color = $groupColors[$type];
            ?>
            <details class="app-card border border-<?php echo e($color); ?>-subtle overflow-hidden mb-3 account-chart-group" id="account-root-<?php echo e($root->id); ?>">
                <summary class="btn border-0 rounded-0 w-100 d-flex align-items-center justify-content-between gap-2 p-2 text-start bg-<?php echo e($color); ?>-subtle">
                    <span class="d-flex align-items-center gap-2">
                        <span class="bg-white text-<?php echo e($color); ?>-emphasis rounded-3 p-2"><i class="fa-solid fa-folder-tree fs-5" aria-hidden="true"></i></span>
                        <span>
                            <strong class="d-block account-root-name"><?php echo e($root->name); ?></strong>
                            <span class="small text-body-secondary"><span class="account-root-code"><?php echo e($root->code); ?></span> &middot; Root account</span>
                        </span>
                    </span>
                    <span class="d-flex align-items-center gap-3">
                        <span class="badge text-bg-light border"><?php echo e($accounts->count() - 1); ?> accounts</span>
                        <?php if($canCreateAccount && $root->active): ?>
                            <a class="btn btn-outline-success btn-sm account-group-add text-nowrap"
                                href="<?php echo e(route($routePrefix.'.create', ['parent_id' => $root->id])); ?>"
                                aria-label="Add group under <?php echo e($root->name); ?>"><i class="fa-solid fa-plus me-1" aria-hidden="true"></i>Add group</a>
                        <?php endif; ?>
                        <i class="fa-solid fa-chevron-right account-root-caret" aria-hidden="true"></i>
                    </span>
                </summary>
                <div class="table-responsive">
                    <table class="table table-sm app-table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-2 py-2" scope="col">Account</th>
                                <th class="px-2 py-2" scope="col">Code</th>
                                <th class="px-2 py-2" scope="col">Level</th>
                                <th class="px-2 py-2" scope="col">Posting</th>
                                <th class="px-2 py-2" scope="col">Status</th>
                                <th class="px-2 py-2 text-end" scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $accounts->whereNotNull('parent_id'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $head): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="account-chart-row" id="account-row-<?php echo e($head->id); ?>" data-parent-id="<?php echo e($head->parent_id); ?>">
                                    <th class="ps-<?php echo e($head->depth); ?> pe-2 py-1" scope="row">
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if($head->children_exists): ?>
                                                <button class="btn btn-outline-secondary btn-sm border-0 px-1 py-0 account-tree-toggle" type="button"
                                                    aria-expanded="false" aria-label="Expand <?php echo e($head->name); ?>">
                                                    <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                                                </button>
                                            <?php else: ?>
                                                <i class="fa-solid fa-minus text-body-tertiary ms-2 me-2" aria-hidden="true"></i>
                                            <?php endif; ?>
                                            <span class="account-name"><?php echo e($head->name); ?></span>
                                        </div>
                                    </th>
                                    <td class="px-2 py-1 text-nowrap"><span class="account-code font-monospace"><?php echo e($head->code); ?></span></td>
                                    <td class="px-2 py-1 small text-body-secondary text-nowrap"><?php echo e($levelLabels[$head->depth]); ?></td>
                                    <td class="px-2 py-1"><span class="badge <?php echo e($head->is_transaction ? 'text-bg-primary' : 'text-bg-light border'); ?>"><?php echo e($head->is_transaction ? 'Posting' : 'Group only'); ?></span></td>
                                    <td class="px-2 py-1 small"><span class="<?php echo e($head->active ? 'text-success' : 'text-body-secondary'); ?>"><?php echo e($head->active ? 'Active' : 'Inactive'); ?></span></td>
                                    <td class="px-2 py-1">
                                        <div class="d-flex justify-content-end align-items-center gap-2">
                                            <?php if($canCreateAccount && $head->active && $head->depth < 5 && ! $head->is_transaction): ?>
                                                <a class="btn btn-outline-success btn-sm account-group-add text-nowrap"
                                                    href="<?php echo e(route($routePrefix.'.create', ['parent_id' => $head->id])); ?>"
                                                    aria-label="Add account under <?php echo e($head->name); ?>"><i class="fa-solid fa-plus me-1" aria-hidden="true"></i><?php echo e($head->depth === 4 ? 'Add ledger' : 'Add group'); ?></a>
                                            <?php endif; ?>
                                            <?php echo $__env->make('core::partials.row-actions', ['model' => $head], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </details>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <section class="app-card"><?php echo $__env->make('core::partials.empty', ['title' => 'No accounts yet', 'description' => 'Set up the default accounts before adding a ledger.', 'icon' => 'sitemap'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></section>
        <?php endif; ?>
    </div>
    <div class="alert alert-light border text-center py-4" id="account-chart-empty" hidden>
        <p class="fw-semibold mb-1">No matching account</p>
        <p class="small text-body-secondary mb-2">Try a shorter name or account code.</p>
        <button class="btn btn-outline-primary btn-sm account-chart-clear" type="button">Clear search</button>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('core::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Accounting\resources\views\account-heads\index.blade.php ENDPATH**/ ?>