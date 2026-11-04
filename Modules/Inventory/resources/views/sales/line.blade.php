@php
    $originalLineId = $lineValues['original_line_id'] ?? $savedLine?->id;
    $productUnitId = $hasOldLines ? ($lineValues['product_unit_id'] ?? '') : ($savedLine?->product_unit_id ?? '');
    $quantity = $hasOldLines ? ($lineValues['quantity'] ?? '') : ($savedLine?->quantity ?? '');
    $unitPrice = $hasOldLines ? ($lineValues['unit_price'] ?? '') : ($savedLine === null ? '' : moneyDecimal($savedLine->unit_price));
    $discountValue = $itemFields['discount'] && $hasOldLines ? ($lineValues['discount'] ?? '')
        : ($savedLine === null && $itemFields['discount'] ? '' : moneyDecimal($savedLine?->discount ?? 0));
    $availableStock = ($stockQuantities->get($variant?->id) ?? 0) + ($revisionStock->get($variant?->id) ?? 0);
@endphp

<fieldset
    disabled
    data-inventory-line
    data-index="{{ $lineIndex }}"
    data-sale-price="{{ $variant?->product?->sale_price ?? '0.00' }}"
>
    <legend class="visually-hidden">Item details</legend>
    <div>
        <input name="lines[{{ $lineIndex }}][variant_id]" type="hidden" value="{{ $lineValues['variant_id'] ?? $variant?->id ?? '' }}">
        <input name="lines[{{ $lineIndex }}][original_line_id]" type="hidden" value="{{ $originalLineId }}">
        <strong class="app-inventory-entry-name" hidden>{{ $variant?->product?->name ?? 'Select an available product' }}</strong>
        <small class="app-inventory-entry-code text-body-secondary" hidden>{{ $variant?->description() }}</small>
        @error('lines.'.$lineIndex.'.variant_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        @error('lines.'.$lineIndex.'.original_line_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>
    <div class="row g-2">
        <div class="col-sm-6 col-lg-4 col-xl-2">
            <label class="form-label" for="trade_{{ $lineIndex }}_unit">Unit <span class="text-danger" aria-hidden="true">*</span></label>
            <select class="{{ inputClass('lines.'.$lineIndex.'.product_unit_id', 'form-select') }}" id="trade_{{ $lineIndex }}_unit" name="lines[{{ $lineIndex }}][product_unit_id]" aria-keyshortcuts="F4 Alt+B Alt+P" title="Unit (F4, Alt+B Box, Alt+P Pcs)" required {{ inputAttrs('lines.'.$lineIndex.'.product_unit_id', 'trade_'.$lineIndex.'_unit') }}>
                <option value="">Unit</option>
                @foreach ($variant?->product?->units ?? [] as $unit)
                    <option value="{{ $unit->id }}" data-factor="{{ $unit->factor }}" data-unit-name="{{ $unit->unit->symbol ?: $unit->unit->name }}" data-symbol="{{ $unit->unit->symbol }}" @selected((string) $productUnitId === (string) $unit->id)>{{ $unit->unit->symbol ?: $unit->unit->name }} × {{ $unit->factor }}</option>
                @endforeach
            </select>
            @error('lines.'.$lineIndex.'.product_unit_id')
                <div class="invalid-feedback d-block" id="trade_{{ $lineIndex }}_unit-error">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-sm-6 col-lg-4 col-xl-2">
            <label class="form-label" for="trade_{{ $lineIndex }}_quantity">Qty <span class="text-danger" aria-hidden="true">*</span></label>
            <div>
                <input class="{{ inputClass('lines.'.$lineIndex.'.quantity', 'form-control text-end') }}" id="trade_{{ $lineIndex }}_quantity" name="lines[{{ $lineIndex }}][quantity]" type="number" inputmode="numeric" min="1" max="1000000000" step="1" value="{{ $quantity }}" required {{ inputAttrs('lines.'.$lineIndex.'.quantity', 'trade_'.$lineIndex.'_quantity') }}>
            </div>
            @error('lines.'.$lineIndex.'.quantity')<div class="invalid-feedback d-block" id="trade_{{ $lineIndex }}_quantity-error">{{ $message }}</div>@enderror
        </div>
        <div class="col-sm-6 col-lg-4 col-xl-2">
            <label class="form-label" for="trade_{{ $lineIndex }}_stock">Stock (Pcs)</label>
            <output class="form-control text-end" id="trade_{{ $lineIndex }}_stock" data-inventory-product-stock>{{ $availableStock }}</output>
        </div>
        <div class="col-sm-6 col-lg-4 col-xl-2">
            <label class="form-label" for="trade_{{ $lineIndex }}_price">Rate <span class="text-danger" aria-hidden="true">*</span></label>
            <div>
                <input class="{{ inputClass('lines.'.$lineIndex.'.unit_price', 'form-control text-end') }}" id="trade_{{ $lineIndex }}_price" name="lines[{{ $lineIndex }}][unit_price]" type="number" inputmode="decimal" min="0" step="0.01" value="{{ $unitPrice }}" required {{ inputAttrs('lines.'.$lineIndex.'.unit_price', 'trade_'.$lineIndex.'_price') }}>
            </div>
            @error('lines.'.$lineIndex.'.unit_price')<div class="invalid-feedback d-block" id="trade_{{ $lineIndex }}_price-error">{{ $message }}</div>@enderror
        </div>
        <fieldset class="col-sm-6 col-lg-4 col-xl-2" @unless ($itemFields['discount']) disabled hidden data-inventory-hidden-item-field="discount" @endunless>
            <legend class="visually-hidden">Discount</legend>
            <label class="form-label" for="trade_{{ $lineIndex }}_discount">Discount</label>
            <input class="{{ inputClass('lines.'.$lineIndex.'.discount', 'form-control text-end') }}" id="trade_{{ $lineIndex }}_discount" name="lines[{{ $lineIndex }}][discount]" type="number" inputmode="decimal" min="0" step="0.01" placeholder="0" value="{{ $discountValue }}" {{ inputAttrs('lines.'.$lineIndex.'.discount', 'trade_'.$lineIndex.'_discount') }}>
            @error('lines.'.$lineIndex.'.discount')<div class="invalid-feedback d-block" id="trade_{{ $lineIndex }}_discount-error">{{ $message }}</div>@enderror
        </fieldset>
        <div class="col-sm-6 col-lg-4 col-xl-2">
            <label class="form-label" for="trade_{{ $lineIndex }}_amount">Amount</label>
            <output class="form-control text-end fw-semibold" id="trade_{{ $lineIndex }}_amount" data-inventory-row-amount>0.00</output>
        </div>
    </div>
</fieldset>
