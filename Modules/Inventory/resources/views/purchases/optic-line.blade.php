@php
    $originalLineId = $lineValues['original_line_id'] ?? $savedLine?->id;
    $selectedProfile = $variant?->product?->units->firstWhere('id', $lineValues['product_unit_id'] ?? null);
    $opticUnitId = $lineValues['unit_id'] ?? ($hasOldLines ? ($selectedProfile?->unit_id ?? '') : $savedLine?->productUnit?->unit_id);
    $opticUnitFactor = $lineValues['unit_factor'] ?? ($hasOldLines ? ($selectedProfile?->factor ?? 1) : ($savedLine?->factor ?? 1));
    $opticUnit = $purchaseUnits->firstWhere('symbol', 'Pcs');
    if ($savedLine !== null) {
        $opticUnit = $purchaseUnits->firstWhere('id', $opticUnitId)
            ?? $variant?->product?->units->firstWhere('unit_id', $opticUnitId)?->unit;
    } else {
        $opticUnitId = $opticUnit?->id;
        $opticUnitFactor = 1;
    }
    $opticUnitLabel = $opticUnit?->symbol ?: ($opticUnit?->name ?: ($savedLine === null ? 'Pcs' : 'Saved unit'));
    $discountUnitLabel = '/ '.$opticUnitLabel;
    $quantity = $hasOldLines ? ($lineValues['quantity'] ?? '') : ($savedLine?->quantity ?? '');
    $unitPrice = $hasOldLines ? ($lineValues['unit_price'] ?? '') : ($savedLine === null ? '' : \Modules\Core\Helpers\Money::decimal($savedLine->unit_price_minor));
    $quantityHelpId = 'trade_'.$lineIndex.'_quantity-unit';
    foreach (['unit_id' => 'unit', 'unit_factor' => 'factor', 'product_unit_id' => 'product-unit'] as $field => $suffix) {
        if ($errors->has('lines.'.$lineIndex.'.'.$field)) {
            $quantityHelpId .= ' trade_'.$lineIndex.'_'.$suffix.'-error';
        }
    }
    $showOpticOptions = $itemFields['discount'] || $itemFields['vat'];
    $opticOptionsLabel = $itemFields['discount'] ? 'Discount' : 'VAT';
    if ($itemFields['discount'] && $itemFields['vat']) {
        $opticOptionsLabel = 'Discount / VAT';
    }
    $optionDiscount = $hasOldLines ? ($lineValues['discount'] ?? 0) : match ($savedLine?->discount_calculation ?? 'amount') {
        'percent' => $savedLine?->discount_percent ?? 0,
        'unit' => $savedLine?->discount_unit_amount_minor ?? 0,
        default => $savedLine?->discount_minor ?? 0,
    };
    $optionVatMode = $hasOldLines ? ($lineValues['vat_mode'] ?? $purchaseVatMode)
        : ($savedLine?->vat_mode ?? ($savedLine === null ? $purchaseVatMode : $document->vat_mode));
    $optionVatPercent = $hasOldLines ? ($lineValues['vat_percent'] ?? $purchaseVatPercent)
        : ($savedLine?->vat_percent ?? ($savedLine === null ? $purchaseVatPercent : $document->vat_percent));
    $optionVatAmount = $hasOldLines ? ($lineValues['vat_amount'] ?? 0) : ($savedLine?->vat_minor ?? 0);
    $opticOptionsOpen = $errors->hasAny([
        'lines.'.$lineIndex.'.discount', 'lines.'.$lineIndex.'.discount_input',
        'lines.'.$lineIndex.'.vat_mode', 'lines.'.$lineIndex.'.vat_input',
        'lines.'.$lineIndex.'.vat_percent', 'lines.'.$lineIndex.'.vat_amount',
    ]) || ($itemFields['discount'] && $optionDiscount > 0)
        || ($itemFields['vat'] && $optionVatMode !== 'none' && ($optionVatPercent > 0 || $optionVatAmount > 0));
@endphp

<fieldset
    disabled
    data-inventory-line
    data-index="{{ $lineIndex }}"
    data-inventory-fixed-unit="{{ $opticUnitLabel }}"
    data-product-id="{{ $variant?->product_id }}"
    data-purchase-price="{{ $variant?->product?->purchase_price ?? '0.00' }}"
    data-sale-price="{{ $variant?->product?->sale_price ?? '0.00' }}"
    data-unit-profiles="{{ $variant ? $variant->product->units->map(fn ($unit) => ['id' => $unit->id, 'unit_id' => $unit->unit_id, 'factor' => $unit->factor])->toJson() : '[]' }}"
