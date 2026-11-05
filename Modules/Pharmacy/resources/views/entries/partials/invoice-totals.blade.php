<div class="app-clinical-invoice-total">
    <dt>Invoice total</dt>
    <dd>{{ moneyDecimal($entry->total) }}</dd>
</div>
<div>
    <dt>Paid</dt>
    <dd>{{ moneyDecimal($entry->paid) }}</dd>
</div>
@if ($entry->returned > 0)
    <div>
        <dt>Returned value</dt>
        <dd>{{ moneyDecimal($entry->returned) }}</dd>
    </div>
@endif
@if ($entry->refunded > 0)
    <div>
        <dt>Cash refunded</dt>
        <dd>{{ moneyDecimal($entry->refunded) }}</dd>
    </div>
@endif
@if ($entry->settlement_discount > 0)
    <div>
        <dt>Settlement discount</dt>
        <dd>{{ moneyDecimal($entry->settlement_discount) }}</dd>
    </div>
@endif
<div class="app-clinical-invoice-refund-total">
    <dt>Due</dt>
    <dd>{{ moneyDecimal($outstandingMinor) }}</dd>
</div>
