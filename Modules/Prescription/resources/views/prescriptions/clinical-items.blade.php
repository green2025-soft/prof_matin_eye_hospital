@php
    $clinicalRows = old('section_details.'.$pickerField, data_get($record->section_details, $pickerField, []));
    $clinicalRows = collect(is_array($clinicalRows) ? $clinicalRows : [])->take(100);
@endphp

<div class="app-prescription-clinical-items" data-value="#{{ $pickerField }}"
    data-name="section_details[{{ $pickerField }}]">
    @error('section_details.'.$pickerField)
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
    @foreach ($clinicalRows as $clinicalIndex => $clinicalItem)
        @continue(! is_array($clinicalItem))
        @php
            $clinicalItem = array_filter($clinicalItem, static fn ($value) => is_scalar($value) || $value === null);
        @endphp
        @include('prescription::prescriptions.clinical-item-row')
    @endforeach

    <template class="prescription-clinical-item-template">
        @include('prescription::prescriptions.clinical-item-row', [
            'clinicalIndex' => '__INDEX__',
            'clinicalItem' => [],
        ])
    </template>
</div>
