@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle, 'description' => 'Record the operation and patient payment details.'])
    @include('hospital::partials.clinical-tabs')

    <form
        class="app-card p-3 p-md-4"
        action="{{ $formAction }}"
        method="POST"
        data-clinical-entry="operation"
        data-record-exists="{{ $patient_operation->exists ? '1' : '0' }}"
        data-initial-amount="{{ $patient_operation->amount ?? '0.00' }}"
        data-original-source-ids="{{ json_encode(($patient_operation->exists ? [$patient_operation->operation_id] : [])) }}"
        data-original-patient-type="{{ $patient_operation->patient_type ?? 'new' }}"
        data-original-eye-type="{{ $patient_operation->eye_type ?? 'left' }}"
    >
        @csrf
        @if ($formMethod !== 'POST')
            @method($formMethod)
        @endif

        @if ($patient_operation->exists && $patient_operation->invoice_no !== null)
            <div class="row g-2 mb-3">
                <div class="col-md-4">
                    <label class="form-label small mb-1" for="operation_invoice_no">Invoice No.</label>
                    <input class="form-control form-control-sm" id="operation_invoice_no" type="text" value="{{ $patient_operation->invoice_no }}" readonly>
                </div>
            </div>
        @endif

        <div class="row g-3">
            <div class="col-md-4">
                @include('hospital::patients.selector', ['selectedPatient' => $selectedPatient, 'selectorId' => 'operation_patient_id'])
            </div>
            <div class="col-md-4">
                <label class="form-label" for="operation_entry_date">Entry date <span class="text-danger" aria-hidden="true">*</span></label>
                <div class="input-group" data-datepicker>
                    <input class="{{ inputClass('entry_date') }}" id="operation_entry_date" name="entry_date" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="{{ getValue('entry_date', null, $entryDate) }}" required data-input {{ inputAttrs('entry_date', 'operation_entry_date') }}>
                    <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="operation_entry_date" aria-label="Choose entry date" title="Choose entry date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
                </div>
                @error('entry_date')<div class="invalid-feedback d-block" id="operation_entry_date-error">{{ $message }}</div>@enderror
            </div>
            <fieldset class="col-md-4" {{ inputAttrs('eye_type', 'operation_eye_type') }}>
                <legend class="form-label mb-2">Eye type <span class="text-danger" aria-hidden="true">*</span></legend>
                <div class="btn-group w-100" role="group" aria-label="Eye type">
                    @foreach ($eyeTypes as $value => $label)
                        <input class="{{ inputClass('eye_type', 'btn-check') }}" id="operation_eye_type_{{ $value }}" name="eye_type" type="radio" value="{{ $value }}" {{ getChecked($value, 'eye_type', $patient_operation, 'left') }} required>
                        <label class="btn btn-outline-primary" for="operation_eye_type_{{ $value }}"><i class="fa-solid fa-eye me-2" aria-hidden="true"></i>{{ $label }}</label>
                    @endforeach
                </div>
                @error('eye_type')<div class="invalid-feedback d-block" id="operation_eye_type-error">{{ $message }}</div>@enderror
            </fieldset>
        </div>

        <div class="row g-3 mt-0">
            <div class="col-md-4">
                <label class="form-label" for="operation_doctor_id">Doctor <span class="text-danger" aria-hidden="true">*</span></label>
                <select class="{{ inputClass('doctor_id', 'form-select') }}" id="operation_doctor_id" name="doctor_id" data-ajax-select data-url="{{ route('hospital.doctors.options') }}" data-value-field="id" data-label-field="text" data-placeholder="Select a doctor" required {{ inputAttrs('doctor_id', 'operation_doctor_id') }}>
                    <option value=""></option>
                    @if ($selectedDoctor !== null)
                        <option value="{{ $selectedDoctor->id }}" data-consultation-fee="{{ $selectedDoctor->consultation_fee }}" data-follow-up-fee="{{ $selectedDoctor->follow_up_fee }}" selected>{{ $selectedDoctor->doctor_id ?: $selectedDoctor->id }} — {{ $selectedDoctor->name }}</option>
                    @endif
                </select>
                @error('doctor_id')<div class="invalid-feedback d-block" id="operation_doctor_id-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label" for="operation_payment_type_id">Payment type <span class="text-danger" aria-hidden="true">*</span></label>
                <select class="{{ inputClass('payment_type_id', 'form-select') }}" id="operation_payment_type_id" name="payment_type_id" required {{ inputAttrs('payment_type_id', 'operation_payment_type_id') }}>
                    <option value="">Select payment type</option>
                    @foreach ($paymentTypes as $paymentType)
                        <option value="{{ $paymentType->id }}" {{ getSelected($paymentType->id, 'payment_type_id', $patient_operation) }}>{{ $paymentType->name }}</option>
                    @endforeach
                </select>
                @error('payment_type_id')<div class="invalid-feedback d-block" id="operation_payment_type_id-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label" for="operation_consultant_id">Consultant</label>
                <select class="{{ inputClass('consultant_id', 'form-select') }}" id="operation_consultant_id" name="consultant_id" data-ajax-select data-url="{{ route('hospital.doctors.options') }}" data-value-field="id" data-label-field="text" data-placeholder="Select a consultant" {{ inputAttrs('consultant_id', 'operation_consultant_id') }}>
                    <option value=""></option>
                    @if ($selectedConsultant !== null)
                        <option value="{{ $selectedConsultant->id }}" selected>{{ $selectedConsultant->doctor_id ?: $selectedConsultant->id }} — {{ $selectedConsultant->name }}</option>
                    @endif
                </select>
                @error('consultant_id')<div class="invalid-feedback d-block" id="operation_consultant_id-error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="row g-3 mt-0">
            <div class="col-md-4">
                <label class="form-label" for="operation_operation_id">Operation <span class="text-danger" aria-hidden="true">*</span></label>
                <select class="{{ inputClass('operation_id', 'form-select') }}" id="operation_operation_id" name="operation_id" data-ajax-select data-url="{{ route('hospital.operations.options') }}" data-value-field="id" data-label-field="name" data-placeholder="Select an operation" required {{ inputAttrs('operation_id', 'operation_operation_id') }}>
                    <option value=""></option>
                    @if ($selectedOperation !== null)
                        <option value="{{ $selectedOperation->id }}" data-single-rate="{{ $selectedOperation->single_rate }}" data-both-rate="{{ $selectedOperation->both_rate }}" selected>{{ $selectedOperation->name }}</option>
                    @endif
                </select>
                @error('operation_id')<div class="invalid-feedback d-block" id="operation_operation_id-error">{{ $message }}</div>@enderror
            </div>
            <fieldset class="col-md-4" {{ inputAttrs('patient_type', 'operation_patient_type') }}>
                <legend class="form-label mb-2">Patient type <span class="text-danger" aria-hidden="true">*</span></legend>
                @foreach ($patientTypes as $value => $label)
                    <div class="form-check form-check-inline">
                        <input class="{{ inputClass('patient_type', 'form-check-input') }}" id="operation_patient_type_{{ $value }}" name="patient_type" type="radio" value="{{ $value }}" {{ getChecked($value, 'patient_type', $patient_operation, 'new') }} required>
                        <label class="form-check-label small" for="operation_patient_type_{{ $value }}">{{ $label }}</label>
                    </div>
                @endforeach
                @error('patient_type')<div class="invalid-feedback d-block" id="operation_patient_type-error">{{ $message }}</div>@enderror
            </fieldset>
            <div class="col-md-4">
                <label class="form-label" for="operation_operation_date">Operation date <span class="text-danger" aria-hidden="true">*</span></label>
                <div class="input-group" data-datepicker>
                    <input class="{{ inputClass('operation_date') }}" id="operation_operation_date" name="operation_date" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="{{ getValue('operation_date', null, $operationDate) }}" required data-input {{ inputAttrs('operation_date', 'operation_operation_date') }}>
                    <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="operation_operation_date" aria-label="Choose operation date" title="Choose operation date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
                </div>
                @error('operation_date')<div class="invalid-feedback d-block" id="operation_operation_date-error">{{ $message }}</div>@enderror
            </div>
        </div>

        @if ($referenceEnabled)
            <div class="row g-3 mt-0">
                <div class="col-md-4">
                    <label class="form-label" for="operation_reference_id">Reference</label>
                    <select class="{{ inputClass('reference_id', 'form-select') }}" id="operation_reference_id" name="reference_id" data-ajax-select data-url="{{ route('hospital.references.options') }}" data-value-field="id" data-label-field="text" data-placeholder="Select a reference" {{ inputAttrs('reference_id', 'operation_reference_id') }}>
                        <option value=""></option>
                        @if ($selectedReference !== null)
                            <option value="{{ $selectedReference->id }}" selected>{{ $selectedReference->reference_id }} — {{ $selectedReference->name }}{{ $selectedReference->contact_no ? ' ('.$selectedReference->contact_no.')' : '' }}</option>
                        @endif
                    </select>
                    @error('reference_id')<div class="invalid-feedback d-block" id="operation_reference_id-error">{{ $message }}</div>@enderror
                </div>
            </div>
        @endif

        <section class="app-form-section mt-3" aria-labelledby="operation_payment_heading">
            <h2 class="h6 mb-3" id="operation_payment_heading">Payment details</h2>
            <div class="row g-3">
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="operation_amount">Amount</label>
                    <input class="form-control" id="operation_amount" type="text" value="{{ $patient_operation->amount ?? '0.00' }}" readonly data-clinical-amount>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="operation_discount_amount">Discount amount <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="{{ inputClass('discount_amount') }}" id="operation_discount_amount" name="discount_amount" type="text" inputmode="decimal" maxlength="13" value="{{ getValue('discount_amount', $patient_operation, '0.00') }}" required data-clinical-discount-amount {{ inputAttrs('discount_amount', 'operation_discount_amount') }}>
                    @error('discount_amount')<div class="invalid-feedback d-block" id="operation_discount_amount-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="operation_payable_amount">Payable amount</label>
                    <input class="form-control" id="operation_payable_amount" type="text" value="{{ $patient_operation->payable_amount ?? '0.00' }}" readonly data-clinical-payable>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="operation_paid_amount">Paid amount <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="{{ inputClass('paid_amount') }}" id="operation_paid_amount" name="paid_amount" type="text" inputmode="decimal" maxlength="13" value="{{ getValue('paid_amount', $patient_operation, '0.00') }}" required data-clinical-paid {{ inputAttrs('paid_amount', 'operation_paid_amount') }}>
                    @error('paid_amount')<div class="invalid-feedback d-block" id="operation_paid_amount-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="operation_due_amount">Due amount</label>
                    <input class="form-control" id="operation_due_amount" type="text" value="{{ $patient_operation->due_amount ?? '0.00' }}" readonly data-clinical-due>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="operation_due_date">Due date <span class="text-danger" aria-hidden="true">*</span></label>
                    <div class="input-group" data-datepicker>
                        <input class="{{ inputClass('due_date') }}" id="operation_due_date" name="due_date" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="{{ getValue('due_date', null, $dueDate) }}" required data-input {{ inputAttrs('due_date', 'operation_due_date') }}>
                        <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="operation_due_date" aria-label="Choose due date" title="Choose due date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
                    </div>
                    @error('due_date')<div class="invalid-feedback d-block" id="operation_due_date-error">{{ $message }}</div>@enderror
                </div>
            </div>
        </section>

        <div class="mt-3">
            <label class="form-label" for="operation_remarks">Remarks</label>
            <textarea class="{{ inputClass('remarks') }}" id="operation_remarks" name="remarks" rows="2" maxlength="2000" {{ inputAttrs('remarks', 'operation_remarks') }}>{{ getValue('remarks', $patient_operation) }}</textarea>
            @error('remarks')<div class="invalid-feedback d-block" id="operation_remarks-error">{{ $message }}</div>@enderror
        </div>

        @include('core::partials.form-actions', ['model' => $patient_operation, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel])
    </form>

    @include('hospital::patients.modal')
@endsection
