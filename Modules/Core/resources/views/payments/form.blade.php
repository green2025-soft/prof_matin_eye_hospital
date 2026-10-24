@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', [
        'title' => $pageTitle,
        'description' => 'Record money already received. This does not initiate an online charge.',
    ])

    <form class="app-card p-3 p-md-4" action="{{ $formAction }}" method="POST">
        @csrf
        <input name="idempotency_key" type="hidden" value="{{ getValue('idempotency_key', null, $idempotencyKey) }}">

        <section class="app-form-section" aria-labelledby="payment-details-heading">
            <h2 class="app-form-section-title" id="payment-details-heading">Payment details</h2>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="invoice-id">Invoice ID</label>
                    <input
                        class="{{ inputClass('invoice_id') }}"
                        id="invoice-id"
                        name="invoice_id"
                        type="text"
                        inputmode="numeric"
                        list="payment-invoices"
                        value="{{ getValue('invoice_id') }}"
                        required
                        {{ inputAttrs('invoice_id', 'invoice-id', 'invoice-id-help') }}
                    >
                    <datalist id="payment-invoices">
                        @foreach ($invoices as $invoice)
                            <option value="{{ $invoice->id }}">
                                {{ $invoice->number }} - {{ $invoice->currency }} {{ $invoice->balance_amount }} due
                            </option>
                        @endforeach
                    </datalist>
                    <div class="form-text" id="invoice-id-help">
                        Choose an unpaid invoice suggestion or enter its ID. Currency comes from that invoice.
                    </div>
                    @error('invoice_id')
                        <div class="invalid-feedback d-block" id="invoice-id-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="amount">Amount received</label>
                    <input
                        class="{{ inputClass('amount') }}"
                        id="amount"
                        name="amount"
                        type="text"
                        inputmode="decimal"
                        value="{{ getValue('amount') }}"
                        placeholder="0.00"
                        required
                        {{ inputAttrs('amount') }}
                    >
                    @error('amount')
                        <div class="invalid-feedback d-block" id="amount-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </section>

        <p class="small text-body-secondary">
            Payments are final once recorded. The server checks the remaining balance before saving.
        </p>
        @include('core::partials.form-actions', [
            'model' => $payment,
            'routePrefix' => 'core.payments',
            'submitLabel' => 'Record payment',
        ])
    </form>
@endsection
