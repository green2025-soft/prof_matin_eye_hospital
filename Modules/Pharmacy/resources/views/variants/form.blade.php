@extends('core::layouts.app')

@php $pageTitle = 'Create Medicine/Product Variant' @endphp
@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle])
    @include('pharmacy::products.navigation', ['selectedProduct' => $selectedProduct, 'productId' => $selectedProduct?->id, 'productType' => $selectedProduct?->type ?? 'medicine', 'activeSection' => 'variants'])

    <form class="app-card p-3 p-md-4" action="{{ $formAction }}" method="POST" data-pharmacy-variant-form data-product-type="{{ $selectedProduct?->type ?? '' }}">
        @csrf
        @if ($formMethod !== 'POST')
            @method($formMethod)
        @endif

        <section class="app-form-section" aria-labelledby="variant-details-heading">
            <h2 class="h6 mb-3" id="variant-details-heading"><i class="fa-solid fa-barcode me-2 text-body-secondary" aria-hidden="true"></i>SKU details</h2>
            <div class="row g-3">
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="sku">SKU <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="{{ inputClass('sku') }}" id="sku" name="sku" type="text" value="{{ getValue('sku', $product_variant) }}" maxlength="100" required autofocus {{ inputAttrs('sku') }}>
                    @error('sku')<div class="invalid-feedback d-block" id="sku-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="name">Variant name</label>
                    <input class="{{ inputClass('name') }}" id="name" name="name" type="text" value="{{ getValue('name', $product_variant) }}" maxlength="255" {{ inputAttrs('name') }}>
                    @error('name')<div class="invalid-feedback d-block" id="name-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="product_id">Product <span class="text-danger" aria-hidden="true">*</span></label>
                    @if ($product_variant->exists)
                        <input type="hidden" name="product_id" value="{{ $product_variant->product_id }}">
                    @endif
                    <select class="{{ inputClass('product_id', 'form-select') }}" id="product_id" name="product_id"
                        data-ajax-select data-url="{{ route('pharmacy.products.options') }}"
                        data-placeholder="Select product" required @disabled($product_variant->exists)
                        {{ inputAttrs('product_id') }}>
                        <option value=""></option>
                        @if ($selectedProduct !== null)
                            <option value="{{ $selectedProduct->id }}" selected>{{ $selectedProduct->name }}</option>
                        @endif
                    </select>
                    @error('product_id')<div class="invalid-feedback d-block" id="product_id-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="active">Status <span class="text-danger" aria-hidden="true">*</span></label>
                    <select class="{{ inputClass('active', 'form-select') }}" id="active" name="active" required {{ inputAttrs('active') }}>
                        <option value="1" @selected((int) getValue('active', $product_variant, 1) === 1)>Active</option>
                        <option value="0" @selected((int) getValue('active', $product_variant, 1) === 0)>Inactive</option>
                    </select>
                    @error('active')<div class="invalid-feedback d-block" id="active-error">{{ $message }}</div>@enderror
                </div>
            </div>
        </section>

        <section class="app-form-section mt-4" aria-labelledby="variant-optic-heading" data-pharmacy-optic-field @if ($selectedProduct?->type !== 'optic') hidden @endif>
            <h2 class="h6 mb-3" id="variant-optic-heading"><i class="fa-solid fa-glasses me-2 text-body-secondary" aria-hidden="true"></i>Frame / lens details</h2>
            <div class="row g-3">
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="color">Color</label>
                    <input class="{{ inputClass('color') }}" id="color" name="color" type="text" value="{{ getValue('color', $product_variant) }}" maxlength="100" {{ inputAttrs('color') }}>
                    @error('color')<div class="invalid-feedback d-block" id="color-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="size">Size</label>
                    <input class="{{ inputClass('size') }}" id="size" name="size" type="text" value="{{ getValue('size', $product_variant) }}" maxlength="100" {{ inputAttrs('size') }}>
                    @error('size')<div class="invalid-feedback d-block" id="size-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="sph">SPH</label>
                    <input class="{{ inputClass('sph') }}" id="sph" name="sph" type="number" value="{{ getValue('sph', $product_variant) }}" inputmode="decimal" min="-9999.99" max="9999.99" step="0.01" {{ inputAttrs('sph') }}>
                    @error('sph')<div class="invalid-feedback d-block" id="sph-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="cyl">CYL</label>
                    <input class="{{ inputClass('cyl') }}" id="cyl" name="cyl" type="number" value="{{ getValue('cyl', $product_variant) }}" inputmode="decimal" min="-9999.99" max="9999.99" step="0.01" {{ inputAttrs('cyl') }}>
                    @error('cyl')<div class="invalid-feedback d-block" id="cyl-error">{{ $message }}</div>@enderror
                </div>
            </div>
            <p class="form-text mb-0 mt-2">Leave unknown powers blank; zero means zero power.</p>
        </section>
        @include('core::partials.form-actions', ['model' => $product_variant, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel])
    </form>
@endsection
