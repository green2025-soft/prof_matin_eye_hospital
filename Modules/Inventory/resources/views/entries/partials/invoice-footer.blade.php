<div class="app-inventory-invoice-signatures">
    <div class="app-clinical-invoice-signature"><span>Received by</span></div>
    <div class="app-clinical-invoice-signature"><span>Authorized signature</span></div>
</div>
<footer class="app-clinical-invoice-footer app-inventory-invoice-print-meta">
    <div>
        <p class="mb-1">Printed by: {{ $printedBy }}</p>
        <p class="mb-0">Printed at: {{ $printedAt }}</p>
    </div>
    <p class="small mb-0">{{ $entry->number }}</p>
</footer>
