@php
    $productType = request()->attributes->get('pharmacy_product_type', 'medicine');
@endphp
@extends('core::layouts.app')

@section('title', ucfirst($productType).' Purchase')

@section('content')
    @php
        $savedLines = $entry->exists ? $entry->lines->keyBy('id') : collect();
        $defaultVatMode = $pharmacySettings['vat_enabled'] ? $pharmacySettings['vat_mode'] : ($pharmacyPurchase ? 'exclusive' : 'none');
        $purchaseVatMode = getValue('vat_mode', null, $defaultVatMode);
        $purchaseVatMode = in_array($purchaseVatMode, ['none', 'inclusive', 'exclusive'], true) ? $purchaseVatMode : 'exclusive';
        $purchaseVatPercent = $pharmacySettings['vat_enabled'] ? $pharmacySettings['vat_percent'] : '0.00';
        $showPurchaseSalePrice = $canUpdateProductPrices && $itemFields['sale_price'];
        $editingPurchase = $formMethod !== 'POST';
        $showDiscountColumn = $itemFields['discount'] || $editingPurchase;
        $showVatColumn = $itemFields['vat'] || $editingPurchase;
        $showExpiryColumn = $pharmacyPurchase && ($itemFields['expiry'] || $editingPurchase);
        $lineColumnCount = 5 + (int) $showDiscountColumn + (int) $showVatColumn + (int) $showExpiryColumn;
    @endphp

    <form
        class="app-card p-3 app-pharmacy-entry app-pharmacy-trade-layout"
        action="{{ $formAction }}"
        method="POST"
        data-pharmacy-entry
        data-pharmacy-trade
        data-type="purchase"
    >
        @csrf
        @if ($formMethod !== 'POST')
            @method($formMethod)
        @endif
        <input id="pharmacy_product_type" name="product_type" type="hidden" value="{{ $productType }}">

        <div class="row g-3 align-items-start">
            <div class="col-12 col-xl-9 app-pharmacy-trade-main">
                <section class="app-form-section" aria-labelledby="purchase-invoice-heading">
                    <h2 class="app-form-section-title" id="purchase-invoice-heading">
                        <span class="app-form-section-icon"><i class="fa-solid fa-file-invoice" aria-hidden="true"></i></span>
                        Purchase details
                    </h2>
                    <div class="row g-2">
                        <div class="col-md-6 col-xl-4">
                            <label class="form-label" for="pharmacy_supplier">Supplier <span class="text-danger" aria-hidden="true">*</span></label>
                            <select class="{{ inputClass('supplier_id', 'form-select') }}" id="pharmacy_supplier" name="supplier_id" data-ajax-select data-url="{{ route('pharmacy.suppliers.options', ['type' => $productType]) }}" data-placeholder="Select supplier" required {{ inputAttrs('supplier_id', 'pharmacy_supplier') }}>
                                <option value=""></option>
                                @if ($selectedSupplier)
                                    <option value="{{ $selectedSupplier->id }}" selected>{{ $selectedSupplier->name }}</option>
                                @endif
                            </select>
                            @error('supplier_id')
                                <div class="invalid-feedback d-block" id="pharmacy_supplier-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 col-xl-3">
                            <label class="form-label" for="supplier_invoice_no">Supplier invoice no.</label>
                            <input class="{{ inputClass('supplier_invoice_no') }}" id="supplier_invoice_no" name="supplier_invoice_no" type="text" maxlength="80" autocomplete="off" value="{{ getValue('supplier_invoice_no', $entry) }}" {{ inputAttrs('supplier_invoice_no') }}>
                            @error('supplier_invoice_no')
                                <div class="invalid-feedback d-block" id="supplier_invoice_no-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-6 col-xl-3">
                            @include('pharmacy::entries.partials.date-field')
                        </div>
                        <div class="col-6 col-xl-2">
                            @include('pharmacy::entries.partials.payment-type-field')
                        </div>
                    </div>
                </section>

                @include('pharmacy::purchases.items')
            </div>
            <aside class="col-12 col-xl-3 app-pharmacy-trade-sidebar" aria-label="Payment and summary">
                @include('pharmacy::purchases.payment-summary')
                @include('pharmacy::entries.partials.form-actions')
            </aside>
        </div>
    </form>

    @if ($pharmacyPurchase)
        @include('pharmacy::purchases.pack-modal')
    @endif
@endsection
