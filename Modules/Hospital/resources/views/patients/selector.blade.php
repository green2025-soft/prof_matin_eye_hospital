<div>
    <label class="form-label" for="{{ $selectorId }}">Patient <span class="text-danger" aria-hidden="true">*</span></label>
    <div class="row g-2">
        <div class="col">
            <select class="{{ inputClass('patient_id', 'form-select') }}" id="{{ $selectorId }}" name="patient_id" data-patient-select data-ajax-select data-url="{{ route('hospital.patients.options') }}" data-value-field="id" data-label-field="text" data-placeholder="Search patient ID, name, or mobile" required {{ inputAttrs('patient_id', $selectorId) }}>
                <option value=""></option>
                @if ($selectedPatient !== null)
                    <option value="{{ $selectedPatient->id }}" selected>{{ $selectedPatient->patient_id }} — {{ $selectedPatient->name }} — {{ $selectedPatient->mobile_no }}</option>
                @endif
            </select>
        </div>
        @can('create', \Modules\Hospital\Models\Patient::class)
            <div class="col-auto">
                <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#patient-create-modal" data-patient-target="{{ $selectorId }}" aria-label="New patient" title="New patient"><i class="fa-solid fa-plus" aria-hidden="true"></i></button>
            </div>
        @endcan
    </div>
    @error('patient_id')<div class="invalid-feedback d-block" id="{{ $selectorId }}-error">{{ $message }}</div>@enderror
</div>
