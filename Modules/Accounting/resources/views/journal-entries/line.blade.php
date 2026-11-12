@php
    $field = 'lines.'.$index;
    $inputId = 'journal-line-'.$index;
    $debit = is_object($line) ? moneyDecimal($line->debit) : data_get($line, 'debit', '');
    $credit = is_object($line) ? moneyDecimal($line->credit) : data_get($line, 'credit', '');
@endphp

<tr class="accounting-journal-line">
    <td>
        <label class="visually-hidden" for="{{ $inputId }}-account">Account head</label>
        <select class="{{ inputClass($field.'.account_head_id', 'form-select form-select-sm') }}"
            id="{{ $inputId }}-account" name="lines[{{ $index }}][account_head_id]" required
            {{ inputAttrs($field.'.account_head_id', $inputId.'-account') }}>
            <option value="">Select account</option>
            @foreach ($accountHeads as $head)
                <option value="{{ $head->id }}" @selected((string) data_get($line, 'account_head_id') === (string) $head->id)>{{ $head->code }} — {{ $head->name }}</option>
            @endforeach
        </select>
        @error($field.'.account_head_id')<div class="invalid-feedback d-block" id="{{ $inputId }}-account-error">{{ $message }}</div>@enderror
    </td>
    <td>
        <label class="visually-hidden" for="{{ $inputId }}-debit">Debit</label>
        <input class="{{ inputClass($field.'.debit', 'form-control form-control-sm text-end') }} journal-debit"
            id="{{ $inputId }}-debit" name="lines[{{ $index }}][debit]" inputmode="decimal" autocomplete="off"
            value="{{ $debit === '0.00' ? '' : $debit }}" placeholder="0.00" maxlength="13"
            {{ inputAttrs($field.'.debit', $inputId.'-debit') }}>
        @error($field.'.debit')<div class="invalid-feedback d-block" id="{{ $inputId }}-debit-error">{{ $message }}</div>@enderror
    </td>
    <td>
        <label class="visually-hidden" for="{{ $inputId }}-credit">Credit</label>
        <input class="{{ inputClass($field.'.credit', 'form-control form-control-sm text-end') }} journal-credit"
            id="{{ $inputId }}-credit" name="lines[{{ $index }}][credit]" inputmode="decimal" autocomplete="off"
            value="{{ $credit === '0.00' ? '' : $credit }}" placeholder="0.00" maxlength="13"
            {{ inputAttrs($field.'.credit', $inputId.'-credit') }}>
        @error($field.'.credit')<div class="invalid-feedback d-block" id="{{ $inputId }}-credit-error">{{ $message }}</div>@enderror
    </td>
    <td>
        <label class="visually-hidden" for="{{ $inputId }}-remarks">Remarks</label>
        <input class="{{ inputClass($field.'.remarks', 'form-control form-control-sm') }}"
            id="{{ $inputId }}-remarks" name="lines[{{ $index }}][remarks]" maxlength="255"
            value="{{ data_get($line, 'remarks') }}" {{ inputAttrs($field.'.remarks', $inputId.'-remarks') }}>
        @error($field.'.remarks')<div class="invalid-feedback d-block" id="{{ $inputId }}-remarks-error">{{ $message }}</div>@enderror
    </td>
    <td class="text-end">
        <button class="btn btn-outline-danger btn-sm accounting-remove-line" type="button" aria-label="Remove voucher line" title="Remove line">
            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
        </button>
    </td>
</tr>
