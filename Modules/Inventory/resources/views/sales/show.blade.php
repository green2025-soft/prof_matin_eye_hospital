@extends('core::layouts.app')

@section('title', ucfirst($document->product_type).' Sales Invoice')

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
                <a class="btn btn-outline-primary btn-sm" href="{{ route($routePrefix.'.edit', [$resourceParameter => $document, 'product_type' => $document->product_type]) }}">
                    <i class="fa-solid fa-pen-to-square me-2" aria-hidden="true"></i>Edit
                </a>
            @endcan
            @can('delete', $document)
                <form action="{{ route($routePrefix.'.destroy', [$resourceParameter => $document, 'product_type' => $document->product_type]) }}" method="POST" data-inventory-confirm="Delete this sales invoice and reverse its stock and amounts?">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm" type="submit"><i class="fa-solid fa-trash-can me-2" aria-hidden="true"></i>Delete</button>
                </form>
            @endcan
            @if ($canVoid)
                <form action="{{ route($routePrefix.'.void', [$resourceParameter => $document, 'product_type' => $document->product_type]) }}" method="POST" data-inventory-confirm="Void this sales invoice and reverse its stock and amounts?">
                    @csrf
                    <button class="btn btn-outline-danger btn-sm" type="submit">Void</button>
                </form>
            @endif
        </div>
    </div>

    @php
        $documentTitle = 'Sales Invoice';
        $outstandingMinor = $document->voided_at ? 0 : $document->outstandingMinor();
        $invoiceStatus = $document->voided_at ? 'VOID' : ($outstandingMinor > 0 ? 'DUE' : 'PAID');
        $invoicePaper = in_array($invoicePaper ?? null, ['a4', 'a5', 'pos'], true) ? $invoicePaper : 'a4';
        $salePosWidth = is_string($salePosWidth ?? null) && preg_match('/\A\d{1,3}(?:\.\d{1,2})?\z/', $salePosWidth)
            && (float) $salePosWidth >= 20 && (float) $salePosWidth <= 300 ? $salePosWidth : '80';
        $salePosWidth = rtrim(rtrim(number_format((float) $salePosWidth, 2, '.', ''), '0'), '.');
    @endphp

    <style>
        @media print {
            @page inventory-sale {
                size: {{ match ($invoicePaper) { 'a5' => 'A5', 'pos' => $salePosWidth.'mm 297mm', default => 'A4' } }};
                margin: {{ $invoicePaper === 'pos' ? '2mm 0 0' : '10mm' }};
            }

            .app-shell,
            .app-page,
            .app-content,
            .app-sale-invoice {
                page: inventory-sale;
            }

            @if ($invoicePaper === 'pos')
                .app-shell,
                .app-page,
                .app-content {
                    width: 100%;
                    max-width: none;
                    min-height: 0;
                    margin: 0 !important;
                    padding: 0 !important;
                    overflow: visible;
                }
            @endif
        }
    </style>

    <article class="app-card app-invoice-document app-clinical-invoice app-inventory-invoice app-sale-invoice app-sale-invoice-{{ $invoicePaper }}" @if ($invoicePaper === 'pos') style="--sale-pos-width: {{ $salePosWidth }}mm" @endif aria-labelledby="inventory-invoice-title">
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

        @include('inventory::entries.partials.invoice-financial-items', ['showExpiry' => false])

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
                    @include('inventory::entries.partials.invoice-totals')
                </dl>
            </div>
        </div>

        @include('inventory::entries.partials.invoice-footer')
    </article>
@endsection
