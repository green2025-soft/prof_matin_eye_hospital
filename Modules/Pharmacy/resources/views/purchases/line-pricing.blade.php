@php
    $savedDiscountInput = $savedLine?->discount_calculation ?? 'amount';
    $savedDiscount = match ($savedDiscountInput) {
        'percent' => $savedLine?->discount_percent ?? '0',
        'unit' => moneyDecimal($savedLine?->discount_unit_amount ?? 0),
        default => moneyDecimal($savedLine?->discount ?? 0),
    };
    $discountInput = $itemFields['discount'] && $hasOldLines ? ($lineValues['discount_input'] ?? 'amount') : $savedDiscountInput;
    $discountInput = in_array($discountInput, ['percent', 'unit', 'amount'], true) ? $discountInput : 'amount';
    $discountValue = $itemFields['discount'] && $hasOldLines ? ($lineValues['discount'] ?? '')
        : ($savedLine === null && $itemFields['discount'] ? '' : $savedDiscount);

    $savedVatInput = $savedLine?->vat_calculation ?? 'amount';
    $vatInput = $itemFields['vat'] && $hasOldLines ? ($lineValues['vat_input'] ?? 'percent')
        : ($savedLine === null ? 'percent' : $savedVatInput);
    $vatInput = in_array($vatInput, ['percent', 'unit', 'amount'], true) ? $vatInput : 'percent';
    $vatMode = $savedLine?->vat_mode ?? $purchaseVatMode;
    $vatPercent = $savedLine?->vat_percent ?? $purchaseVatPercent;
    $vatAmount = moneyDecimal($savedVatInput === 'unit'
        ? ($savedLine?->vat_unit_amount ?? 0) : ($savedLine?->vat ?? 0));
    if ($itemFields['vat'] && $hasOldLines) {
        $vatMode = array_key_exists('vat_mode', $lineValues) ? $lineValues['vat_mode'] : $purchaseVatMode;
        $vatPercent = array_key_exists('vat_percent', $lineValues) ? $lineValues['vat_percent'] : $purchaseVatPercent;
        $vatAmount = $lineValues['vat_amount'] ?? '';
    }
    if (! $itemFields['vat'] && $savedLine === null) {
        $vatMode = 'none';
        $vatPercent = '0';
    }
@endphp

<fieldset class="col-sm-6 col-lg-4 col-xl-2" @unless ($itemFields['discount']) disabled hidden data-pharmacy-hidden-item-field="discount" @endunless>
    <legend class="visually-hidden">Discount</legend>
    <label class="form-label" for="trade_{{ $lineIndex }}_discount">Discount</label>

    <label class="visually-hidden" for="trade_{{ $lineIndex }}_discount_input">Discount calculation for {{ $variant?->product?->name ?? 'product' }}</label>
    <div class="input-group">
        <select class="{{ inputClass('lines.'.$lineIndex.'.discount_input', 'form-select') }}" id="trade_{{ $lineIndex }}_discount_input" name="lines[{{ $lineIndex }}][discount_input]" {{ inputAttrs('lines.'.$lineIndex.'.discount_input', 'trade_'.$lineIndex.'_discount_input') }}>
            <option value="percent" @selected($discountInput === 'percent')>%</option>
            <option value="unit" @selected($discountInput === 'unit')>{{ $discountUnitLabel }}</option>
            <option value="amount" @selected($discountInput === 'amount')>Total</option>
        </select>
        <input class="{{ inputClass('lines.'.$lineIndex.'.discount', 'form-control text-end') }}" id="trade_{{ $lineIndex }}_discount" name="lines[{{ $lineIndex }}][discount]" type="number" inputmode="decimal" min="0" step="0.01" placeholder="0" value="{{ $discountValue }}"
            @if ($savedLine !== null && $savedDiscountInput === 'unit')
                data-original-unit-factor="{{ $savedLine->factor }}"
                data-original-unit-amount="{{ moneyDecimal($savedLine->discount_unit_amount ?? 0) }}"
            @endif
            {{ inputAttrs('lines.'.$lineIndex.'.discount', 'trade_'.$lineIndex.'_discount') }}>
    </div>
    @error('lines.'.$lineIndex.'.discount_input')
        <div class="invalid-feedback d-block" id="trade_{{ $lineIndex }}_discount_input-error">{{ $message }}</div>
    @enderror

    @error('lines.'.$lineIndex.'.discount')<div class="invalid-feedback d-block" id="trade_{{ $lineIndex }}_discount-error">{{ $message }}</div>@enderror
</fieldset>

@unless ($itemFields['vat'])
    <fieldset disabled hidden data-pharmacy-hidden-item-field="vat">
