@extends('core::layouts.app')

@php $pageTitle = 'Create Medicine/Product' @endphp

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle])
    @include('pharmacy::products.navigation', ['selectedProduct' => $product->exists ? $product : null, 'productId' => $product->exists ? $product->id : null, 'productType' => $defaultType, 'activeSection' => 'details'])

    <div class="row g-3 align-items-start">
        <div class="{{ $canViewSetup ? 'col-xl-9' : 'col-12' }}">
            <form class="app-card p-3 p-md-4" action="{{ $formAction }}" method="POST" data-pharmacy-product-form>
                @csrf
                <input id="type" name="type" type="hidden" value="{{ $defaultType }}">
                @error('type')<div class="invalid-feedback d-block" id="type-error">{{ $message }}</div>@enderror
                @if ($formMethod !== 'POST')
                    @method($formMethod)
                @endif

                <section class="app-form-section" aria-labelledby="product-details-heading">
                    <h2 class="h6 mb-3" id="product-details-heading"><i class="fa-solid fa-box-open text-body-secondary me-2" aria-hidden="true"></i>Product details</h2>
                    <div class="row g-3">
                        <div class="col-sm-6 col-xl-3">
                            <label class="form-label" for="name">Name <span class="text-danger" aria-hidden="true">*</span></label>
                            <input class="{{ inputClass('name') }}" id="name" name="name" type="text" value="{{ getValue('name', $product) }}" maxlength="255" required autofocus {{ inputAttrs('name') }}>
                            @error('name')<div class="invalid-feedback d-block" id="name-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <label class="form-label" for="product_sku">ID / SKU</label>
                            <input class="form-control" id="product_sku" type="text" value="{{ $product->exists ? ($product->primaryVariant?->sku ?? $product->code) : '' }}" placeholder="Auto" readonly>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <label class="form-label" for="barcode">Barcode</label>
                            <input class="{{ inputClass('barcode') }}" id="barcode" name="barcode" type="text"
                                value="{{ getValue('barcode', $product) }}" maxlength="100" autocomplete="off" autocapitalize="off" spellcheck="false"
                                {{ inputAttrs('barcode') }}>
                            @error('barcode')
                                <div class="invalid-feedback d-block" id="barcode-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <label class="form-label" for="category_id">Category / generic</label>
                            <select class="{{ inputClass('category_id', 'form-select') }}" id="category_id" name="category_id"
                                data-ajax-select data-url="{{ route('pharmacy.categories.options') }}"
                                data-parent-select="type" data-parent-field="type"
                                data-placeholder="Select category / generic"
                                {{ inputAttrs('category_id') }}>
                                <option value=""></option>
                                @if ($selectedCategory !== null)
                                    <option value="{{ $selectedCategory->id }}" selected>{{ $selectedCategory->name }}</option>
                                @endif
                            </select>
                            @error('category_id')<div class="invalid-feedback d-block" id="category_id-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6 col-xl-3" data-pharmacy-medicine-field @if ($defaultType !== 'medicine') hidden @endif>
                            <label class="form-label" for="dosage_id">Dose Type</label>
                            <select class="{{ inputClass('dosage_id', 'form-select') }}" id="dosage_id" name="dosage_id"
                                data-ajax-select data-url="{{ route('pharmacy.dosages.options') }}"
                                data-placeholder="Select dose type"
                                {{ inputAttrs('dosage_id') }}>
                                <option value=""></option>
                                @if ($selectedDosage !== null)
                                    <option value="{{ $selectedDosage->id }}" selected>{{ $selectedDosage->name }}</option>
                                @endif
                            </select>
                            @error('dosage_id')<div class="invalid-feedback d-block" id="dosage_id-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6 col-xl-3" data-pharmacy-medicine-field @if ($defaultType !== 'medicine') hidden @endif>
                            <label class="form-label" for="strength">Strength</label>
                            <input class="{{ inputClass('strength') }}" id="strength" name="strength" type="text" value="{{ getValue('strength', $product) }}" maxlength="255"  {{ inputAttrs('strength') }}>
                            @error('strength')<div class="invalid-feedback d-block" id="strength-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <label class="form-label" for="supplier_id">Supplier</label>
                            <select class="{{ inputClass('supplier_id', 'form-select') }}" id="supplier_id" name="supplier_id"
                                data-ajax-select data-url="{{ route('pharmacy.suppliers.options') }}"
                                data-parent-select="type" data-parent-field="type"
                                data-placeholder="Select supplier"
                                {{ inputAttrs('supplier_id') }}>
                                <option value=""></option>
                                @if ($selectedSupplier !== null)
                                    <option value="{{ $selectedSupplier->id }}" selected>{{ $selectedSupplier->name }}</option>
                                @endif
                            </select>
                            @error('supplier_id')<div class="invalid-feedback d-block" id="supplier_id-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <label class="form-label" for="shelf_id">Shelf Location</label>
                            <select class="{{ inputClass('shelf_id', 'form-select') }}" id="shelf_id" name="shelf_id"
                                data-ajax-select data-url="{{ route('pharmacy.shelf.options') }}"
                                data-parent-select="type" data-parent-field="type"
                                data-placeholder="Select shelf location"
                                {{ inputAttrs('shelf_id') }}>
                                <option value=""></option>
                                @if ($selectedShelf !== null)
                                    <option value="{{ $selectedShelf->id }}" selected>{{ $selectedShelf->name }}</option>
                                @endif
                            </select>
                            @error('shelf_id')<div class="invalid-feedback d-block" id="shelf_id-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </section>

                <section class="app-form-section mt-4" aria-labelledby="product-prices-heading">
                    <h2 class="h6 mb-3" id="product-prices-heading"><i class="fa-solid fa-tags text-body-secondary me-2" aria-hidden="true"></i>Prices & stock</h2>
                    <div class="row g-3">
                        <div class="col-sm-6 col-xl-3">
                            <label class="form-label" for="purchase_price">Purchase price <span class="text-danger" aria-hidden="true">*</span></label>
                            <input class="{{ inputClass('purchase_price') }}" id="purchase_price" name="purchase_price" type="text"
                                value="{{ getValue('purchase_price', $product, '0.00') }}" maxlength="13" required inputmode="decimal"
                                {{ inputAttrs('purchase_price') }}>
                            @error('purchase_price')<div class="invalid-feedback d-block" id="purchase_price-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <label class="form-label" for="sale_price">Sale price <span class="text-danger" aria-hidden="true">*</span></label>
                            <input class="{{ inputClass('sale_price') }}" id="sale_price" name="sale_price" type="text"
                                value="{{ getValue('sale_price', $product, '0.00') }}" maxlength="13" required inputmode="decimal"
                                {{ inputAttrs('sale_price') }}>
                            @error('sale_price')<div class="invalid-feedback d-block" id="sale_price-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <label class="form-label" for="reorder_level">Re-order Level (Pcs) <span class="text-danger" aria-hidden="true">*</span></label>
                            <input class="{{ inputClass('reorder_level') }}" id="reorder_level" name="reorder_level" type="number"
                                value="{{ getValue('reorder_level', $product, 0) }}" required min="0" max="1000000000" step="1" inputmode="numeric"
                                {{ inputAttrs('reorder_level') }}>
                            @error('reorder_level')<div class="invalid-feedback d-block" id="reorder_level-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </section>

                @include('core::partials.form-actions', ['model' => $product, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel])
            </form>
        </div>

        @if ($canViewSetup)
            <aside class="col-xl-3">
                @include('pharmacy::products.stock-setup')
            </aside>
        @endif
    </div>
@endsection
