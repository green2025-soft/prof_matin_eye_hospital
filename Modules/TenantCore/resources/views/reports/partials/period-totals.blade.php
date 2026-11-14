<div class="app-report-summary row row-cols-1 row-cols-sm-3 g-2 mx-0 py-3">
    <div class="col">
        <div class="border rounded p-2 h-100">
            <span class="d-block small">Total Income</span>
            <strong class="d-block text-success">{{ $received }}</strong>
        </div>
    </div>
    <div class="col">
        <div class="border rounded p-2 h-100">
            <span class="d-block small">Total Expense</span>
            <strong class="d-block text-danger">{{ $paid }}</strong>
        </div>
    </div>
    <div class="col">
        <div class="border rounded p-2 h-100">
            <span class="d-block small">Balance</span>
            <strong class="d-block">{{ $net }}</strong>
        </div>
    </div>
</div>
<p class="small text-body-secondary">Balance = Income − Expense for the selected period.</p>
