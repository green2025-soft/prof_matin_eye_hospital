<div class="col-lg-5">
    <label class="form-label small" for="line_{{ $lineIndex }}_lot">Stock lot (optional)</label>
    <select class="{{ inputClass('lines.'.$lineIndex.'.stock_lot_id', 'form-select') }}" id="line_{{ $lineIndex }}_lot" name="lines[{{ $lineIndex }}][stock_lot_id]" data-inventory-lot data-ajax-select data-url="{{ route('inventory.lots.options') }}" data-parent-select="line_{{ $lineIndex }}_variant" data-parent-field="variant_id" data-placeholder="Use {{ strtoupper($inventorySettings['stock_method']) }}" {{ inputAttrs('lines.'.$lineIndex.'.stock_lot_id', 'line_'.$lineIndex.'_lot') }}>
        <option value=""></option>
        @if ($stockLots->has($lineValues['stock_lot_id'] ?? null))
            <option value="{{ $lineValues['stock_lot_id'] }}" selected>
                #{{ $lineValues['stock_lot_id'] }} / {{ $stockLots[$lineValues['stock_lot_id']]->batch_no }} / Qty {{ $stockLots[$lineValues['stock_lot_id']]->remaining_quantity }}
            </option>
        @endif
    </select>
    @error('lines.'.$lineIndex.'.stock_lot_id')
        <div class="invalid-feedback d-block" id="line_{{ $lineIndex }}_lot-error">
            {{ $message }}
        </div>
    @enderror
</div>
