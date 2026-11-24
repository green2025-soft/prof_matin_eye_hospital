<div class="app-report-summary row row-cols-1 row-cols-sm-3 g-2 mx-0 py-3">
    <div class="col">
        <div class="border rounded p-2 h-100">
            <span class="d-block small">Total Income</span>
            <strong class="d-block text-success"><?php echo e($received); ?></strong>
        </div>
    </div>
    <div class="col">
        <div class="border rounded p-2 h-100">
            <span class="d-block small">Total Expense</span>
            <strong class="d-block text-danger"><?php echo e($paid); ?></strong>
        </div>
    </div>
    <div class="col">
        <div class="border rounded p-2 h-100">
            <span class="d-block small">Balance</span>
            <strong class="d-block"><?php echo e($net); ?></strong>
        </div>
    </div>
</div>
<p class="small text-body-secondary">Balance = Income − Expense for the selected period.</p>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\TenantCore\resources\views\reports\partials\period-totals.blade.php ENDPATH**/ ?>