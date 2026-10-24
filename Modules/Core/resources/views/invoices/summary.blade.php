@foreach ($summaries as $summary)
    <section class="app-card p-3 p-md-4 mb-4" aria-label="{{ $summary['currency'] }} invoice summary">
        <div class="d-flex justify-content-between align-items-center gap-3 mb-3">
            <h2 class="h6 mb-0">{{ $summary['currency'] }} summary</h2>
            <span class="small text-body-secondary">{{ $summary['invoiceCount'] }} issued invoices</span>
        </div>
        <div class="row g-3">
            <div class="col-sm-4">
                <div class="small text-body-secondary mb-1">Invoiced</div>
                <div class="h5 mb-0">{{ $summary['currency'] }} {{ $summary['total'] }}</div>
            </div>
            <div class="col-sm-4">
                <div class="small text-body-secondary mb-1">Paid</div>
                <div class="h5 mb-0">{{ $summary['currency'] }} {{ $summary['paid'] }}</div>
            </div>
            <div class="col-sm-4">
                <div class="small text-body-secondary mb-1">Outstanding</div>
                <div class="h5 mb-0">{{ $summary['currency'] }} {{ $summary['balance'] }}</div>
            </div>
        </div>
    </section>
@endforeach
