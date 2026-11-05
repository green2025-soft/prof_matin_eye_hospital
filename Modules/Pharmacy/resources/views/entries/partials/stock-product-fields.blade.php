<div class="col-lg-4">
    <label class="form-label small" for="line_{{ $lineIndex }}_variant">Product / SKU</label>
    <select class="{{ inputClass('lines.'.$lineIndex.'.variant_id', 'form-select') }}" id="line_{{ $lineIndex }}_variant" name="lines[{{ $lineIndex }}][variant_id]" data-ajax-select data-url="{{ route('pharmacy.items.options') }}" data-parent-select="pharmacy_product_type" data-parent-field="product_type" data-placeholder="Search product or SKU" required {{ inputAttrs('lines.'.$lineIndex.'.variant_id', 'line_'.$lineIndex.'_variant') }}>
        <option value=""></option>
        @if ($variant)
            <option value="{{ $variant->id }}" data-purchase-price="{{ $variant->product->purchase_price }}" selected>
                {{ $variant->product->name }} / {{ $variant->sku }}
            </option>
        @endif
    </select>
    @error('lines.'.$lineIndex.'.variant_id')
        <div class="invalid-feedback d-block" id="line_{{ $lineIndex }}_variant-error">{{ $message }}</div>
    @enderror
</div>
<div class="col-6 col-lg-2">
    <label class="form-label small" for="line_{{ $lineIndex }}_unit">Unit</label>
    <select class="{{ inputClass('lines.'.$lineIndex.'.product_unit_id', 'form-select') }}" id="line_{{ $lineIndex }}_unit" name="lines[{{ $lineIndex }}][product_unit_id]" required {{ inputAttrs('lines.'.$lineIndex.'.product_unit_id', 'line_'.$lineIndex.'_unit') }}>
        <option value="">Unit</option>
        @if ($variant)
            @foreach ($variant->product->units as $unit)
                <option value="{{ $unit->id }}" data-factor="{{ $unit->factor }}" @selected((string) ($lineValues['product_unit_id'] ?? '') === (string) $unit->id)>
                    {{ $unit->unit->symbol ?: $unit->unit->name }} × {{ $unit->factor }}
                </option>
            @endforeach
        @endif
    </select>
    @error('lines.'.$lineIndex.'.product_unit_id')
        <div class="invalid-feedback d-block" id="line_{{ $lineIndex }}_unit-error">{{ $message }}</div>
    @enderror
</div>
<div class="col-6 col-lg-2">
    <label class="form-label small" for="line_{{ $lineIndex }}_quantity">Quantity</label>
    <input class="{{ inputClass('lines.'.$lineIndex.'.quantity', 'form-control') }}" id="line_{{ $lineIndex }}_quantity" name="lines[{{ $lineIndex }}][quantity]" type="number" min="1" step="1" value="{{ $lineValues['quantity'] ?? 1 }}" required {{ inputAttrs('lines.'.$lineIndex.'.quantity', 'line_'.$lineIndex.'_quantity') }}>
    @error('lines.'.$lineIndex.'.quantity')
        <div class="invalid-feedback d-block" id="line_{{ $lineIndex }}_quantity-error">{{ $message }}</div>
    @enderror
</div>
