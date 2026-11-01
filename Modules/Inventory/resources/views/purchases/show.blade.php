@extends('core::layouts.app')

@section('title', ucfirst($document->product_type).' Purchase Invoice')

@section('content')
    <div class="app-print-hidden">
        @include('core::partials.heading', ['title' => ucfirst($document->product_type).' '.$document->number])
        <div class="d-flex flex-wrap gap-2 mb-3">
            <a class="btn btn-outline-secondary btn-sm" href="{{ route($routePrefix.'.index', ['product_type' => $document->product_type]) }}">
                <i class="fa-solid fa-arrow-left me-2" aria-hidden="true"></i>Back
            </a>
            <button class="btn btn-primary btn-sm" type="button" data-inventory-print>
                <i class="fa-solid fa-print me-2" aria-hidden="true"></i>Print
            </button>
            @if ($canEditPurchase && ! $document->voided_at)
                <a class="btn btn-outline-primary btn-sm" href="{{ route($routePrefix.'.edit', [$resourceParameter => $document, 'product_type' => $document->product_type]) }}">
                    <i class="fa-solid fa-pen-to-square me-2" aria-hidden="true"></i>Edit
                </a>
            @endif
            @if ($canDeletePurchase)
                <form action="{{ route($routePrefix.'.destroy', [$resourceParameter => $document, 'product_type' => $document->product_type]) }}" method="POST" data-inventory-confirm="Delete this purchase and reverse its stock and amounts?">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm" type="submit">
                        <i class="fa-solid fa-trash-can me-2" aria-hidden="true"></i>Delete
                    </button>
                </form>
            @elseif ($canVoid)
                <form action="{{ route($routePrefix.'.void', [$resourceParameter => $document, 'product_type' => $document->product_type]) }}" method="POST" data-inventory-confirm="Void this purchase and reverse its stock and amounts?">
                    @csrf
                    <button class="btn btn-outline-danger btn-sm" type="submit">Void</button>
                </form>
            @endif
        </div>
    </div>

    @php
        $documentTitle = 'Purchase Invoice';
        $outstandingMinor = $document->voided_at ? 0 : $document->outstandingMinor();
        $invoiceStatus = $document->voided_at ? 'VOID' : ($outstandingMinor > 0 ? 'DUE' : 'PAID');
        $showSalePrice = $document->lines->contains(fn ($line) => $line->sale_price_per_piece_minor !== null);
    @endphp

    <article class="app-card app-invoice-document app-clinical-invoice app-inventory-invoice" aria-labelledby="inventory-invoice-title">
        @include('inventory::entries.partials.invoice-header')

        <div class="app-inventory-invoice-parties">
            <div>
                <h2 class="app-clinical-invoice-section-title">Supplier</h2>
                <p class="fw-semibold mb-1">{{ $document->supplier->name }}</p>
                @if ($document->supplier->contact_no)
                    <p class="mb-1">Contact: {{ $document->supplier->contact_no }}</p>
                @endif
                @if ($document->supplier->address)
                    <p class="app-inventory-invoice-note mb-0">{{ $document->supplier->address }}</p>
                @endif
            </div>
            <div>
                @include('inventory::entries.partials.invoice-meta')
            </div>
        </div>

        @include('inventory::purchases.invoice-items')

        <div class="app-inventory-invoice-summary">
            <div>
                <div class="app-inventory-invoice-words mb-3">
                    <h2 class="app-clinical-invoice-section-title">Paid amount in words</h2>
                    <p class="mb-0">{{ $paidAmountWords }}</p>
                </div>
                @if ($document->reason)
                    <h2 class="app-clinical-invoice-section-title">Reason</h2>
                    <p class="app-inventory-invoice-note">{{ $document->reason }}</p>
                @endif
                @if ($document->notes)
                    <h2 class="app-clinical-invoice-section-title">Notes</h2>
                    <p class="app-inventory-invoice-note">{{ $document->notes }}</p>
                @endif
            </div>
            <div class="app-clinical-invoice-totals">
                <dl class="mb-0">
                    @include('inventory::entries.partials.invoice-totals')
                </dl>
            </div>
        </div>

        @include('inventory::entries.partials.invoice-footer')
    </article>
@endsection
