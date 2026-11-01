<dl class="app-clinical-invoice-meta mb-0">
    <div>
        <dt>Date</dt>
        <dd>{{ $document->document_date->format('d-m-Y') }}</dd>
    </div>
    @if ($document->supplier_invoice_no)
        <div>
            <dt>Supplier invoice no.</dt>
            <dd>{{ $document->supplier_invoice_no }}</dd>
        </div>
    @endif
    @if ($document->sourceDocument)
        <div>
            <dt>Original invoice</dt>
            <dd>{{ $document->sourceDocument->number }}</dd>
        </div>
    @endif
    @if ($document->paymentType)
        <div>
            <dt>Payment type</dt>
            <dd>{{ $document->paymentType->name }}</dd>
        </div>
    @endif
    @if ($document->stock_method)
        <div>
            <dt>Stock method</dt>
            <dd>{{ strtoupper($document->stock_method) }}</dd>
        </div>
    @endif
</dl>
