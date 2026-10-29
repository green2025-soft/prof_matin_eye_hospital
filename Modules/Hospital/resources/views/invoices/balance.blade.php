@if ($creditAmount !== '0.00')
    <h3 class="app-clinical-invoice-section-title mt-3">Refund adjustments</h3>
    <dl class="mb-0">
        <div><dt>Refunded value</dt><dd>{{ $creditAmount }}</dd></div>
        <div><dt>Due adjusted</dt><dd>{{ $dueAdjusted }}</dd></div>
        <div><dt>Cash refunded</dt><dd>{{ $cashRefunded }}</dd></div>
    </dl>
@endif
@if ($collectedAmount !== '0.00')
    <h3 class="app-clinical-invoice-section-title mt-3">Due collections</h3>
    <dl class="mb-0">
        <div><dt>Received amount</dt><dd>{{ $collectedAmount }}</dd></div>
    </dl>
@endif
@if ($creditAmount !== '0.00' || $collectedAmount !== '0.00')
    <h3 class="app-clinical-invoice-section-title mt-3">Current balance</h3>
    <dl class="mb-0">
        <div class="app-clinical-invoice-total"><dt>Current payable</dt><dd>{{ $remainingPayable }}</dd></div>
        <div><dt>Net paid</dt><dd>{{ $remainingPaid }}</dd></div>
        <div><dt>Current due</dt><dd>{{ $remainingDue }}</dd></div>
    </dl>
@endif
