<form class="app-card app-appointment-form p-3" action="{{ $formAction }}" method="POST">
    @csrf
    @if ($formMethod !== 'POST')
        @method($formMethod)
    @endif

    <section class="app-form-section" aria-labelledby="appointment-form-heading">
        <h2 class="h6 mb-2" id="appointment-form-heading">{{ $pageTitle }}</h2>
        @if ($appointment->exists && ($appointment->serial_no !== null || $appointment->invoice_no !== null))
            <p class="small text-body-secondary d-flex flex-wrap gap-3 mb-2">
                @if ($appointment->serial_no !== null)
                    <span>Serial: <strong class="text-body">{{ $appointment->serial_no }}</strong></span>
                @endif
                @if ($appointment->invoice_no !== null)
                    <span>Invoice No.: <strong class="text-body">{{ $appointment->invoice_no }}</strong></span>
                @endif
            </p>
        @endif
        <div class="row g-2 align-items-end">
            <div class="col-md-6 col-xl-3">
                <label class="form-label" for="appointment_doctor_id">Doctor <span class="text-danger" aria-hidden="true">*</span></label>
                <select class="{{ inputClass('doctor_id', 'form-select') }}" id="appointment_doctor_id" name="doctor_id" data-ajax-select data-url="{{ route('hospital.doctors.options') }}" data-value-field="id" data-label-field="text" data-placeholder="Select a doctor" required {{ inputAttrs('doctor_id', 'appointment_doctor_id') }}>
                    <option value=""></option>
                    @if ($selectedDoctor !== null)
                        <option value="{{ $selectedDoctor->id }}" selected>{{ $selectedDoctor->doctor_id ?: $selectedDoctor->id }} — {{ $selectedDoctor->name }}</option>
                    @endif
                </select>
                @error('doctor_id')<div class="invalid-feedback d-block" id="appointment_doctor_id-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 col-xl-4">
                @include('hospital::patients.selector', ['selectedPatient' => $selectedPatient, 'selectorId' => 'appointment_patient_id'])
            </div>
            <div class="col-sm-6 col-md-4 col-xl-2">
                <label class="form-label" for="appointment_date">Appointment date <span class="text-danger" aria-hidden="true">*</span></label>
                <div class="input-group" data-datepicker>
                    <input class="{{ inputClass('appointment_date') }}" id="appointment_date" name="appointment_date" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="{{ getValue('appointment_date', null, $appointmentDate) }}" required data-input {{ inputAttrs('appointment_date') }}>
                    <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="appointment_date" aria-label="Choose appointment date" title="Choose appointment date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
                </div>
                @error('appointment_date')<div class="invalid-feedback d-block" id="appointment_date-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-sm-6 col-md-4 col-xl-3">
                <label class="form-label" for="appointment_time">Appointment time</label>
                <div class="input-group" data-timepicker>
                    <input class="{{ inputClass('appointment_time') }}" id="appointment_time" name="appointment_time" type="text" autocomplete="off" placeholder="hh:mm AM/PM" value="{{ getValue('appointment_time', null, $appointmentTime) }}" maxlength="5" data-input {{ inputAttrs('appointment_time') }}>
                    <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="appointment_time" aria-label="Choose appointment time" title="Choose appointment time"><i class="fa-solid fa-clock" aria-hidden="true"></i></button>
                    <button class="btn btn-outline-secondary" type="button" data-clear aria-controls="appointment_time" aria-label="Clear appointment time" title="Clear appointment time"><i class="fa-solid fa-xmark" aria-hidden="true"></i></button>
                </div>
                @error('appointment_time')<div class="invalid-feedback d-block" id="appointment_time-error">{{ $message }}</div>@enderror
            </div>
        </div>
    </section>

    @include('core::partials.form-actions', ['model' => $appointment, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel])
</form>
