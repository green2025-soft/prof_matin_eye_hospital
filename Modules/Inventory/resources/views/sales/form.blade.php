@extends('core::layouts.app')

@section('title', ucfirst(request()->attributes->get('inventory_product_type', 'medicine')).' Sale')

@section('content')
    @php
        $showDiscountColumn = $itemFields['discount'] || $document->exists;
        $showVatColumn = $inventorySettings['vat_enabled'];
        $lineColumnCount = 5 + (int) $showDiscountColumn + (int) $showVatColumn;
    @endphp

    @include('core::partials.heading', ['title' => ($document->exists ? 'Edit ' : 'New ').ucfirst(request()->attributes->get('inventory_product_type', 'medicine')).' Sale'.($document->exists ? ' — '.$document->number : '')])

    <form
        class="app-card p-3 p-md-4 app-inventory-entry"
        action="{{ $formAction }}"
        method="POST"
        data-inventory-document
        data-inventory-trade
        data-type="sale"
        data-inventory-revision-stock="{{ $revisionStock->toJson() }}"
        data-allow-without-stock="{{ $inventorySettings[request()->attributes->get('inventory_product_type', 'medicine').'_sale_without_stock_enabled'] ? '1' : '0' }}"
        data-vat-enabled="{{ $inventorySettings['vat_enabled'] ? '1' : '0' }}"
        data-vat-mode="{{ $inventorySettings['vat_mode'] }}"
        data-vat-percent="{{ $inventorySettings['vat_percent'] }}"
    >
        @csrf
        @if ($formMethod !== 'POST')
            @method($formMethod)
        @endif
        <input id="inventory_product_type" name="product_type" type="hidden" value="{{ request()->attributes->get('inventory_product_type', 'medicine') }}">

        <section class="app-form-section" aria-labelledby="sale-invoice-heading">
            <h2 class="app-form-section-title" id="sale-invoice-heading">
                <span class="app-form-section-icon"><i class="fa-solid fa-file-invoice" aria-hidden="true"></i></span>
                Sale details
            </h2>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="inventory_customer">Customer <span class="text-danger" aria-hidden="true">*</span></label>
                    <div class="d-flex gap-2">
                        <select class="{{ inputClass('customer_id', 'form-select') }}" id="inventory_customer" name="customer_id" data-ajax-select data-url="{{ route('inventory.customers.options', ['new_sale' => 1]) }}" data-label-field="text" data-placeholder="Patient ID, name or mobile" required {{ inputAttrs('customer_id', 'inventory_customer') }}>
                            <option value=""></option>
                            @if ($selectedCustomer)
                                <option value="{{ $selectedCustomer['id'] }}" selected>{{ $selectedCustomer['text'] }}</option>
                            @endif
                        </select>
                        @if ($canCreateCustomer)
                            <button class="btn btn-outline-primary text-nowrap" type="button" data-bs-toggle="modal" data-bs-target="#inventory-customer-modal">
                                <i class="fa-solid fa-plus me-1" aria-hidden="true"></i>New
                            </button>
                        @endif
                    </div>
                    @error('customer_id')
                        <div class="invalid-feedback d-block" id="inventory_customer-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-sm-6 col-md-3">
                    @include('inventory::entries.partials.date-field')
                </div>
                <div class="col-sm-6 col-md-3">
                    @include('inventory::entries.partials.payment-type-field')
                </div>
            </div>
        </section>

        @include('inventory::sales.items')
        @include('inventory::sales.payment-summary')
        @include('inventory::entries.partials.form-actions')
    </form>

    @if ($canCreateCustomer)
        @include('inventory::customers.modal')
    @endif
@endsection
