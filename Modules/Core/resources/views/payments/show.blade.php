@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    <div class="app-print-hidden">
        @include('core::partials.heading', [
            'title' => $pageTitle,
            'description' => 'Payment receipt. Recorded payments cannot be edited or deleted.',
        ])
        <button class="btn btn-outline-secondary mb-4" type="button" data-print>
            <i class="fa-solid fa-print me-2" aria-hidden="true"></i>
            Print receipt
        </button>
    </div>

    <article class="app-card p-3 p-md-4 app-invoice-document">
        <div class="d-flex flex-wrap justify-content-between gap-3 border-bottom pb-3 mb-4">
            <div>
                <h2 class="h5">{{ $merchant['name'] ?? 'Payment receipt' }}</h2>
                <p class="small mb-0">{{ $merchant['email'] ?? '' }}</p>
            </div>
            <div class="text-sm-end">
                <h2 class="h5">PAYMENT RECEIPT</h2>
                <p class="mb-0">#{{ $payment->id }}</p>
            </div>
        </div>
        <dl class="row mb-0">
            <dt class="col-sm-4">Received from</dt>
            <dd class="col-sm-8">{{ $customer['name'] ?? '' }}</dd>
            <dt class="col-sm-4">Invoice</dt>
            <dd class="col-sm-8">
                @if ($invoiceUrl)
                    <a href="{{ $invoiceUrl }}">{{ $payment->invoice->number }}</a>
                @else
                    {{ $payment->invoice->number }}
                @endif
            </dd>
            <dt class="col-sm-4">Date</dt>
            <dd class="col-sm-8">{{ $paidDate }}</dd>
            <dt class="col-sm-4">Provider</dt>
            <dd class="col-sm-8">{{ $payment->provider }}</dd>
            <dt class="col-sm-4">Method</dt>
            <dd class="col-sm-8">{{ str_replace('_', ' ', $payment->method) }}</dd>
            <dt class="col-sm-4">Reference</dt>
            <dd class="col-sm-8 text-break">{{ $payment->provider_reference ?? '-' }}</dd>
            <dt class="col-sm-4">Invoice amount</dt>
            <dd class="col-sm-8 fw-semibold">{{ $payment->currency }} {{ $amount }}</dd>
            <dt class="col-sm-4">Extra charge</dt>
            <dd class="col-sm-8">{{ $payment->currency }} {{ $extraCharge }}</dd>
            <dt class="col-sm-4">Total received</dt>
            <dd class="col-sm-8 fw-semibold">{{ $payment->currency }} {{ $received }}</dd>
        </dl>

        @if ($payment->notes)
            <div class="border-top mt-3 pt-3">
                <h3 class="h6">Notes</h3>
                <p class="small text-break mb-0">{{ $payment->notes }}</p>
            </div>
        @endif
    </article>
@endsection
