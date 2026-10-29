@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle, 'description' => 'Record investigations, delivery, and patient payment details.'])
    @include('hospital::partials.clinical-tabs')

    <form
        class="app-card p-3 p-md-4"
        action="{{ $formAction }}"
        method="POST"
        data-clinical-entry="investigation"
        data-record-exists="{{ $patient_investigation->exists ? '1' : '0' }}"
        data-initial-amount="{{ $patient_investigation->amount ?? '0.00' }}"
        data-original-source-ids="{{ json_encode(($patient_investigation->exists ? $patient_investigation->investigations->modelKeys() : [])) }}"
        data-original-eye-type="{{ $patient_investigation->eye_type ?? 'left' }}"
        data-investigation-eyes="{{ json_encode(getValue('investigation_eyes', null, $investigationEyes)) }}"
        data-original-investigation-eyes="{{ json_encode($investigationEyes) }}"
        data-original-investigation-rates="{{ json_encode($investigationRates) }}"
    >
        @csrf
        @if ($formMethod !== 'POST')
            @method($formMethod)
        @endif

        @if ($patient_investigation->exists && $patient_investigation->invoice_no !== null)
            <div class="row g-2 mb-3">
                <div class="col-md-4">
                    <label class="form-label small mb-1" for="investigation_invoice_no">Invoice No.</label>
                    <input class="form-control form-control-sm" id="investigation_invoice_no" type="text" value="{{ $patient_investigation->invoice_no }}" readonly>
                </div>
            </div>
        @endif

        <div class="row g-3">
            <div class="col-md-4">
                @include('hospital::patients.selector', ['selectedPatient' => $selectedPatient, 'selectorId' => 'investigation_patient_id'])
            </div>
            <div class="col-md-4">
                <label class="form-label" for="investigation_entry_date">Entry date <span class="text-danger" aria-hidden="true">*</span></label>
                <div class="input-group" data-datepicker>
                    <input class="{{ inputClass('entry_date') }}" id="investigation_entry_date" name="entry_date" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="{{ getValue('entry_date', null, $entryDate) }}" required data-input {{ inputAttrs('entry_date', 'investigation_entry_date') }}>
                    <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="investigation_entry_date" aria-label="Choose entry date" title="Choose entry date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
                </div>
                @error('entry_date')<div class="invalid-feedback d-block" id="investigation_entry_date-error">{{ $message }}</div>@enderror
            </div>
            <fieldset class="col-md-4" {{ inputAttrs('eye_type', 'investigation_eye_type', 'investigation_eye_type-help') }}>
                <legend class="form-label mb-2">Eye type <span class="text-danger" aria-hidden="true">*</span></legend>
                <div class="btn-group w-100" role="group" aria-label="Eye type">
                    @foreach ($eyeTypes as $value => $label)
                        <input class="{{ inputClass('eye_type', 'btn-check') }}" id="investigation_eye_type_{{ $value }}" name="eye_type" type="radio" value="{{ $value }}" {{ getChecked($value, 'eye_type', $patient_investigation, 'left') }} required>
                        <label class="btn btn-outline-primary" for="investigation_eye_type_{{ $value }}"><i class="fa-solid fa-eye me-2" aria-hidden="true"></i>{{ $label }}</label>
                    @endforeach
                </div>
                <p class="form-text mb-0" id="investigation_eye_type-help">Default eye type for new tests; edit individual rows below.</p>
                @error('eye_type')<div class="invalid-feedback d-block" id="investigation_eye_type-error">{{ $message }}</div>@enderror
            </fieldset>
        </div>

        <div class="row g-3 mt-0">
            <div class="col-md-4">
                <label class="form-label" for="investigation_doctor_id">Doctor <span class="text-danger" aria-hidden="true">*</span></label>
                <select class="{{ inputClass('doctor_id', 'form-select') }}" id="investigation_doctor_id" name="doctor_id" data-ajax-select data-url="{{ route('hospital.doctors.options') }}" data-value-field="id" data-label-field="text" data-placeholder="Select a doctor" required {{ inputAttrs('doctor_id', 'investigation_doctor_id') }}>
                    <option value=""></option>
                    @if ($selectedDoctor !== null)
                        <option value="{{ $selectedDoctor->id }}" data-consultation-fee="{{ $selectedDoctor->consultation_fee }}" data-follow-up-fee="{{ $selectedDoctor->follow_up_fee }}" selected>{{ $selectedDoctor->doctor_id ?: $selectedDoctor->id }} — {{ $selectedDoctor->name }}</option>
                    @endif
                </select>
                @error('doctor_id')<div class="invalid-feedback d-block" id="investigation_doctor_id-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label" for="investigation_payment_type_id">Payment type <span class="text-danger" aria-hidden="true">*</span></label>
                <select class="{{ inputClass('payment_type_id', 'form-select') }}" id="investigation_payment_type_id" name="payment_type_id" required {{ inputAttrs('payment_type_id', 'investigation_payment_type_id') }}>
                    <option value="">Select payment type</option>
                    @foreach ($paymentTypes as $paymentType)
                        <option value="{{ $paymentType->id }}" {{ getSelected($paymentType->id, 'payment_type_id', $patient_investigation) }}>{{ $paymentType->name }}</option>
                    @endforeach
                </select>
                @error('payment_type_id')<div class="invalid-feedback d-block" id="investigation_payment_type_id-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label" for="investigation_delivery_date">Delivery date <span class="text-danger" aria-hidden="true">*</span></label>
                <div class="input-group" data-datepicker>
                    <input class="{{ inputClass('delivery_date') }}" id="investigation_delivery_date" name="delivery_date" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="{{ getValue('delivery_date', null, $deliveryDate) }}" required data-input {{ inputAttrs('delivery_date', 'investigation_delivery_date') }}>
                    <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="investigation_delivery_date" aria-label="Choose delivery date" title="Choose delivery date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
                </div>
                @error('delivery_date')<div class="invalid-feedback d-block" id="investigation_delivery_date-error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="mt-3">
            <label class="form-label" for="investigation_investigation_ids">Investigations <span class="text-danger" aria-hidden="true">*</span></label>
            <select class="{{ inputClass('investigation_ids', 'form-select') }}" id="investigation_investigation_ids" data-investigation-picker data-ajax-select data-url="{{ route('hospital.investigations.options') }}" data-value-field="id" data-label-field="name" data-placeholder="Select a test to add" @disabled($selectedDoctor === null) {{ inputAttrs('investigation_ids', 'investigation_investigation_ids', 'investigation_doctor-help investigation_picker-help') }}>
                <option value=""></option>
            </select>
            <select name="investigation_ids[]" data-investigation-items multiple hidden>
                @foreach ($selectedInvestigations as $investigation)
                    <option value="{{ $investigation->id }}" data-single-rate="{{ $investigation->single_rate }}" data-both-rate="{{ $investigation->both_rate }}" data-category-name="{{ $investigation->category?->name }}" data-entry-rate="{{ $investigationRates[$investigation->id] ?? '' }}" data-entry-eye="{{ $investigationEyes[$investigation->id] ?? $patient_investigation->eye_type ?? 'left' }}" selected>{{ $investigation->name }}</option>
                @endforeach
            </select>
            <p class="form-text mb-0" id="investigation_doctor-help" data-investigation-doctor-help @if ($selectedDoctor !== null) hidden @endif>Select a doctor before adding investigations.</p>
            <p class="form-text mb-0" id="investigation_picker-help" data-investigation-picker-help aria-live="polite">Select one test at a time. Each selection is added below.</p>
            @error('investigation_ids')<div class="invalid-feedback d-block" id="investigation_investigation_ids-error">{{ $message }}</div>@enderror
            @error('investigation_ids.*')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
        <div class="table-responsive border rounded mt-3">
            <table class="table app-table align-middle mb-0">
                <caption class="visually-hidden">Selected investigations, doctor, categories, eye types, and rates</caption>
                <thead><tr><th scope="col">Doctor</th><th scope="col">Test</th><th scope="col">Category</th><th scope="col">Eye</th><th class="text-end" scope="col">Rate</th><th class="text-end" scope="col">Actions</th></tr></thead>
                <tbody data-clinical-investigation-items></tbody>
            </table>
        </div>

        @if ($referenceEnabled)
            <div class="row g-3 mt-0">
                <div class="col-md-4">
                    <label class="form-label" for="investigation_reference_id">Reference</label>
                    <select class="{{ inputClass('reference_id', 'form-select') }}" id="investigation_reference_id" name="reference_id" data-ajax-select data-url="{{ route('hospital.references.options') }}" data-value-field="id" data-label-field="text" data-placeholder="Select a reference" {{ inputAttrs('reference_id', 'investigation_reference_id') }}>
                        <option value=""></option>
                        @if ($selectedReference !== null)
                            <option value="{{ $selectedReference->id }}" selected>{{ $selectedReference->reference_id }} — {{ $selectedReference->name }}{{ $selectedReference->contact_no ? ' ('.$selectedReference->contact_no.')' : '' }}</option>
                        @endif
                    </select>
                    @error('reference_id')<div class="invalid-feedback d-block" id="investigation_reference_id-error">{{ $message }}</div>@enderror
                </div>
            </div>
        @endif

        <input name="discount_mode" type="hidden" value="{{ getValue('discount_mode', $patient_investigation, 'percent') }}" data-clinical-discount-mode>

        <section class="app-form-section mt-3" aria-labelledby="investigation_payment_heading">
            <h2 class="h6 mb-3" id="investigation_payment_heading">Payment details</h2>
            <div class="row g-3">
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="investigation_amount">Amount</label>
                    <input class="form-control" id="investigation_amount" type="text" value="{{ $patient_investigation->amount ?? '0.00' }}" readonly data-clinical-amount>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="investigation_discount_percent">Discount % <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="{{ inputClass('discount_percent') }}" id="investigation_discount_percent" name="discount_percent" type="number" inputmode="decimal" min="0" max="100" step="0.01" value="{{ getValue('discount_percent', $patient_investigation, '0.00') }}" required data-clinical-discount-percent {{ inputAttrs('discount_percent', 'investigation_discount_percent') }}>
                    @error('discount_percent')<div class="invalid-feedback d-block" id="investigation_discount_percent-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="investigation_discount_amount">Discount amount <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="{{ inputClass('discount_amount') }}" id="investigation_discount_amount" name="discount_amount" type="text" inputmode="decimal" maxlength="13" value="{{ getValue('discount_amount', $patient_investigation, '0.00') }}" required data-clinical-discount-amount {{ inputAttrs('discount_amount', 'investigation_discount_amount') }}>
                    @error('discount_amount')<div class="invalid-feedback d-block" id="investigation_discount_amount-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="investigation_extra_discount_percent">Extra discount % <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="{{ inputClass('extra_discount_percent') }}" id="investigation_extra_discount_percent" name="extra_discount_percent" type="number" inputmode="decimal" min="0" max="100" step="0.01" value="{{ getValue('extra_discount_percent', $patient_investigation, '0.00') }}" required data-clinical-extra-discount-percent {{ inputAttrs('extra_discount_percent', 'investigation_extra_discount_percent') }}>
                    @error('extra_discount_percent')<div class="invalid-feedback d-block" id="investigation_extra_discount_percent-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="investigation_payable_amount">Payable amount</label>
                    <input class="form-control" id="investigation_payable_amount" type="text" value="{{ $patient_investigation->payable_amount ?? '0.00' }}" readonly data-clinical-payable>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="investigation_paid_amount">Paid amount <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="{{ inputClass('paid_amount') }}" id="investigation_paid_amount" name="paid_amount" type="text" inputmode="decimal" maxlength="13" value="{{ getValue('paid_amount', $patient_investigation, '0.00') }}" required data-clinical-paid {{ inputAttrs('paid_amount', 'investigation_paid_amount') }}>
                    @error('paid_amount')<div class="invalid-feedback d-block" id="investigation_paid_amount-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="investigation_due_amount">Due amount</label>
                    <input class="form-control" id="investigation_due_amount" type="text" value="{{ $patient_investigation->due_amount ?? '0.00' }}" readonly data-clinical-due>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="investigation_due_date">Due date <span class="text-danger" aria-hidden="true">*</span></label>
                    <div class="input-group" data-datepicker>
                        <input class="{{ inputClass('due_date') }}" id="investigation_due_date" name="due_date" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="{{ getValue('due_date', null, $dueDate) }}" required data-input {{ inputAttrs('due_date', 'investigation_due_date') }}>
                        <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="investigation_due_date" aria-label="Choose due date" title="Choose due date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
                    </div>
                    @error('due_date')<div class="invalid-feedback d-block" id="investigation_due_date-error">{{ $message }}</div>@enderror
                </div>
            </div>
        </section>

        @include('core::partials.form-actions', ['model' => $patient_investigation, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel])
    </form>

    @include('hospital::patients.modal')
@endsection
