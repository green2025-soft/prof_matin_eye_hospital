<div class="app-prescription-picker" id="{{ $pickerField }}-picker"
    data-url="{{ $pickerUrl }}" data-search="#{{ $pickerField }}-search">
    <div class="app-prescription-picker-options" aria-label="Available items" aria-live="polite"></div>
    <textarea class="{{ inputClass($pickerField, 'prescription-picker-value') }}" hidden
        id="{{ $pickerField }}" name="{{ $pickerField }}" form="prescription-form">{{ $pickerValue }}</textarea>
    @error($pickerField)
        <div class="invalid-feedback d-block" id="{{ $pickerField }}-error">{{ $message }}</div>
    @enderror
    <p class="small text-danger prescription-picker-error mt-2 mb-0" role="alert" hidden></p>
</div>
