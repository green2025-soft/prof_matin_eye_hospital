@php
    $clinicalField = 'section_details.'.$pickerField.'.'.$clinicalIndex;
    $clinicalName = 'section_details['.$pickerField.']['.$clinicalIndex.']';
    $clinicalErrorId = str_replace('.', '-', $clinicalField);
    $clinicalNameValue = data_get($clinicalItem, 'name');
    $durationUnits = [
        'hours' => 'Hours',
        'days' => 'Days',
        'weeks' => 'Weeks',
        'months' => 'Month(s)',
        'years' => 'Year',
        'long_ago' => 'Long time ago',
    ];
    $durationUnit = data_get($clinicalItem, 'duration_unit') ?? 'days';
    $durationUnit = array_key_exists($durationUnit, $durationUnits) ? $durationUnit : 'days';
@endphp

<div class="app-prescription-clinical-item">
    <div class="app-prescription-clinical-item-name">
        <span class="prescription-clinical-item-number" aria-hidden="true"></span>
        <span class="prescription-clinical-item-title">{{ $clinicalNameValue }}</span>
        <input type="hidden" name="{{ $clinicalName }}[name]" form="prescription-form"
            value="{{ $clinicalNameValue }}">
        @error($clinicalField.'.name')
            <span class="invalid-feedback d-block" id="{{ $clinicalErrorId }}-name-error">{{ $message }}</span>
        @enderror
    </div>
    <label class="app-prescription-clinical-item-duration">
        <span>For</span>
        <input name="{{ $clinicalName }}[duration]" form="prescription-form" maxlength="30"
            class="{{ inputClass($clinicalField.'.duration', 'form-control form-control-sm') }}"
            value="{{ data_get($clinicalItem, 'duration') }}" placeholder="Duration" inputmode="decimal"
            {{ inputAttrs($clinicalField.'.duration') }}>
        @error($clinicalField.'.duration')
            <span class="invalid-feedback" id="{{ $clinicalErrorId }}-duration-error">{{ $message }}</span>
        @enderror
    </label>
    <label class="app-prescription-clinical-item-unit">
        <span class="visually-hidden">Duration unit</span>
        <select name="{{ $clinicalName }}[duration_unit]" form="prescription-form"
            class="{{ inputClass($clinicalField.'.duration_unit', 'form-select form-select-sm') }}"
            {{ inputAttrs($clinicalField.'.duration_unit') }}>
            @foreach ($durationUnits as $unitValue => $unitLabel)
                <option value="{{ $unitValue }}" @selected($durationUnit === $unitValue)>{{ $unitLabel }}</option>
            @endforeach
        </select>
        @error($clinicalField.'.duration_unit')
            <span class="invalid-feedback" id="{{ $clinicalErrorId }}-duration_unit-error">{{ $message }}</span>
        @enderror
    </label>
    <label class="app-prescription-clinical-item-notes">
        <span>Note</span>
        <textarea name="{{ $clinicalName }}[notes]" form="prescription-form" rows="1" maxlength="1000" placeholder="Note"
            class="{{ inputClass($clinicalField.'.notes', 'form-control form-control-sm') }}"
            {{ inputAttrs($clinicalField.'.notes') }}>{{ data_get($clinicalItem, 'notes') }}</textarea>
        @error($clinicalField.'.notes')
            <span class="invalid-feedback" id="{{ $clinicalErrorId }}-notes-error">{{ $message }}</span>
        @enderror
    </label>
    <button class="btn btn-sm prescription-clinical-item-remove" type="button"
        aria-label="Remove {{ $clinicalNameValue }}">
        <i class="fa-solid fa-xmark" aria-hidden="true"></i>
    </button>
</div>
