@php
    $headField = 'items.'.$index.'.head_id';
    $amountField = 'items.'.$index.'.amount';
    $headId = getValue($headField, null, data_get($item, 'head_id'));
    $head = $selectedHeads->get($headId) ?? data_get($item, 'head');
@endphp

<tr class="income-expense-row">
    <td>
        <label class="visually-hidden" for="items-{{ $index }}-head_id">Head</label>
        <select class="{{ inputClass($headField, 'form-select') }}" id="items-{{ $index }}-head_id" name="items[{{ $index }}][head_id]"
            data-ajax-select data-url="{{ $headOptionsUrl }}" data-placeholder="Select a head"
            required {{ inputAttrs($headField) }}>
            <option value=""></option>
            @if ($head !== null)
                <option value="{{ $head->id }}" selected>{{ $head->name }}</option>
            @endif
        </select>
        @error($headField)<div class="invalid-feedback d-block" id="items-{{ $index }}-head_id-error">{{ $message }}</div>@enderror
    </td>
    <td class="col-4 col-sm-3">
        <label class="visually-hidden" for="items-{{ $index }}-amount">Amount</label>
        <input class="{{ inputClass($amountField, 'form-control text-end income-expense-amount') }}" id="items-{{ $index }}-amount"
            name="items[{{ $index }}][amount]" type="text" inputmode="decimal" maxlength="13"
            value="{{ getValue($amountField, null, data_get($item, 'amount')) }}" required {{ inputAttrs($amountField) }}>
        @error($amountField)<div class="invalid-feedback d-block" id="items-{{ $index }}-amount-error">{{ $message }}</div>@enderror
    </td>
    <td class="text-end">
        <button class="btn btn-outline-danger btn-sm income-expense-remove-row" type="button" aria-label="Remove head">
            <i class="fa-solid fa-trash-can" aria-hidden="true"></i>
        </button>
    </td>
</tr>
