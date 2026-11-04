@if ($entry->paymentType)
    <dl class="app-clinical-invoice-meta mb-0">
        <div>
            <dt>Payment type</dt>
            <dd>{{ $entry->paymentType->name }}</dd>
        </div>
    </dl>
@endif
