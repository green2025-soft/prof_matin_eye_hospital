<div class="col-md-6 col-lg-4">
    <label class="form-label small" for="report_product">Product / SKU</label>
    <select class="{{ inputClass('variant_id', 'form-select') }}" id="report_product" name="variant_id" data-ajax-select data-url="{{ route('inventory.reports.products', ['product_type' => $filters['product_type']]) }}" data-placeholder="All products / SKUs" {{ inputAttrs('variant_id', 'report_product') }}>
        <option value=""></option>
        @if ($selectedProduct !== null)
            <option value="{{ $selectedProduct->id }}" selected>{{ $selectedProduct->product->name }} / {{ $selectedProduct->description() }}</option>
        @endif
    </select>
    @error('variant_id')<div class="invalid-feedback d-block" id="report_product-error">{{ $message }}</div>@enderror
</div>
