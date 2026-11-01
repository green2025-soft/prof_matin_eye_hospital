@extends('core::layouts.app')

@section('title', ucfirst($document->product_type).' Stock Adjustment')

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
            @if ($canVoid)
                <form action="{{ route($routePrefix.'.void', [$resourceParameter => $document, 'product_type' => $document->product_type]) }}" method="POST" data-inventory-confirm="Void this stock adjustment and reverse its stock and amounts?">
                    @csrf
                    <button class="btn btn-outline-danger btn-sm" type="submit">Void</button>
                </form>
            @endif
        </div>
    </div>

    @php
        $documentTitle = 'Stock Adjustment';
        $invoiceStatus = $document->voided_at ? 'VOID' : 'POSTED';
    @endphp

    <article class="app-card app-invoice-document app-clinical-invoice app-inventory-invoice" aria-labelledby="inventory-invoice-title">
        @include('inventory::entries.partials.invoice-header')

        <div class="app-inventory-invoice-parties">
            <div>
                <h2 class="app-clinical-invoice-section-title">Stock entry</h2>
                <p class="fw-semibold mb-1">{{ $documentTitle }}</p>
            </div>
            <div>
                @include('inventory::entries.partials.invoice-meta')
            </div>
        </div>

        @include('inventory::entries.partials.invoice-stock-items')

        <div class="app-inventory-invoice-summary">
            <div>
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
                    <div class="app-clinical-invoice-total">
                        <dt>Stock value</dt>
                        <dd>{{ \Modules\Core\Helpers\Money::decimal($document->cost_minor) }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        @include('inventory::entries.partials.invoice-footer')
    </article>
@endsection
