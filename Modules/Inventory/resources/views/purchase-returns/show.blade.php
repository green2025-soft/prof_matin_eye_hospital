@extends('core::layouts.app')

@section('title', ucfirst($entry->product_type).' Purchase Return')

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
            @can('update', $entry)
                <a class="btn btn-outline-primary btn-sm" href="{{ route($routePrefix.'.edit', [$resourceParameter => $entry, 'product_type' => $entry->product_type]) }}"><i class="fa-solid fa-pen-to-square me-2" aria-hidden="true"></i>Edit</a>
            @endcan
            @can('delete', $entry)
                <form action="{{ route($routePrefix.'.destroy', [$resourceParameter => $entry, 'product_type' => $entry->product_type]) }}" method="POST" data-confirm="Delete this purchase return and reverse its stock and amounts?">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm" type="submit"><i class="fa-solid fa-trash-can me-2" aria-hidden="true"></i>Delete</button>
                </form>
            @endcan
            @if ($canVoid)
                <form action="{{ route($routePrefix.'.void', [$resourceParameter => $entry, 'product_type' => $entry->product_type]) }}" method="POST" data-confirm="Void this purchase return and reverse its stock and amounts?">
                    @csrf
                    <button class="btn btn-outline-danger btn-sm" type="submit">Void</button>
                </form>
            @endif
        </div>
    </div>

    @php
        $invoiceTitle = 'Purchase Return';
        $invoiceStatus = $entry->voided_at ? 'VOID' : 'POSTED';
    @endphp

    <article class="app-card app-invoice-document app-clinical-invoice app-inventory-invoice" aria-labelledby="inventory-invoice-title">
        @include('inventory::entries.partials.invoice-header')

        <div class="app-inventory-invoice-parties">
            <div>
                <h2 class="app-clinical-invoice-section-title">Supplier</h2>
                <p class="fw-semibold mb-1">{{ $entry->supplier->name }}</p>
                @if ($entry->supplier->contact_no)
                    <p class="mb-1">Contact: {{ $entry->supplier->contact_no }}</p>
                @endif
                @if ($entry->supplier->address)
                    <p class="app-inventory-invoice-note mb-0">{{ $entry->supplier->address }}</p>
                @endif
            </div>
            <div>
                @include('inventory::entries.partials.invoice-meta')
                @include('inventory::entries.partials.invoice-payment-meta')
                <dl class="app-clinical-invoice-meta mb-0">
                    <div><dt>Original invoice</dt><dd>{{ $entry->purchase->number }}</dd></div>
                </dl>
            </div>
        </div>

        @include('inventory::entries.partials.invoice-financial-items', ['showDiscount' => false, 'showVat' => false])

        <div class="app-inventory-invoice-summary">
            <div>
                @if ($entry->notes)
                    <h2 class="app-clinical-invoice-section-title">Notes</h2>
                    <p class="app-inventory-invoice-note">{{ $entry->notes }}</p>
                @endif
            </div>
            <div class="app-clinical-invoice-totals">
                <dl class="mb-0">
                    <div class="app-clinical-invoice-total">
                        <dt>Original invoice credit</dt>
                        <dd>{{ moneyDecimal($entry->total) }}</dd>
                    </div>
                    <div>
                        <dt>Cash received</dt>
                        <dd>{{ moneyDecimal($entry->initial_paid) }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        @include('inventory::entries.partials.invoice-footer')
    </article>
@endsection