>
    <legend class="visually-hidden">Item details</legend>
    <div>
        <input name="lines[{{ $lineIndex }}][variant_id]" type="hidden" value="{{ $lineValues['variant_id'] ?? $variant?->id ?? '' }}">
        @if (filled($originalLineId))
            <input name="lines[{{ $lineIndex }}][original_line_id]" type="hidden" value="{{ $originalLineId }}">
        @endif
        <strong class="app-inventory-entry-name" data-inventory-product-name hidden>{{ $variant?->product?->name ?? 'Select an available product' }}</strong>
        <small class="app-inventory-entry-code text-body-secondary" data-inventory-product-code hidden>
            {{ $variant?->sku }}
            @if ($variant?->name && $variant->name !== 'Default') / {{ $variant->name }} @endif
            @if ($variant?->color) / {{ $variant->color }} @endif
            @if ($variant?->size) / {{ $variant->size }} @endif
            @if ($variant !== null && $variant->sph !== null) / SPH {{ $variant->sph }} @endif
            @if ($variant !== null && $variant->cyl !== null) / CYL {{ $variant->cyl }} @endif
        </small>
        <small class="d-none text-body-secondary" data-inventory-product-stock hidden></small>
        @error('lines.'.$lineIndex.'.variant_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        @error('lines.'.$lineIndex.'.original_line_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>
    <div class="row g-2">
        <input name="lines[{{ $lineIndex }}][unit_id]" type="hidden" value="{{ $opticUnitId }}" data-inventory-unit-id>
        <input name="lines[{{ $lineIndex }}][unit_factor]" type="hidden" value="{{ $opticUnitFactor }}" data-inventory-unit-factor>
        <div class="col-sm-6 col-lg-4 col-xl-2">
            <label class="form-label" for="trade_{{ $lineIndex }}_quantity">Qty <span class="text-danger" aria-hidden="true">*</span></label>
            <div class="input-group">
                <input class="{{ inputClass('lines.'.$lineIndex.'.quantity', 'form-control text-end') }}" id="trade_{{ $lineIndex }}_quantity" name="lines[{{ $lineIndex }}][quantity]" type="number" inputmode="numeric" min="1" max="1000000000" step="1" value="{{ $quantity }}" required {{ inputAttrs('lines.'.$lineIndex.'.quantity', 'trade_'.$lineIndex.'_quantity', $quantityHelpId) }}>
                <span class="input-group-text" id="trade_{{ $lineIndex }}_quantity-unit" data-inventory-quantity-unit>{{ $opticUnitLabel }}</span>
            </div>
            @error('lines.'.$lineIndex.'.quantity')<div class="invalid-feedback d-block" id="trade_{{ $lineIndex }}_quantity-error">{{ $message }}</div>@enderror
            @error('lines.'.$lineIndex.'.unit_id')
                <div class="invalid-feedback d-block" id="trade_{{ $lineIndex }}_unit-error">{{ $message }}</div>
            @enderror
            @error('lines.'.$lineIndex.'.unit_factor')
                <div class="invalid-feedback d-block" id="trade_{{ $lineIndex }}_factor-error">{{ $message }}</div>
            @enderror
            @error('lines.'.$lineIndex.'.product_unit_id')
                <div class="invalid-feedback d-block" id="trade_{{ $lineIndex }}_product-unit-error">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-sm-6 col-lg-4 col-xl-2">
            <label class="form-label" for="trade_{{ $lineIndex }}_price">Rate <span class="text-danger" aria-hidden="true">*</span></label>
            <div class="input-group">
                <input class="{{ inputClass('lines.'.$lineIndex.'.unit_price', 'form-control text-end') }}" id="trade_{{ $lineIndex }}_price" name="lines[{{ $lineIndex }}][unit_price]" type="number" inputmode="decimal" min="0" step="0.01" value="{{ $unitPrice }}" data-inventory-unit-price required {{ inputAttrs('lines.'.$lineIndex.'.unit_price', 'trade_'.$lineIndex.'_price', 'trade_'.$lineIndex.'_price-unit') }}>
                <span class="input-group-text" id="trade_{{ $lineIndex }}_price-unit">/ <span class="ms-1" data-inventory-rate-unit>{{ $opticUnitLabel }}</span></span>
            </div>
            @error('lines.'.$lineIndex.'.unit_price')<div class="invalid-feedback d-block" id="trade_{{ $lineIndex }}_price-error">{{ $message }}</div>@enderror
        </div>
        @if ($showPurchaseSalePrice)
            <div class="col-sm-6 col-lg-4 col-xl-2">
                <label class="form-label" for="trade_{{ $lineIndex }}_sale_price">Sale Price <span class="visually-hidden">per piece</span></label>
                <input class="{{ inputClass('lines.'.$lineIndex.'.sale_price_per_piece', 'form-control text-end') }}" id="trade_{{ $lineIndex }}_sale_price" name="lines[{{ $lineIndex }}][sale_price_per_piece]" type="number" inputmode="decimal" min="0" step="0.01" placeholder="{{ $variant?->product?->sale_price > 0 ? $variant->product->sale_price : '0' }}" value="{{ $lineValues['sale_price_per_piece'] ?? '' }}" data-inventory-sale-price-per-piece {{ inputAttrs('lines.'.$lineIndex.'.sale_price_per_piece', 'trade_'.$lineIndex.'_sale_price') }}>
                @error('lines.'.$lineIndex.'.sale_price_per_piece')
                    <div class="invalid-feedback d-block" id="trade_{{ $lineIndex }}_sale_price-error">{{ $message }}</div>
                @enderror
            </div>
        @endif
        <div class="col-12 order-last" @unless ($showOpticOptions) hidden @endunless>
            <details data-inventory-optic-options @if ($opticOptionsOpen) open @endif>
                <summary class="small fw-semibold">{{ $opticOptionsLabel }}</summary>
                <div class="row g-2 mt-1">
                    @include('inventory::purchases.line-pricing')
                </div>
            </details>
        </div>
        <div class="col-sm-6 col-lg-4 col-xl-2">
            <label class="form-label" for="trade_{{ $lineIndex }}_amount">Amount</label>
            <output class="form-control text-end fw-semibold" id="trade_{{ $lineIndex }}_amount" data-inventory-row-amount>0.00</output>
        </div>
    </div>
</fieldset>
