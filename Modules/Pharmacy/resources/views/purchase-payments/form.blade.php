@extends('core::layouts.app')

@php
    $productType = request()->attributes->get('pharmacy_product_type', 'medicine');
    $paymentTitle = ucfirst($productType).' Supplier Payment';
    $invoiceField = 'purchase_id';
@endphp

@section('title', 'New '.$paymentTitle)

@section('content')
    @include('core::partials.heading', ['title' => $paymentTitle])
    <form class="app-card p-3 p-md-4" action="{{ $formAction }}" method="POST" data-pharmacy-payment
        data-invoices-url="{{ route('pharmacy.purchase-payments.invoices') }}">
        @csrf
        <input id="payment_product_type" name="product_type" type="hidden" value="{{ $productType }}">
        @error('product_type')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        <div class="row g-3 mb-3">
            <div class="col-lg-6">
                <label class="form-label" for="payment_supplier">Supplier</label>
                <select class="{{ inputClass('supplier_id', 'form-select') }}" id="payment_supplier" name="supplier_id"
                    data-pharmacy-payment-party data-ajax-select
                    data-url="{{ route('pharmacy.purchase-payments.suppliers', ['product_type' => $productType]) }}"
                    data-placeholder="Select supplier with due" required {{ inputAttrs('supplier_id', 'payment_supplier') }}>
                    <option value=""></option>
                    @if ($selectedSupplier)
                        <option value="{{ $selectedSupplier->id }}" selected>{{ $selectedSupplier->name }}</option>
                    @endif
                </select>
                @error('supplier_id')
                    <div class="invalid-feedback d-block" id="payment_supplier-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-sm-6 col-lg-3">
                <label class="form-label" for="payment_date">Date</label>
                <div class="input-group" data-datepicker>
                    <input class="{{ inputClass('payment_date') }}" id="payment_date" name="payment_date" type="text"
                        inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD"
                        value="{{ getValue('payment_date', null, now()->toDateString()) }}" required data-input
                        {{ inputAttrs('payment_date') }}>
                    <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="payment_date"
                        aria-label="Choose payment date" title="Choose payment date">
                        <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
                    </button>
                </div>
                @error('payment_date')
                    <div class="invalid-feedback d-block" id="payment_date-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-sm-6 col-lg-3">
                <label class="form-label" for="payment_type_id">Payment Type</label>
                <select class="{{ inputClass('payment_type_id', 'form-select') }}" id="payment_type_id"
                    name="payment_type_id" {{ inputAttrs('payment_type_id') }}>
                    @foreach ($paymentTypes as $paymentType)
                        <option value="{{ $paymentType->id }}" @selected((string) getValue('payment_type_id') === (string) $paymentType->id)>
                            {{ $paymentType->name }}
                        </option>
                    @endforeach
                </select>
                @error('payment_type_id')
                    <div class="invalid-feedback d-block" id="payment_type_id-error">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="alert alert-danger d-none" data-pharmacy-payment-error role="alert"></div>
        <p class="small text-body-secondary mb-2" data-pharmacy-payment-loading role="status" hidden>Loading invoices...</p>
        @error('allocations')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th scope="col">Select</th>
                        <th scope="col">Invoice</th>
                        <th scope="col" class="text-end">Due</th>
                        @if ($canDiscount)
                        <th scope="col">Discount</th>
                        @endif
                        <th scope="col">Collection</th>
                    </tr>
                </thead>
                <tbody data-pharmacy-allocations>
                    @foreach ($allocationInputs as $index => $allocation)
                        @php($invoice = $invoices->get($allocation[$invoiceField] ?? 0))
                        @continue($invoice === null)
                        @php($due = moneyDecimal($invoice->outstandingMinor()))
                        <tr data-pharmacy-allocation data-invoice-id="{{ $invoice->id }}" data-due="{{ $due }}">
                            <td>
                                <input class="form-check-input" type="checkbox" aria-label="Pay invoice {{ $invoice->number }}"
                                    data-pharmacy-allocation-select checked>
                            </td>
                            <td>
                                {{ $invoice->number }}
                                <input name="allocations[{{ $index }}][{{ $invoiceField }}]" type="hidden" value="{{ $invoice->id }}">
                            </td>
                            <td class="text-end">{{ $due }}</td>
                            <td>
                                <input class="{{ inputClass('allocations.'.$index.'.amount') }}"
                                    id="allocation-{{ $index }}-amount" name="allocations[{{ $index }}][amount]"
                                    aria-label="Cash for invoice {{ $invoice->number }}" type="number" min="0"
                                    max="{{ $due }}" step="0.01" value="{{ $allocation['amount'] ?? '0.00' }}"
                                    {{ inputAttrs('allocations.'.$index.'.amount', 'allocation-'.$index.'-amount') }}>
                                @error('allocations.'.$index.'.amount')
                                    <div class="invalid-feedback d-block" id="allocation-{{ $index }}-amount-error">{{ $message }}</div>
                                @enderror
                            </td>
                            @if ($canDiscount)
                                <td>
                                    <input class="{{ inputClass('allocations.'.$index.'.discount') }}"
                                        id="allocation-{{ $index }}-discount" name="allocations[{{ $index }}][discount]"
                                        aria-label="Discount for invoice {{ $invoice->number }}" type="number" min="0"
                                        max="{{ $due }}" step="0.01" value="{{ $allocation['discount'] ?? '0.00' }}"
                                        {{ inputAttrs('allocations.'.$index.'.discount', 'allocation-'.$index.'-discount') }}>
                                    @error('allocations.'.$index.'.discount')
                                        <div class="invalid-feedback d-block" id="allocation-{{ $index }}-discount-error">{{ $message }}</div>
                                    @enderror
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <template data-pharmacy-allocation-template>
            <tr data-pharmacy-allocation>
                <td><input class="form-check-input" type="checkbox" data-pharmacy-allocation-select checked></td>
                <td>
                    <span data-pharmacy-allocation-number></span>
                    <input name="allocations[__INDEX__][{{ $invoiceField }}]" type="hidden">
                </td>
                <td class="text-end" data-pharmacy-allocation-due></td>
                @if ($canDiscount)
                    <td>
                        <input class="form-control" name="allocations[__INDEX__][discount]" type="number" min="0" step="0.01" value="0.00">
                    </td>
                @endif
                <td>
                    <input class="form-control" name="allocations[__INDEX__][amount]" type="number" min="0" step="0.01">
                </td>

            </tr>
        </template>
        <button class="btn btn-outline-secondary btn-sm d-none mb-3" type="button" data-pharmacy-more-invoices>Load more</button>
        <div class="row g-3 mt-0">
            <div class="col-md-4 align-self-end">
                <span class="text-body-secondary small">Received Amount</span>
                <strong class="d-block" data-pharmacy-cash-total>0.00</strong>
            </div>
            <div class="col-md-5">
                <label class="form-label" for="notes">Notes</label>
                <textarea class="{{ inputClass('notes') }}" id="notes" name="notes" rows="2" maxlength="2000"
                    {{ inputAttrs('notes') }}>{{ getValue('notes') }}</textarea>
                @error('notes')
                    <div class="invalid-feedback d-block" id="notes-error">{{ $message }}</div>
                @enderror
            </div>

        </div>
        <div class="d-flex gap-2 border-top mt-3 pt-3">
            <a class="btn btn-warning" href="{{ route('pharmacy.purchase-payments.index', ['product_type' => $productType]) }}">Cancel</a>
            <button class="btn btn-primary" type="submit">Collection</button>
        </div>
    </form>
@endsection
