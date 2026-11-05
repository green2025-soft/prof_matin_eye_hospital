@php
    $quantityHelpId = 'trade_'.$lineIndex.'_quantity-unit';
    $discountUnitLabel = 'Unit';
    $originalLineId = $lineValues['original_line_id'] ?? $savedLine?->id;
    $selectedProfile = $variant?->product?->units->firstWhere('id', $lineValues['product_unit_id'] ?? null);
    $unitId = $lineValues['unit_id'] ?? ($hasOldLines ? $selectedProfile?->unit_id : $savedLine?->productUnit?->unit_id);
    $unitFactor = $lineValues['unit_factor'] ?? ($hasOldLines ? $selectedProfile?->factor : $savedLine?->factor);
    $quantity = $hasOldLines ? ($lineValues['quantity'] ?? '') : ($savedLine?->quantity ?? '');
    $unitPrice = $hasOldLines ? ($lineValues['unit_price'] ?? '') : ($savedLine === null ? '' : moneyDecimal($savedLine->unit_price));
    $expiryDate = $itemFields['expiry'] && $hasOldLines ? ($lineValues['expiry_date'] ?? '') : $savedLine?->expiry_date?->toDateString();
@endphp

<fieldset
    disabled
    data-pharmacy-line
    data-index="{{ $lineIndex }}"
    data-product-id="{{ $variant?->product_id }}"
    data-purchase-price="{{ $variant?->product?->purchase_price ?? '0.00' }}"
    data-unit-profiles="{{ $variant ? $variant->product->units->map(fn ($unit) => ['id' => $unit->id, 'unit_id' => $unit->unit_id, 'factor' => $unit->factor])->toJson() : '[]' }}"
