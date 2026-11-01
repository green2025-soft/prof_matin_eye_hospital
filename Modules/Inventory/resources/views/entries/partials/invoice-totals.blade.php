<div>
    <dt>Subtotal</dt>
    <dd>{{ \Modules\Core\Helpers\Money::decimal($document->subtotal_minor) }}</dd>
</div>
<div>
    <dt>Total discounts</dt>
    <dd>{{ \Modules\Core\Helpers\Money::decimal($document->discount_minor) }}</dd>
</div>
@if ($document->vat_minor > 0)
    @if ($document->vat_calculation === 'line')
        @php
            $addedVatMinor = $document->lines->where('vat_mode', 'exclusive')->sum('vat_minor');
            $includedVatMinor = $document->lines->where('vat_mode', 'inclusive')->sum('vat_minor');
        @endphp
        @if ($addedVatMinor > 0)
            <div>
                <dt>VAT added</dt>
                <dd>{{ \Modules\Core\Helpers\Money::decimal($addedVatMinor) }}</dd>
            </div>
        @endif
        @if ($includedVatMinor > 0)
            <div>
                <dt>VAT included</dt>
                <dd>{{ \Modules\Core\Helpers\Money::decimal($includedVatMinor) }}</dd>
            </div>
        @endif
    @else
        <div>
            <dt>VAT {{ $document->vat_percent }}% ({{ $document->vat_mode === 'inclusive' ? 'included' : 'added' }})</dt>
            <dd>{{ \Modules\Core\Helpers\Money::decimal($document->vat_minor) }}</dd>
        </div>
    @endif
@endif
<div class="app-clinical-invoice-total">
    <dt>Invoice total</dt>
    <dd>{{ \Modules\Core\Helpers\Money::decimal($document->total_minor) }}</dd>
</div>
<div>
    <dt>Paid</dt>
    <dd>{{ \Modules\Core\Helpers\Money::decimal($document->paid_minor) }}</dd>
</div>
@if ($document->returned_minor > 0)
    <div>
        <dt>Returned value</dt>
        <dd>{{ \Modules\Core\Helpers\Money::decimal($document->returned_minor) }}</dd>
    </div>
@endif
@if ($document->refunded_minor > 0)
    <div>
        <dt>Cash refunded</dt>
        <dd>{{ \Modules\Core\Helpers\Money::decimal($document->refunded_minor) }}</dd>
    </div>
@endif
@if ($document->settlement_discount_minor > 0)
    <div>
        <dt>Settlement discount</dt>
        <dd>{{ \Modules\Core\Helpers\Money::decimal($document->settlement_discount_minor) }}</dd>
    </div>
@endif
<div class="app-clinical-invoice-refund-total">
    <dt>Due</dt>
    <dd>{{ \Modules\Core\Helpers\Money::decimal($outstandingMinor) }}</dd>
</div>
