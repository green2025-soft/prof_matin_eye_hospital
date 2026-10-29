@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle, 'description' => 'Record the consultation and patient payment details.'])
    @include('hospital::partials.clinical-tabs')

    <form
        class="app-card p-3 p-md-4"
        action="{{ $formAction }}"
        method="POST"
        data-clinical-entry="consultation"
        data-record-exists="{{ $consultation->exists ? '1' : '0' }}"
        data-initial-amount="{{ $consultation->amount ?? '0.00' }}"
        data-original-source-ids="{{ json_encode(($consultation->exists ? [$consultation->doctor_id] : [])) }}"
        data-original-patient-type="{{ $consultation->patient_type ?? 'new' }}"
    >
        @csrf
        @if ($formMethod !== 'POST')
            @method($formMethod)
        @endif
        @if ($appointmentId !== null)
            <input name="appointment_id" type="hidden" value="{{ $appointmentId }}">
        @endif
        @error('appointment_id')<div class="alert alert-danger">{{ $message }}</div>@enderror

        @if ($consultation->exists && ($consultation->serial_no !== null || $consultation->invoice_no !== null))
            <div class="row g-2 mb-3">
                @if ($consultation->serial_no !== null)
                    <div class="col-md-4">
                        <label class="form-label small mb-1" for="consultation_serial_no">Serial</label>
                        <input class="form-control form-control-sm" id="consultation_serial_no" type="text" value="{{ $consultation->serial_no }}" readonly>
                    </div>
                @endif
                @if ($consultation->invoice_no !== null)
                    <div class="col-md-4">
                        <label class="form-label small mb-1" for="consultation_invoice_no">Invoice No.</label>
                        <input class="form-control form-control-sm" id="consultation_invoice_no" type="text" value="{{ $consultation->invoice_no }}" readonly>
                    </div>
                @endif
            </div>
        @endif

        <div class="row g-3">
             <div class="col-md-4">
                @include('hospital::patients.selector', ['selectedPatient' => $selectedPatient, 'selectorId' => 'consultation_patient_id'])
            </div>
            <div class="col-md-4">
                <label class="form-label" for="consultation_entry_date">Entry date <span class="text-danger" aria-hidden="true">*</span></label>
                <div class="input-group" data-datepicker>
                    <input class="{{ inputClass('entry_date') }}" id="consultation_entry_date" name="entry_date" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="{{ getValue('entry_date', null, $entryDate) }}" required data-input {{ inputAttrs('entry_date', 'consultation_entry_date') }}>
                    <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="consultation_entry_date" aria-label="Choose entry date" title="Choose entry date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
                </div>
                @error('entry_date')<div class="invalid-feedback d-block" id="consultation_entry_date-error">{{ $message }}</div>@enderror
            </div>
            <fieldset class="col-md-4" {{ inputAttrs('patient_type', 'consultation_patient_type') }}>
                <legend class="form-label mb-2">Patient type <span class="text-danger" aria-hidden="true">*</span></legend>
                @foreach ($patientTypes as $value => $label)
                    <div class="form-check form-check-inline">
                        <input class="{{ inputClass('patient_type', 'form-check-input') }}" id="consultation_patient_type_{{ $value }}" name="patient_type" type="radio" value="{{ $value }}" {{ getChecked($value, 'patient_type', $consultation, 'new') }} required>
                        <label class="form-check-label small" for="consultation_patient_type_{{ $value }}">{{ $label }}</label>
                    </div>
                @endforeach
                @error('patient_type')<div class="invalid-feedback d-block" id="consultation_patient_type-error">{{ $message }}</div>@enderror
            </fieldset>

        </div>

        <div class="row g-3 mt-0">
            <div class="col-md-4">
                <label class="form-label" for="consultation_doctor_id">Doctor <span class="text-danger" aria-hidden="true">*</span></label>
                <select class="{{ inputClass('doctor_id', 'form-select') }}" id="consultation_doctor_id" name="doctor_id" data-ajax-select data-url="{{ route('hospital.doctors.options') }}" data-value-field="id" data-label-field="text" data-placeholder="Select a doctor" required {{ inputAttrs('doctor_id', 'consultation_doctor_id') }}>
                    <option value=""></option>
                    @if ($selectedDoctor !== null)
                        <option value="{{ $selectedDoctor->id }}" data-consultation-fee="{{ $selectedDoctor->consultation_fee }}" data-follow-up-fee="{{ $selectedDoctor->follow_up_fee }}" selected>{{ $selectedDoctor->doctor_id ?: $selectedDoctor->id }} — {{ $selectedDoctor->name }}</option>
                    @endif
                </select>
                @error('doctor_id')<div class="invalid-feedback d-block" id="consultation_doctor_id-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label" for="consultation_payment_type_id">Payment type <span class="text-danger" aria-hidden="true">*</span></label>
                <select class="{{ inputClass('payment_type_id', 'form-select') }}" id="consultation_payment_type_id" name="payment_type_id" required {{ inputAttrs('payment_type_id', 'consultation_payment_type_id') }}>
                    <option value="">Select payment type</option>
                    @foreach ($paymentTypes as $paymentType)
                        <option value="{{ $paymentType->id }}" {{ getSelected($paymentType->id, 'payment_type_id', $consultation) }}>{{ $paymentType->name }}</option>
                    @endforeach
                </select>
                @error('payment_type_id')<div class="invalid-feedback d-block" id="consultation_payment_type_id-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label" for="consultation_due_date">Due date <span class="text-danger" aria-hidden="true">*</span></label>
                <div class="input-group" data-datepicker>
                    <input class="{{ inputClass('due_date') }}" id="consultation_due_date" name="due_date" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="{{ getValue('due_date', null, $dueDate) }}" required data-input {{ inputAttrs('due_date', 'consultation_due_date') }}>
                    <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="consultation_due_date" aria-label="Choose due date" title="Choose due date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
                </div>
                @error('due_date')<div class="invalid-feedback d-block" id="consultation_due_date-error">{{ $message }}</div>@enderror
            </div>
        </div>

        @if ($referenceEnabled)
            <div class="row g-3 mt-0">
                <div class="col-md-4">
                    <label class="form-label" for="consultation_reference_id">Reference</label>
                    <select class="{{ inputClass('reference_id', 'form-select') }}" id="consultation_reference_id" name="reference_id" data-ajax-select data-url="{{ route('hospital.references.options') }}" data-value-field="id" data-label-field="text" data-placeholder="Select a reference" {{ inputAttrs('reference_id', 'consultation_reference_id') }}>
                        <option value=""></option>
                        @if ($selectedReference !== null)
                            <option value="{{ $selectedReference->id }}" selected>{{ $selectedReference->reference_id }} — {{ $selectedReference->name }}{{ $selectedReference->contact_no ? ' ('.$selectedReference->contact_no.')' : '' }}</option>
                        @endif
                    </select>
                    @error('reference_id')<div class="invalid-feedback d-block" id="consultation_reference_id-error">{{ $message }}</div>@enderror
                </div>
            </div>
        @endif

        <input name="discount_mode" type="hidden" value="{{ getValue('discount_mode', $consultation, 'percent') }}" data-clinical-discount-mode>

        <section class="app-form-section mt-3" aria-labelledby="consultation_payment_heading">
            <h2 class="h6 mb-3" id="consultation_payment_heading">Payment details</h2>
            <div class="row g-3">
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="consultation_amount">Amount</label>
                    <input class="form-control" id="consultation_amount" type="text" value="{{ $consultation->amount ?? '0.00' }}" readonly data-clinical-amount>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="consultation_discount_percent">Discount % <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="{{ inputClass('discount_percent') }}" id="consultation_discount_percent" name="discount_percent" type="number" inputmode="decimal" min="0" max="100" step="0.01" value="{{ getValue('discount_percent', $consultation, '0.00') }}" required data-clinical-discount-percent {{ inputAttrs('discount_percent', 'consultation_discount_percent') }}>
                    @error('discount_percent')<div class="invalid-feedback d-block" id="consultation_discount_percent-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="consultation_discount_amount">Discount amount <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="{{ inputClass('discount_amount') }}" id="consultation_discount_amount" name="discount_amount" type="text" inputmode="decimal" maxlength="13" value="{{ getValue('discount_amount', $consultation, '0.00') }}" required data-clinical-discount-amount {{ inputAttrs('discount_amount', 'consultation_discount_amount') }}>
                    @error('discount_amount')<div class="invalid-feedback d-block" id="consultation_discount_amount-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="consultation_payable_amount">Payable amount</label>
                    <input class="form-control" id="consultation_payable_amount" type="text" value="{{ $consultation->payable_amount ?? '0.00' }}" readonly data-clinical-payable>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="consultation_paid_amount">Paid amount <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="{{ inputClass('paid_amount') }}" id="consultation_paid_amount" name="paid_amount" type="text" inputmode="decimal" maxlength="13" value="{{ getValue('paid_amount', $consultation, '0.00') }}" required data-clinical-paid {{ inputAttrs('paid_amount', 'consultation_paid_amount') }}>
                    @error('paid_amount')<div class="invalid-feedback d-block" id="consultation_paid_amount-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="consultation_due_amount">Due amount</label>
                    <input class="form-control" id="consultation_due_amount" type="text" value="{{ $consultation->due_amount ?? '0.00' }}" readonly data-clinical-due>
                </div>
            </div>
        </section>

        @include('core::partials.form-actions', ['model' => $consultation, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel])
    </form>

    @include('hospital::patients.modal')
@endsection
