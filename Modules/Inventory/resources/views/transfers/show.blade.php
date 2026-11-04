@extends('core::layouts.app')

@section('title', ucfirst($entry->product_type).' Branch Transfer')

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
            @if ($canVoid)
                <form action="{{ route($routePrefix.'.void', [$resourceParameter => $entry, 'product_type' => $entry->product_type]) }}" method="POST" data-confirm="Void this branch transfer and reverse its stock and amounts?">
                    @csrf
                    <button class="btn btn-outline-danger btn-sm" type="submit">Void</button>
                </form>
            @endif
        </div>
    </div>

    @php
        $invoiceTitle = 'Branch Transfer';
        $invoiceStatus = $entry->voided_at ? 'VOID' : 'POSTED';
    @endphp

    <article class="app-card app-invoice-document app-clinical-invoice app-inventory-invoice" aria-labelledby="inventory-invoice-title">
        @include('inventory::entries.partials.invoice-header')

        <div class="app-inventory-invoice-parties">
            <div>
                <h2 class="app-clinical-invoice-section-title">Stock entry</h2>
                <p class="fw-semibold mb-1">{{ $invoiceTitle }}</p>
                <p class="mb-0">To: {{ $entry->destinationBranch?->name }}</p>
            </div>
            <div>
                <dl class="app-clinical-invoice-meta mb-0">
                    <div>
                        <dt>Date</dt>
                        <dd>{{ $entry->entry_date->format('d-m-Y') }}</dd>
                    </div>
                    <div>
                        <dt>Stock method</dt>
                        <dd>{{ strtoupper($entry->stock_method) }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        @include('inventory::transfers.invoice-items')

        <div class="app-inventory-invoice-summary">
            <div>
                @if ($entry->reason)
                    <h2 class="app-clinical-invoice-section-title">Reason</h2>
                    <p class="app-inventory-invoice-note">{{ $entry->reason }}</p>
                @endif
                @if ($entry->notes)
                    <h2 class="app-clinical-invoice-section-title">Notes</h2>
                    <p class="app-inventory-invoice-note">{{ $entry->notes }}</p>
                @endif
            </div>
            <div class="app-clinical-invoice-totals">
                <dl class="mb-0">
                    <div class="app-clinical-invoice-total">
                        <dt>Stock value</dt>
                        <dd>{{ moneyDecimal($entry->cost) }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        @include('inventory::entries.partials.invoice-footer')
    </article>
@endsection
