@php
    $productType = request()->attributes->get('pharmacy_product_type', 'medicine');
@endphp
@extends('core::layouts.app')

@section('title', ucfirst($productType).' Sale')

@section('content')
    @php
        $savedLines = $savedLines->keyBy('id');
        $itemRows = $hasOldLines ? $lineInputs : $savedLines->take(100);

        // Existing invoices keep their saved VAT settings when edited.
        if ($entry->exists) {
            $pharmacySettings['vat_enabled']    = $entry->vat_mode !== 'none';
            $pharmacySettings['vat_mode']       = $entry->vat_mode;
            $pharmacySettings['vat_percent']    = $entry->vat_percent;
        }
        $showDiscountColumn = $itemFields['discount'] || $entry->exists;
        $showVatColumn      = $pharmacySettings['vat_enabled'];
        $lineColumnCount    = 5 + (int) $showDiscountColumn + (int) $showVatColumn;

        $saleOptions = [
            'type'              => 'sale',
            'revisionStock'     => $revisionStock,
            'allowWithoutStock' => (bool) $pharmacySettings[$productType.'_sale_without_stock_enabled'],
            'vatMode'           => $showVatColumn ? $pharmacySettings['vat_mode'] : 'none',
            'vatPercent'        => $pharmacySettings['vat_percent'],
        ];
    @endphp

    <form
        class="app-card p-3 app-pharmacy-entry app-pharmacy-trade-layout"
        action="{{ $formAction }}"
        method="POST"
    >
        @csrf
        @if ($formMethod !== 'POST')
            @method($formMethod)
        @endif
        <input id="pharmacy_product_type" name="product_type" type="hidden" value="{{ $productType }}">
        <script type="application/json" id="pharmacy-sale-options">{!! htmlJson($saleOptions) !!}</script>

        <div class="row g-3 align-items-start">
            <div class="col-12 col-xl-9 app-pharmacy-trade-main">
                <section class="app-form-section" aria-labelledby="sale-invoice-heading">
                    <h2 class="app-form-section-title" id="sale-invoice-heading">
                        <span class="app-form-section-icon"><i class="fa-solid fa-file-invoice" aria-hidden="true"></i></span>
                        Sale details
                    </h2>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label" for="pharmacy_customer">Customer <span class="text-danger" aria-hidden="true">*</span></label>
                            <div class="d-flex gap-2">
                                <select class="{{ inputClass('customer_id', 'form-select') }}" id="pharmacy_customer" name="customer_id" data-ajax-select data-url="{{ route('pharmacy.customers.options', ['new_sale' => 1]) }}" data-label-field="text" data-placeholder="Patient ID, name or mobile" required {{ inputAttrs('customer_id', 'pharmacy_customer') }}>
                                    <option value=""></option>
                                    @if ($selectedCustomer)
                                        <option value="{{ $selectedCustomer['id'] }}" selected>{{ $selectedCustomer['text'] }}</option>
                                    @endif
                                </select>
                                @if ($canCreateCustomer)
                                    <button class="btn btn-outline-primary text-nowrap" type="button" data-bs-toggle="modal" data-bs-target="#pharmacy-customer-modal">
                                        <i class="fa-solid fa-plus me-1" aria-hidden="true"></i>New
                                    </button>
                                @endif
                            </div>
                            @error('customer_id')
                                <div class="invalid-feedback d-block" id="pharmacy_customer-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-6 col-md-3">
                            @include('pharmacy::entries.partials.date-field')
                        </div>
                        <div class="col-6 col-md-3">
                            @include('pharmacy::entries.partials.payment-type-field')
                        </div>
                    </div>
                </section>

                @include('pharmacy::sales.items')
            </div>
            <aside class="col-12 col-xl-3 app-pharmacy-trade-sidebar" aria-label="Payment and summary">
                @include('pharmacy::sales.payment-summary')
                @include('pharmacy::entries.partials.form-actions')
            </aside>
        </div>
    </form>

    @if ($canCreateCustomer)
        @include('pharmacy::customers.modal')
    @endif
@endsection
