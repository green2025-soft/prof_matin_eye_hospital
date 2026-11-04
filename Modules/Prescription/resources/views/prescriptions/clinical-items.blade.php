<div class="app-prescription-clinical-items" data-value="#{{ $pickerField }}"
    data-name="section_details[{{ $pickerField }}]">
    @error('section_details.'.$pickerField)
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
    @foreach ($sectionInputs[$pickerField] ?? [] as $clinicalIndex => $clinicalItem)
        @include('prescription::prescriptions.clinical-item-row')
    @endforeach

    <template class="prescription-clinical-item-template">
        @include('prescription::prescriptions.clinical-item-row', [
            'clinicalIndex' => '__INDEX__',
            'clinicalItem' => [],
        ])
    </template>
</div>
