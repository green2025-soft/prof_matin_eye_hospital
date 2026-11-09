<section class="app-form-section mb-3" aria-labelledby="prescription-identity-heading">
    <h2 class="h6 mb-3" id="prescription-identity-heading">Patient and doctor</h2>
    <div class="row g-3">
        <div class="col-md-5">
            <label class="form-label" for="prescription_patient_id">Patient Name <span class="text-danger" aria-hidden="true">*</span></label>
            <select class="{{ inputClass('patient_id', 'form-select') }}" id="prescription_patient_id" name="patient_id"
                data-patient-select data-ajax-select data-url="{{ route('hospital.patients.options') }}"
                data-value-field="id" data-label-field="text" data-placeholder="Search patient ID, name or mobile"
                required {{ inputAttrs('patient_id', 'prescription_patient_id') }}>
                <option value=""></option>
                @if ($selectedPatient !== null)
                    <option value="{{ $selectedPatient->id }}" selected>{{ $selectedPatient->patient_id }} / {{ $selectedPatient->name }} / {{ $selectedPatient->mobile_no }}</option>
                @endif
            </select>
            @error('patient_id')<div class="invalid-feedback d-block" id="prescription_patient_id-error">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label" for="prescription_doctor_id">Doctor Name <span class="text-danger" aria-hidden="true">*</span></label>
            @if ($doctorLocked)
                <input class="{{ inputClass('doctor_id') }}" id="prescription_doctor_id" type="text" readonly
                    value="{{ $selectedDoctor?->doctor_id ?: $selectedDoctor?->id }} / {{ $selectedDoctor?->name }}"
                    {{ inputAttrs('doctor_id', 'prescription_doctor_id') }}>
                <input type="hidden" name="doctor_id" value="{{ $selectedDoctor?->id }}">
            @else
                <select class="{{ inputClass('doctor_id', 'form-select') }}" id="prescription_doctor_id" name="doctor_id"
                    data-ajax-select data-url="{{ route('hospital.doctors.options') }}" data-value-field="id"
                    data-label-field="text" data-placeholder="Select doctor" required
                    {{ inputAttrs('doctor_id', 'prescription_doctor_id') }}>
                    <option value=""></option>
                    @if ($selectedDoctor !== null)
                        <option value="{{ $selectedDoctor->id }}" selected>{{ $selectedDoctor->doctor_id ?: $selectedDoctor->id }} / {{ $selectedDoctor->name }}</option>
                    @endif
                </select>
            @endif
            @error('doctor_id')<div class="invalid-feedback d-block" id="prescription_doctor_id-error">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-3">
            <label class="form-label" for="prescription_date">Visit Date <span class="text-danger" aria-hidden="true">*</span></label>
            <div class="input-group" data-datepicker>
                <input class="{{ inputClass('prescription_date') }}" id="prescription_date" name="prescription_date" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="{{ getValue('prescription_date', null, $entryDate) }}" required data-input {{ inputAttrs('prescription_date') }}>
                <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="prescription_date" aria-label="Choose prescription date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
            </div>
            @error('prescription_date')<div class="invalid-feedback d-block" id="prescription_date-error">{{ $message }}</div>@enderror
        </div>
    </div>
</section>
