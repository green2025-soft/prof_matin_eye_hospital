@extends('core::layouts.app')

@section('title', ucfirst($document->product_type).' Sales Return')

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
            @can('update', $document)
                <a class="btn btn-outline-primary btn-sm" href="{{ route($routePrefix.'.edit', [$resourceParameter => $document, 'product_type' => $document->product_type]) }}"><i class="fa-solid fa-pen-to-square me-2" aria-hidden="true"></i>Edit</a>
            @endcan
            @can('delete', $document)
                <form action="{{ route($routePrefix.'.destroy', [$resourceParameter => $document, 'product_type' => $document->product_type]) }}" method="POST" data-inventory-confirm="Delete this sales return and reverse its stock and amounts?">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm" type="submit"><i class="fa-solid fa-trash-can me-2" aria-hidden="true"></i>Delete</button>
                </form>
            @endcan
            @if ($canVoid)
                <form action="{{ route($routePrefix.'.void', [$resourceParameter => $document, 'product_type' => $document->product_type]) }}" method="POST" data-inventory-confirm="Void this sales return and reverse its stock and amounts?">
                    @csrf
                    <button class="btn btn-outline-danger btn-sm" type="submit">Void</button>
                </form>
            @endif
        </div>
    </div>

    @php
        $documentTitle = 'Sales Return';
        $invoiceStatus = $document->voided_at ? 'VOID' : 'POSTED';
    @endphp

    <article class="app-card app-invoice-document app-clinical-invoice app-inventory-invoice" aria-labelledby="inventory-invoice-title">
        @include('inventory::entries.partials.invoice-header')

        <div class="app-inventory-invoice-parties">
            <div>
                <h2 class="app-clinical-invoice-section-title">Customer</h2>
                <p class="fw-semibold mb-1">{{ $customer['name'] ?? '—' }}</p>
                <p class="mb-1">Patient ID: {{ $customer['patient_id'] ?? '—' }}</p>
                @if ($customer['mobile_no'] ?? '')
                    <p class="mb-1">Contact: {{ $customer['mobile_no'] }}</p>
                @endif
                @if ($customer['address'] ?? '')
                    <p class="app-inventory-invoice-note mb-0">{{ $customer['address'] }}</p>
                @endif
            </div>
            <div>
                @include('inventory::entries.partials.invoice-meta')
            </div>
        </div>

        @include('inventory::entries.partials.invoice-financial-items', ['showDiscount' => false, 'showVat' => false, 'showExpiry' => false])

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
                        <dt>Original invoice credit</dt>
                        <dd>{{ \Modules\Core\Helpers\Money::decimal($document->total_minor) }}</dd>
                    </div>
                    <div>
                        <dt>Cash refunded</dt>
                        <dd>{{ \Modules\Core\Helpers\Money::decimal($document->initial_paid_minor) }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        @include('inventory::entries.partials.invoice-footer')
    </article>
@endsection
