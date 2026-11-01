@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle])
    @include('inventory::products.navigation', ['selectedProduct' => $selectedProduct, 'productId' => $selectedProduct?->id, 'productType' => $selectedProduct?->type ?? 'medicine', 'activeSection' => 'units'])

    <form class="app-card p-3 p-md-4" action="{{ $formAction }}" method="POST">
        @csrf
        @if ($formMethod !== 'POST')
            @method($formMethod)
        @endif

        <section class="app-form-section" aria-labelledby="unit-conversion-heading">
            <h2 class="h6 mb-3" id="unit-conversion-heading"><i class="fa-solid fa-boxes-stacked me-2 text-body-secondary" aria-hidden="true"></i>Unit conversion</h2>
            <div class="row g-3">
                <div class="col-sm-6 col-xl-4">
                    <label class="form-label" for="product_id">Product <span class="text-danger" aria-hidden="true">*</span></label>
                    @if ($product_unit->exists)
                        <input type="hidden" name="product_id" value="{{ $product_unit->product_id }}">
                    @endif
                    <select class="{{ inputClass('product_id', 'form-select') }}" id="product_id" name="product_id" data-ajax-select data-url="{{ route('inventory.products.options') }}" data-placeholder="Select product" required @disabled($product_unit->exists) {{ inputAttrs('product_id') }}>
                        <option value=""></option>
                        @if ($selectedProduct !== null)
                            <option value="{{ $selectedProduct->id }}" selected>{{ $selectedProduct->name }}</option>
                        @endif
                    </select>
                    @error('product_id')<div class="invalid-feedback d-block" id="product_id-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-xl-4">
                    <label class="form-label" for="unit_id">Unit <span class="text-danger" aria-hidden="true">*</span></label>
                    @if ($product_unit->exists)
                        <input type="hidden" name="unit_id" value="{{ $product_unit->unit_id }}">
                    @endif
                    <select class="{{ inputClass('unit_id', 'form-select') }}" id="unit_id" name="unit_id" data-ajax-select data-url="{{ route('inventory.units.options') }}" data-placeholder="Select unit" required @disabled($product_unit->exists) {{ inputAttrs('unit_id') }}>
                        <option value=""></option>
                        @if ($selectedUnit !== null)
                            <option value="{{ $selectedUnit->id }}" selected>{{ $selectedUnit->name }}</option>
                        @endif
                    </select>
                    @error('unit_id')<div class="invalid-feedback d-block" id="unit_id-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-xl-4">
                    <label class="form-label" for="factor">Pieces per unit <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="{{ inputClass('factor') }}" id="factor" name="factor" type="number" value="{{ $unitLocked ? $product_unit->factor : getValue('factor', $product_unit, 1) }}" min="1" max="1000000" step="1" inputmode="numeric" required @readonly($unitLocked) {{ inputAttrs('factor', 'factor', 'factor-help') }}>
                    <div class="form-text" id="factor-help">{{ $unitLocked ? 'Used conversions are locked.' : 'Piece: 1 · Strip of 10: 10.' }}</div>
                    @error('factor')<div class="invalid-feedback d-block" id="factor-error">{{ $message }}</div>@enderror
                </div>
            </div>
        </section>
        @include('core::partials.form-actions', ['model' => $product_unit, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel])
    </form>
@endsection
