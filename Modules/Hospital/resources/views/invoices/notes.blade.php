<div class="col-12 col-sm-6 app-clinical-invoice-notes">
    @yield('invoice-notes')
    @if ($refundCause !== null)
        <h2 class="app-clinical-invoice-section-title">Refund Cause</h2>
        <p class="app-clinical-invoice-note">{{ $refundCause }}</p>
    @endif
    @if (filled($remarks))
        <h2 class="app-clinical-invoice-section-title">Remarks</h2>
        <p class="app-clinical-invoice-note">{{ $remarks }}</p>
    @endif
</div>

<div class="col-12 col-sm-6 app-clinical-invoice-payment-stamp">
    {{-- <span>{{ $status }}</span> --}}
</div>

<div class="col-12 app-clinical-invoice-words">
    <h2 class="app-clinical-invoice-section-title">{{ $wordsLabel }}</h2>
    <p class="app-clinical-invoice-note mb-0">{{ $amountWords }}</p>
</div>
