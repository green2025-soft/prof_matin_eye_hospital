<div class="app-clinical-invoice-notes">
    @yield('invoice-notes')
    @if ($refundCause !== null)
        <h2 class="app-clinical-invoice-section-title">Refund cause</h2>
        <p class="app-clinical-invoice-note">{{ $refundCause }}</p>
    @endif
    @if (filled($remarks))
        <h2 class="app-clinical-invoice-section-title">Remarks</h2>
        <p class="app-clinical-invoice-note">{{ $remarks }}</p>
    @endif
    <h2 class="app-clinical-invoice-section-title">{{ $wordsLabel }}</h2>
    <p class="app-clinical-invoice-note mb-0">{{ $amountWords }}</p>
</div>
