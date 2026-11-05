@php
    $quantity = $hasOldLines ? ($lineValues['quantity'] ?? 0) : ($savedLine?->quantity ?? 0);
    $productUnitId = $hasOldLines ? ($lineValues['product_unit_id'] ?? $sourceLine->product_unit_id) : ($savedLine?->product_unit_id ?? $sourceLine->product_unit_id);
@endphp

<tr data-pharmacy-line data-index="{{ $lineIndex }}" data-return-remaining="{{ $remainingQuantity }}" data-return-amount-minor="{{ $remainingAmount }}" data-original-factor="{{ $sourceLine->factor }}" data-original-price="{{ moneyDecimal($sourceLine->unit_price) }}">
    <td data-label="Qty">
        <label class="visually-hidden" for="line_{{ $lineIndex }}_quantity">Return quantity for {{ $variant->product->name }}</label>
        <input class="{{ inputClass('lines.'.$lineIndex.'.quantity', 'form-control form-control-sm text-end') }}" id="line_{{ $lineIndex }}_quantity" name="lines[{{ $lineIndex }}][quantity]" type="number" inputmode="numeric" min="0" step="1" value="{{ $quantity }}" data-pharmacy-return-quantity required {{ inputAttrs('lines.'.$lineIndex.'.quantity', 'line_'.$lineIndex.'_quantity') }}>
        @error('lines.'.$lineIndex.'.quantity')<div class="invalid-feedback d-block" id="line_{{ $lineIndex }}_quantity-error">{{ $message }}</div>@enderror
    </td>
    <td class="app-pharmacy-entry-product" data-label="Product">
        <strong class="d-block small">{{ $variant->product->name }}</strong>
        <small class="text-body-secondary">{{ $variant->sku }}</small>
        @if (filled($variant->name) && strtolower(trim($variant->name)) !== 'default')
            <small class="text-body-secondary"> / {{ $variant->name }}</small>
        @endif
        @if ($variant->color)<small class="text-body-secondary"> / {{ $variant->color }}</small>@endif
        @if ($variant->size)<small class="text-body-secondary"> / {{ $variant->size }}</small>@endif
        @if (filled($variant->sph))<small class="text-body-secondary"> / SPH {{ $variant->sph }}</small>@endif
        @if (filled($variant->cyl))<small class="text-body-secondary"> / CYL {{ $variant->cyl }}</small>@endif
        <input name="lines[{{ $lineIndex }}][variant_id]" type="hidden" value="{{ $variant->id }}" data-pharmacy-return-field @disabled((int) $quantity <= 0)>
        <input name="lines[{{ $lineIndex }}][purchase_item_id]" type="hidden" value="{{ $sourceLine->id }}" data-pharmacy-return-field @disabled((int) $quantity <= 0)>
        @error('lines.'.$lineIndex.'.variant_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        @error('lines.'.$lineIndex.'.purchase_item_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </td>
    <td class="text-end text-nowrap" data-label="Original (Pcs)">{{ $sourceLine->base_quantity }}</td>
    <td class="text-end" data-label="Available (Pcs)">
        <span class="text-nowrap" id="line_{{ $lineIndex }}_remaining" data-pharmacy-return-remaining>{{ $remainingQuantity }}</span>
        @if ($sourceLine->base_quantity > $remainingQuantity)
            <small class="d-block text-body-secondary">Returned: {{ $sourceLine->base_quantity - $remainingQuantity }}</small>
        @endif
    </td>
    <td data-label="Unit">
        <label class="visually-hidden" for="line_{{ $lineIndex }}_unit">Return unit for {{ $variant->product->name }}</label>
        <select class="{{ inputClass('lines.'.$lineIndex.'.product_unit_id', 'form-select form-select-sm') }}" id="line_{{ $lineIndex }}_unit" name="lines[{{ $lineIndex }}][product_unit_id]" data-pharmacy-return-unit required {{ inputAttrs('lines.'.$lineIndex.'.product_unit_id', 'line_'.$lineIndex.'_unit', 'line_'.$lineIndex.'_remaining') }}>
            @foreach ($variant->product->units as $unit)
                <option value="{{ $unit->id }}" data-factor="{{ $unit->id === $sourceLine->product_unit_id ? $sourceLine->factor : $unit->factor }}" @selected((string) $productUnitId === (string) $unit->id)>{{ $unit->unit->symbol ?: $unit->unit->name }} × {{ $unit->id === $sourceLine->product_unit_id ? $sourceLine->factor : $unit->factor }}</option>
            @endforeach
        </select>
        @error('lines.'.$lineIndex.'.product_unit_id')<div class="invalid-feedback d-block" id="line_{{ $lineIndex }}_unit-error">{{ $message }}</div>@enderror
    </td>
    <td class="text-end text-nowrap" data-label="Rate">
        <input name="lines[{{ $lineIndex }}][unit_price]" type="hidden" value="{{ moneyDecimal($sourceLine->unit_price) }}" data-pharmacy-return-rate-input data-pharmacy-return-field @disabled((int) $quantity <= 0)>
        <output data-pharmacy-return-rate>{{ moneyDecimal($sourceLine->unit_price) }}</output>
        @error('lines.'.$lineIndex.'.unit_price')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </td>
    <td class="text-end text-nowrap fw-semibold" data-label="Return amount"><output data-pharmacy-return-amount>0.00</output></td>
</tr>
