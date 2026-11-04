<header class="app-clinical-invoice-header">
    <div class="app-clinical-invoice-brand">
        @if ($logoUrl)
            <img class="app-clinical-invoice-logo" src="{{ $logoUrl }}" alt="" width="76" height="76">
        @endif
        <div>
            <h2 class="app-clinical-invoice-company">{{ $company['name'] }}</h2>
            @if ($company['branch_name'] ?? '')
                <p class="fw-semibold mb-1">{{ $company['branch_name'] }}</p>
            @endif
            @if ($company['address'] ?? '')
                <p class="app-clinical-invoice-address">{{ $company['address'] }}</p>
            @endif
            <div class="app-clinical-invoice-contact">
                @if ($company['phone'] ?? '')
                    <span>Phone: {{ $company['phone'] }}</span>
                @endif
                @if ($company['email'] ?? '')
                    <span>{{ $company['email'] }}</span>
                @endif
                @if ($company['tax_id'] ?? '')
                    <span>Tax ID: {{ $company['tax_id'] }}</span>
                @endif
            </div>
        </div>
    </div>
    <div class="app-clinical-invoice-title-row">
        <div>
            <h1 id="inventory-invoice-title">{{ $invoiceTitle }}</h1>
            <p class="fw-semibold mb-0">{{ $entry->number }}</p>
            <p class="small mb-0">{{ ucfirst($entry->product_type) }}</p>
        </div>
        <span class="app-clinical-invoice-status app-inventory-invoice-status">{{ $invoiceStatus }}</span>
    </div>
    @if ($entry->voided_at)
        <p class="small fw-semibold mb-3">Voided on {{ $entry->voided_at->format('d-m-Y H:i') }}</p>
    @endif
</header>
