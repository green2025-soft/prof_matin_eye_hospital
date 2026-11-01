@extends('core::layouts.app')

@section('title', ucfirst(request()->attributes->get('inventory_product_type', 'medicine')).' Purchase')

@section('content')
    @php
        $showPurchaseSalePrice = $canUpdateProductPrices && $itemFields['sale_price'];
        $editingPurchase = $formMethod !== 'POST';
        $showDiscountColumn = $itemFields['discount'] || $editingPurchase;
        $showVatColumn = $itemFields['vat'] || $editingPurchase;
        $showExpiryColumn = $pharmacyPurchase && ($itemFields['expiry'] || $editingPurchase);
        $lineColumnCount = 5 + (int) $showDiscountColumn + (int) $showVatColumn + (int) $showExpiryColumn;
    @endphp

    @include('core::partials.heading', ['title' => ($document->exists ? 'Edit ' : 'New ').ucfirst(request()->attributes->get('inventory_product_type', 'medicine')).' Purchase'.($document->exists ? ' — '.$document->number : '')])

    <form
        class="app-card p-3 p-md-4 app-inventory-entry"
        action="{{ $formAction }}"
        method="POST"
        data-inventory-document
        data-inventory-trade
        @if ($pharmacyPurchase) data-inventory-pharmacy-purchase @endif
        @if ($showPurchaseSalePrice) data-inventory-sale-price-entry @endif
        data-type="purchase"
        data-vat-enabled="1"
        data-vat-mode="{{ $purchaseVatMode }}"
        data-vat-percent="{{ $pharmacyPurchase ? '0.00' : $purchaseVatPercent }}"
    >
        @csrf
        @if ($formMethod !== 'POST')
            @method($formMethod)
        @endif
        <input id="inventory_product_type" name="product_type" type="hidden" value="{{ request()->attributes->get('inventory_product_type', 'medicine') }}">

        <section class="app-form-section" aria-labelledby="purchase-invoice-heading">
            <h2 class="app-form-section-title" id="purchase-invoice-heading">
                <span class="app-form-section-icon"><i class="fa-solid fa-file-invoice" aria-hidden="true"></i></span>
                Purchase details
            </h2>
            <div class="row g-3">
                <div class="col-md-6 col-xl-4">
                    <label class="form-label" for="inventory_supplier">Supplier <span class="text-danger" aria-hidden="true">*</span></label>
                    <select class="{{ inputClass('supplier_id', 'form-select') }}" id="inventory_supplier" name="supplier_id" data-ajax-select data-url="{{ route('inventory.suppliers.options', ['type' => request()->attributes->get('inventory_product_type', 'medicine')]) }}" data-placeholder="Select supplier" required {{ inputAttrs('supplier_id', 'inventory_supplier') }}>
                        <option value=""></option>
                        @if ($selectedSupplier)
                            <option value="{{ $selectedSupplier->id }}" selected>{{ $selectedSupplier->name }}</option>
                        @endif
                    </select>
                    @error('supplier_id')
                        <div class="invalid-feedback d-block" id="inventory_supplier-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 col-xl-3">
                    <label class="form-label" for="supplier_invoice_no">Supplier invoice no.</label>
                    <input class="{{ inputClass('supplier_invoice_no') }}" id="supplier_invoice_no" name="supplier_invoice_no" type="text" maxlength="80" autocomplete="off" value="{{ getValue('supplier_invoice_no', $document) }}" {{ inputAttrs('supplier_invoice_no') }}>
                    @error('supplier_invoice_no')
                        <div class="invalid-feedback d-block" id="supplier_invoice_no-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-sm-6 col-xl-3">
                    @include('inventory::entries.partials.date-field')
                </div>
                <div class="col-sm-6 col-xl-2">
                    @include('inventory::entries.partials.payment-type-field')
                </div>
            </div>
        </section>

        @include('inventory::purchases.items')
        @include('inventory::purchases.payment-summary')
        @include('inventory::entries.partials.form-actions')
    </form>

    @if ($pharmacyPurchase)
        @include('inventory::purchases.pack-modal')
    @endif
@endsection
