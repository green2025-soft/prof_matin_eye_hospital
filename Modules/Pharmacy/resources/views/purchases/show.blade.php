@extends('core::layouts.app')

@section('title', ucfirst($entry->product_type).' Purchase Invoice')

@section('content')
    <div class="app-print-hidden">
        @include('core::partials.heading', ['title' => ucfirst($entry->product_type).' '.$entry->number])
        <div class="d-flex flex-wrap gap-2 mb-3">
            <a class="btn btn-outline-secondary btn-sm" href="{{ route($routePrefix.'.index', ['product_type' => $entry->product_type]) }}">
                <i class="fa-solid fa-arrow-left me-2" aria-hidden="true"></i>Back
            </a>
            <button class="btn btn-primary btn-sm" type="button" data-print>
                <i class="fa-solid fa-print me-2" aria-hidden="true"></i>Print
            </button>
            @if ($canEditPurchase && ! $entry->voided_at)
                <a class="btn btn-outline-primary btn-sm" href="{{ route($routePrefix.'.edit', [$resourceParameter => $entry, 'product_type' => $entry->product_type]) }}">
                    <i class="fa-solid fa-pen-to-square me-2" aria-hidden="true"></i>Edit
                </a>
            @endif
            @if ($canDeletePurchase)
                <form action="{{ route($routePrefix.'.destroy', [$resourceParameter => $entry, 'product_type' => $entry->product_type]) }}" method="POST" data-confirm="Delete this purchase and reverse its stock and amounts?">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm" type="submit">
                        <i class="fa-solid fa-trash-can me-2" aria-hidden="true"></i>Delete
                    </button>
                </form>
            @elseif ($canVoid)
                <form action="{{ route($routePrefix.'.void', [$resourceParameter => $entry, 'product_type' => $entry->product_type]) }}" method="POST" data-confirm="Void this purchase and reverse its stock and amounts?">
                    @csrf
                    <button class="btn btn-outline-danger btn-sm" type="submit">Void</button>
                </form>
            @endif
        </div>
    </div>

    @php
        $invoiceTitle = 'Purchase Invoice';
        $outstandingMinor = $entry->voided_at ? 0 : $entry->outstandingMinor();
        $invoiceStatus = $entry->voided_at ? 'VOID' : ($outstandingMinor > 0 ? 'DUE' : 'PAID');
        $showSalePrice = $entry->lines->contains(fn ($line) => $line->sale_price_per_piece !== null);
    @endphp

    <article class="app-card app-invoice-document app-clinical-invoice app-pharmacy-invoice" aria-labelledby="pharmacy-invoice-title">
        @include('pharmacy::entries.partials.invoice-header')

        <div class="app-pharmacy-invoice-parties">
            <div>
                <h2 class="app-clinical-invoice-section-title">Supplier</h2>
                <p class="fw-semibold mb-1">{{ $entry->supplier->name }}</p>
                @if ($entry->supplier->contact_no)
                    <p class="mb-1">Contact: {{ $entry->supplier->contact_no }}</p>
                @endif
                @if ($entry->supplier->address)
                    <p class="app-pharmacy-invoice-note mb-0">{{ $entry->supplier->address }}</p>
                @endif
            </div>
            <div>
                @include('pharmacy::entries.partials.invoice-meta')
                @include('pharmacy::entries.partials.invoice-payment-meta')
                @if ($entry->supplier_invoice_no)
                    <dl class="app-clinical-invoice-meta mb-0">
                        <div><dt>Supplier invoice no.</dt><dd>{{ $entry->supplier_invoice_no }}</dd></div>
                    </dl>
                @endif
            </div>
        </div>

        @include('pharmacy::purchases.invoice-items')

        <div class="app-pharmacy-invoice-summary">
            <div>
                <div class="app-pharmacy-invoice-words mb-3">
                    <h2 class="app-clinical-invoice-section-title">Paid amount in words</h2>
                    <p class="mb-0">{{ $paidAmountWords }}</p>
                </div>
                @if ($entry->notes)
                    <h2 class="app-clinical-invoice-section-title">Notes</h2>
                    <p class="app-pharmacy-invoice-note">{{ $entry->notes }}</p>
                @endif
            </div>
            <div class="app-clinical-invoice-totals">
                <dl class="mb-0">
                    <div>
                        <dt>Subtotal</dt>
                        <dd>{{ moneyDecimal($entry->subtotal) }}</dd>
                    </div>
                    <div>
                        <dt>Total discounts</dt>
                        <dd>{{ moneyDecimal($entry->discount) }}</dd>
                    </div>
                    @php
                        $addedVatMinor = $entry->lines->where('vat_mode', 'exclusive')->sum('vat');
                        $includedVatMinor = $entry->lines->where('vat_mode', 'inclusive')->sum('vat');
                    @endphp
                    @if ($addedVatMinor > 0)
                        <div><dt>VAT added</dt><dd>{{ moneyDecimal($addedVatMinor) }}</dd></div>
                    @endif
                    @if ($includedVatMinor > 0)
                        <div><dt>VAT included</dt><dd>{{ moneyDecimal($includedVatMinor) }}</dd></div>
                    @endif
                    @include('pharmacy::entries.partials.invoice-totals')
                </dl>
            </div>
        </div>

        @include('pharmacy::entries.partials.invoice-footer')
    </article>
@endsection