@endunless
<div class="col-sm-6 col-lg-4 col-xl-2">
    <label class="form-label" for="trade_{{ $lineIndex }}_vat_mode">Price VAT</label>
    <select class="{{ inputClass('lines.'.$lineIndex.'.vat_mode', 'form-select') }}" id="trade_{{ $lineIndex }}_vat_mode" name="lines[{{ $lineIndex }}][vat_mode]" required {{ inputAttrs('lines.'.$lineIndex.'.vat_mode', 'trade_'.$lineIndex.'_vat_mode') }}>
        <option value="inclusive" @selected($vatMode === 'inclusive')>Included</option>
        <option value="exclusive" @selected($vatMode === 'exclusive')>Add VAT</option>
        <option value="none" @selected($vatMode === 'none')>No VAT</option>
    </select>
    @error('lines.'.$lineIndex.'.vat_mode')
        <div class="invalid-feedback d-block" id="trade_{{ $lineIndex }}_vat_mode-error">{{ $message }}</div>
    @enderror
</div>
<div class="col-sm-6 col-lg-4 col-xl-2">
    <label class="visually-hidden" for="trade_{{ $lineIndex }}_vat_input">VAT calculation for {{ $variant?->product?->name ?? 'product' }}</label>
    <label class="form-label" for="trade_{{ $lineIndex }}_vat_percent">VAT</label>
    <label class="visually-hidden" for="trade_{{ $lineIndex }}_vat_amount">VAT amount per selected unit or whole line for {{ $variant?->product?->name ?? 'product' }}</label>
    <div class="input-group">
        <select class="{{ inputClass('lines.'.$lineIndex.'.vat_input', 'form-select') }}" id="trade_{{ $lineIndex }}_vat_input" name="lines[{{ $lineIndex }}][vat_input]" {{ inputAttrs('lines.'.$lineIndex.'.vat_input', 'trade_'.$lineIndex.'_vat_input') }}>
            <option value="percent" @selected($vatInput === 'percent')>%</option>
            <option value="unit" @selected($vatInput === 'unit')>{{ $discountUnitLabel }}</option>
            <option value="amount" @selected($vatInput === 'amount')>Total</option>
        </select>
        <input class="{{ inputClass('lines.'.$lineIndex.'.vat_percent', 'form-control text-end') }}" id="trade_{{ $lineIndex }}_vat_percent" name="lines[{{ $lineIndex }}][vat_percent]" type="number" inputmode="decimal" min="0" max="100" step="0.01" placeholder="0" value="{{ $vatPercent == 0 ? '' : $vatPercent }}" @if ($vatInput !== 'percent') hidden disabled @endif {{ inputAttrs('lines.'.$lineIndex.'.vat_percent', 'trade_'.$lineIndex.'_vat_percent') }}>
        <input class="{{ inputClass('lines.'.$lineIndex.'.vat_amount', 'form-control text-end') }}" id="trade_{{ $lineIndex }}_vat_amount" name="lines[{{ $lineIndex }}][vat_amount]" type="number" inputmode="decimal" min="0" step="0.01" placeholder="0" value="{{ $vatInput !== 'percent' || $vatAmount != 0 ? $vatAmount : '' }}"
            @if ($savedLine !== null && $savedVatInput === 'unit')
                data-original-unit-factor="{{ $savedLine->factor }}"
                data-original-unit-amount="{{ moneyDecimal($savedLine->vat_unit_amount ?? 0) }}"
            @endif
            @if ($vatInput === 'percent') hidden disabled @else required @endif {{ inputAttrs('lines.'.$lineIndex.'.vat_amount', 'trade_'.$lineIndex.'_vat_amount') }}>
    </div>
    @error('lines.'.$lineIndex.'.vat_input')
        <div class="invalid-feedback d-block" id="trade_{{ $lineIndex }}_vat_input-error">{{ $message }}</div>
    @enderror
    @error('lines.'.$lineIndex.'.vat_percent')
        <div class="invalid-feedback d-block" id="trade_{{ $lineIndex }}_vat_percent-error">{{ $message }}</div>
    @enderror
    @error('lines.'.$lineIndex.'.vat_amount')
        <div class="invalid-feedback d-block" id="trade_{{ $lineIndex }}_vat_amount-error">{{ $message }}</div>
    @enderror
</div>
<div class="col-sm-6 col-lg-4 col-xl-2">
    <label class="form-label" for="trade_{{ $lineIndex }}_vat_total">VAT total</label>
    <output class="form-control text-end" id="trade_{{ $lineIndex }}_vat_total" data-pharmacy-line-vat-total>0.00</output>
</div>
@unless ($itemFields['vat'])
    </fieldset>
@endunless
