<tr data-invoice-item>
    <td>
        <label class="visually-hidden" for="invoice-item-{{ $index }}-description">Description</label>
        <input
            class="{{ inputClass('items.'.$index.'.description') }}"
            id="invoice-item-{{ $index }}-description"
            name="items[{{ $index }}][description]"
            type="text"
            value="{{ getValue('items.'.$index.'.description', null, $item['description'] ?? '') }}"
            maxlength="1000"
            required
            {{ inputAttrs('items.'.$index.'.description', 'invoice-item-'.$index.'-description') }}
        >
        @error('items.'.$index.'.description')
            <div class="invalid-feedback d-block" id="invoice-item-{{ $index }}-description-error">{{ $message }}</div>
        @enderror
    </td>
    <td>
        <label class="visually-hidden" for="invoice-item-{{ $index }}-quantity">Quantity</label>
        <input
            class="{{ inputClass('items.'.$index.'.quantity') }}"
            id="invoice-item-{{ $index }}-quantity"
            name="items[{{ $index }}][quantity]"
            type="number"
            min="1"
            max="100000"
            step="1"
            value="{{ getValue('items.'.$index.'.quantity', null, $item['quantity'] ?? 1) }}"
            required
            {{ inputAttrs('items.'.$index.'.quantity', 'invoice-item-'.$index.'-quantity') }}
        >
        @error('items.'.$index.'.quantity')
            <div class="invalid-feedback d-block" id="invoice-item-{{ $index }}-quantity-error">{{ $message }}</div>
        @enderror
    </td>
    <td>
        <label class="visually-hidden" for="invoice-item-{{ $index }}-unit-amount">Unit amount</label>
        <input
            class="{{ inputClass('items.'.$index.'.unit_amount') }}"
            id="invoice-item-{{ $index }}-unit-amount"
            name="items[{{ $index }}][unit_amount]"
            type="text"
            inputmode="decimal"
            value="{{ getValue('items.'.$index.'.unit_amount', null, $item['unit_amount'] ?? '0.00') }}"
            required
            {{ inputAttrs('items.'.$index.'.unit_amount', 'invoice-item-'.$index.'-unit-amount') }}
        >
        @error('items.'.$index.'.unit_amount')
            <div class="invalid-feedback d-block" id="invoice-item-{{ $index }}-unit-amount-error">{{ $message }}</div>
        @enderror
    </td>
    <td class="text-end">
        <button
            class="btn btn-outline-danger btn-sm app-action-button"
            type="button"
            data-invoice-remove
            aria-label="Remove invoice item"
            title="Remove item"
        >
            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
        </button>
    </td>
</tr>