>
    <legend class="visually-hidden">Item details</legend>
    <div>
        <input name="lines[{{ $lineIndex }}][variant_id]" type="hidden" value="{{ $lineValues['variant_id'] ?? $variant?->id ?? '' }}">
        <input name="lines[{{ $lineIndex }}][original_line_id]" type="hidden" value="{{ $originalLineId }}">
        <strong class="app-pharmacy-entry-name" hidden>{{ $variant?->product?->name ?? 'Select an available product' }}</strong>
        <small class="app-pharmacy-entry-code text-body-secondary" hidden>{{ $variant?->description() }}</small>

        @error('lines.'.$lineIndex.'.variant_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        @error('lines.'.$lineIndex.'.original_line_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>
    <div class="row g-2">
        <div class="col-sm-6 col-lg-4 col-xl-2">
            <label class="form-label" for="trade_{{ $lineIndex }}_unit">Unit <span class="text-danger" aria-hidden="true">*</span></label>
            @php
                $unitErrorField = $errors->has('lines.'.$lineIndex.'.unit_id') ? 'unit_id' : 'unit_factor';
                $unitErrorId = 'trade_'.$lineIndex.($unitErrorField === 'unit_id' ? '_unit' : '_factor');
            @endphp
            <div class="input-group">
                <select class="{{ inputClass('lines.'.$lineIndex.'.'.$unitErrorField, 'form-select') }}" id="trade_{{ $lineIndex }}_unit" data-pharmacy-purchase-unit aria-keyshortcuts="F4 Alt+B Alt+P" title="Unit (F4, Alt+B Box, Alt+P Pcs)" required {{ inputAttrs('lines.'.$lineIndex.'.'.$unitErrorField, $unitErrorId) }}>
                    <option value="">Unit</option>
                    @foreach ($purchaseUnits as $unit)
                        <option value="unit:{{ $unit->id }}" data-unit-id="{{ $unit->id }}" data-symbol="{{ $unit->symbol }}" @selected((string) $unitId === (string) $unit->id)>{{ $unit->symbol ?: $unit->name }}</option>
                    @endforeach
                </select>
                <button class="btn btn-outline-secondary" type="button" data-pharmacy-edit-unit disabled aria-label="Edit pack size" aria-keyshortcuts="Alt+U" title="Edit pack size">
                    <i class="fa-solid fa-pencil" aria-hidden="true"></i>
                </button>
            </div>
            <input name="lines[{{ $lineIndex }}][unit_id]" type="hidden" value="{{ $unitId }}">
            <input name="lines[{{ $lineIndex }}][unit_factor]" type="hidden" value="{{ $unitFactor }}">
            @error('lines.'.$lineIndex.'.unit_id')
                <div class="invalid-feedback d-block" id="trade_{{ $lineIndex }}_unit-error">{{ $message }}</div>
            @enderror
            @error('lines.'.$lineIndex.'.unit_factor')
                <div class="invalid-feedback d-block" id="trade_{{ $lineIndex }}_factor-error">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-sm-6 col-lg-4 col-xl-2">
            <label class="form-label" for="trade_{{ $lineIndex }}_quantity">Qty <span class="text-danger" aria-hidden="true">*</span></label>
            <div class="input-group">
                <input class="{{ inputClass('lines.'.$lineIndex.'.quantity', 'form-control text-end') }}" id="trade_{{ $lineIndex }}_quantity" name="lines[{{ $lineIndex }}][quantity]" type="number" inputmode="numeric" min="1" max="1000000000" step="1" value="{{ $quantity }}" required {{ inputAttrs('lines.'.$lineIndex.'.quantity', 'trade_'.$lineIndex.'_quantity', $quantityHelpId) }}>
                <span class="input-group-text" id="trade_{{ $lineIndex }}_quantity-unit" data-pharmacy-quantity-unit>Pcs</span>
            </div>
            @error('lines.'.$lineIndex.'.quantity')<div class="invalid-feedback d-block" id="trade_{{ $lineIndex }}_quantity-error">{{ $message }}</div>@enderror
        </div>
        <div class="col-sm-6 col-lg-4 col-xl-2">
            <label class="form-label" for="trade_{{ $lineIndex }}_base_quantity">Total Pcs</label>
            <output class="form-control text-end" id="trade_{{ $lineIndex }}_base_quantity" data-pharmacy-base-quantity>&mdash;</output>
        </div>
        <div class="col-sm-6 col-lg-4 col-xl-2">
            <label class="form-label" for="trade_{{ $lineIndex }}_price">Rate <span class="text-danger" aria-hidden="true">*</span></label>
            <div class="input-group">
                <input class="{{ inputClass('lines.'.$lineIndex.'.unit_price', 'form-control text-end') }}" id="trade_{{ $lineIndex }}_price" name="lines[{{ $lineIndex }}][unit_price]" type="number" inputmode="decimal" min="0" step="0.01" value="{{ $unitPrice }}" required {{ inputAttrs('lines.'.$lineIndex.'.unit_price', 'trade_'.$lineIndex.'_price', 'trade_'.$lineIndex.'_price-unit') }}>
                <span class="input-group-text" id="trade_{{ $lineIndex }}_price-unit">/ <span class="ms-1" data-pharmacy-rate-unit>Pcs</span></span>
            </div>
            @error('lines.'.$lineIndex.'.unit_price')<div class="invalid-feedback d-block" id="trade_{{ $lineIndex }}_price-error">{{ $message }}</div>@enderror
        </div>
        @if ($showPurchaseSalePrice)
            <div class="col-sm-6 col-lg-4 col-xl-2">
                <label class="form-label" for="trade_{{ $lineIndex }}_sale_price">Sale Price <span class="visually-hidden">per piece</span></label>
                <input class="{{ inputClass('lines.'.$lineIndex.'.sale_price_per_piece', 'form-control text-end') }}" id="trade_{{ $lineIndex }}_sale_price" name="lines[{{ $lineIndex }}][sale_price_per_piece]" type="number" inputmode="decimal" min="0" step="0.01" placeholder="{{ $variant?->product?->sale_price > 0 ? $variant->product->sale_price : '0' }}" value="{{ $lineValues['sale_price_per_piece'] ?? '' }}" {{ inputAttrs('lines.'.$lineIndex.'.sale_price_per_piece', 'trade_'.$lineIndex.'_sale_price') }}>
                @error('lines.'.$lineIndex.'.sale_price_per_piece')
                    <div class="invalid-feedback d-block" id="trade_{{ $lineIndex }}_sale_price-error">{{ $message }}</div>
                @enderror
            </div>
        @endif
        @include('pharmacy::purchases.line-pricing')
        <fieldset class="col-sm-6 col-lg-4 col-xl-2 app-pharmacy-entry-expiry" @unless ($itemFields['expiry']) disabled hidden data-pharmacy-hidden-item-field="expiry" @endunless>
            <legend class="visually-hidden">Expiry</legend>
            <label class="form-label" for="trade_{{ $lineIndex }}_expiry">Expiry</label>
            <div class="input-group" data-datepicker>
                <input class="{{ inputClass('lines.'.$lineIndex.'.expiry_date', 'form-control') }}" id="trade_{{ $lineIndex }}_expiry" name="lines[{{ $lineIndex }}][expiry_date]" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="{{ $expiryDate }}" data-input {{ inputAttrs('lines.'.$lineIndex.'.expiry_date', 'trade_'.$lineIndex.'_expiry') }}>
                <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="trade_{{ $lineIndex }}_expiry" aria-label="Choose product expiry date" title="Choose expiry date">
                    <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
                </button>
            </div>
            @error('lines.'.$lineIndex.'.expiry_date')
                <div class="invalid-feedback d-block" id="trade_{{ $lineIndex }}_expiry-error">{{ $message }}</div>
            @enderror
        </fieldset>
        <div class="col-sm-6 col-lg-4 col-xl-2">
            <label class="form-label" for="trade_{{ $lineIndex }}_amount">Amount</label>
            <output class="form-control text-end fw-semibold" id="trade_{{ $lineIndex }}_amount" data-pharmacy-row-amount>0.00</output>
        </div>
    </div>
</fieldset>

