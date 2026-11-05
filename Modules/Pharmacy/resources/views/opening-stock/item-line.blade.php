<div class="border rounded p-3 mb-2" data-pharmacy-line data-index="{{ $lineIndex }}">
    <fieldset class="border-0 p-0 m-0">
        <legend class="visually-hidden">Item {{ $lineIndex }}</legend>
        <div class="row g-2 align-items-end">
            @include('pharmacy::entries.partials.stock-product-fields')
            <div class="col-6 col-lg-2">
                <label class="form-label small" for="line_{{ $lineIndex }}_price">Unit price</label>
                <input class="{{ inputClass('lines.'.$lineIndex.'.unit_price', 'form-control') }}" id="line_{{ $lineIndex }}_price" name="lines[{{ $lineIndex }}][unit_price]" type="number" min="0" step="0.01" value="{{ $lineValues['unit_price'] ?? '0.00' }}" {{ inputAttrs('lines.'.$lineIndex.'.unit_price', 'line_'.$lineIndex.'_price') }}>
                @error('lines.'.$lineIndex.'.unit_price')
                    <div class="invalid-feedback d-block" id="line_{{ $lineIndex }}_price-error">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-6 col-lg-2">
                <button class="btn btn-outline-danger w-100" type="button" data-pharmacy-remove-line>
                    Remove
                </button>
            </div>
            @include('pharmacy::entries.partials.stock-receiving-fields')
        </div>
    </fieldset>
</div>
