<div class="row g-2 py-3">
    <div class="col-6 col-lg-3">
        <div class="border rounded p-2">
            <div class="small text-body-secondary">Opening cash / bank</div>
            <div class="fw-semibold">{{ $opening }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="border rounded p-2">
            <div class="small text-body-secondary">Received</div>
            <div class="fw-semibold">{{ $received }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="border rounded p-2">
            <div class="small text-body-secondary">Paid</div>
            <div class="fw-semibold">{{ $paid }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="border rounded p-2">
            <div class="small text-body-secondary">Closing cash / bank</div>
            <div class="fw-semibold">{{ $closing }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-4">
        <div class="border rounded p-2">
            <div class="small text-body-secondary">Receivable due</div>
            <div class="fw-semibold">{{ $receivable }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-4">
        <div class="border rounded p-2">
            <div class="small text-body-secondary">Payable due</div>
            <div class="fw-semibold">{{ $payable }}</div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="border rounded p-2">
            <div class="small text-body-secondary">Combined balance</div>
            <div class="fw-semibold">{{ $balance }}</div>
        </div>
    </div>
</div>
<p class="small text-body-secondary">Combined balance = closing cash / bank + receivable due − payable due.</p>
