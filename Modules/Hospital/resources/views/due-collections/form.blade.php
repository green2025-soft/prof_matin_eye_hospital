@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle, 'description' => 'Collect part or all of an invoice’s outstanding due.'])

    <form class="app-card p-3 p-md-4" action="{{ $formAction }}" method="POST" data-due-collection-form
        data-record-exists="{{ $due_collection->exists ? '1' : '0' }}"
        data-preview-url="{{ route('hospital.due-collections.invoice') }}"
        data-initial-preview="{{ json_encode($collectionPreview) }}"
    >
        @csrf
        @if ($formMethod !== 'POST')
            @method($formMethod)
        @endif

        <div class="row g-3">


            <div class="col-md-3">
                <label class="form-label" for="due_collection_service">Service <span class="text-danger" aria-hidden="true">*</span></label>
                <select class="{{ inputClass('service', 'form-select') }}" id="due_collection_service" name="service" required @disabled($due_collection->exists) {{ inputAttrs('service', 'due_collection_service') }}>
                    @foreach ($serviceOptions as $value => $label)
                        <option value="{{ $value }}" @selected($selectedService === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('service')<div class="invalid-feedback d-block" id="due_collection_service-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-5">
                <label class="form-label" for="due_collection_invoice">Invoice <span class="text-danger" aria-hidden="true">*</span></label>
                <select class="{{ inputClass('invoice', 'form-select') }}" id="due_collection_invoice" name="invoice" data-due-collection-invoice required @disabled($due_collection->exists)
                    @if (! $due_collection->exists)
                        data-ajax-select data-url="{{ route('hospital.due-collections.invoices') }}" data-value-field="id" data-label-field="text" data-placeholder="Search invoice or patient" data-parent-select="due_collection_service" data-parent-field="service" data-no-results-text="No matching invoice has an outstanding due."
                    @endif
                    {{ inputAttrs('invoice', 'due_collection_invoice', 'due_collection_invoice-help due_collection_preview-error') }}
                >
                    <option value=""></option>
                    @if ($selectedInvoice !== null)
                        <option value="{{ $selectedInvoice['id'] }}" data-service="{{ $selectedInvoice['service'] }}" selected>{{ $selectedInvoice['text'] }}</option>
                    @endif
                </select>
                <div class="form-text" id="due_collection_invoice-help">{{ $due_collection->exists ? 'The service and invoice are fixed after saving.' : 'Search by invoice number, patient name, or mobile.' }}</div>
                @error('invoice')<div class="invalid-feedback d-block" id="due_collection_invoice-error">{{ $message }}</div>@enderror
                <div class="form-text" data-due-collection-status role="status" aria-live="polite" hidden></div>
                <div class="alert alert-danger py-2 mt-2 mb-0" id="due_collection_preview-error" data-due-collection-error role="alert" hidden></div>
            </div>
             <div class="col-md-4">
                <label class="form-label" for="due_collection_date">Collection date <span class="text-danger" aria-hidden="true">*</span></label>
                <div class="input-group" data-datepicker>
                    <input class="{{ inputClass('collection_date') }}" id="due_collection_date" name="collection_date" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="{{ getValue('collection_date', null, $collectionDate) }}" required data-input {{ inputAttrs('collection_date', 'due_collection_date') }}>
                    <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="due_collection_date" aria-label="Choose collection date" title="Choose collection date"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></button>
                </div>
                @error('collection_date')<div class="invalid-feedback d-block" id="due_collection_date-error">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label" for="due_collection_current_due">{{ $due_collection->exists ? 'Available due for this collection' : 'Current due' }}</label>
                <input class="form-control" id="due_collection_current_due" type="text" value="{{ $collectionPreview['available_due'] ?? '0.00' }}" readonly data-due-collection-current-due>
            </div>
            <div class="col-md-4">
                <label class="form-label" for="due_collection_amount">Amount <span class="text-danger" aria-hidden="true">*</span></label>
                <input class="{{ inputClass('amount') }}" id="due_collection_amount" name="amount" type="text" inputmode="decimal" maxlength="13" pattern="[0-9]{1,10}([.][0-9]{1,2})?" value="{{ getValue('amount', $due_collection) }}" required data-due-collection-amount {{ inputAttrs('amount', 'due_collection_amount', 'due_collection_amount-help due_collection_amount-client-error') }}>
                <div class="form-text" id="due_collection_amount-help">Enter at least 0.01, up to the current due.</div>
                @error('amount')<div class="invalid-feedback d-block" id="due_collection_amount-error">{{ $message }}</div>@enderror
                <div class="invalid-feedback d-block" id="due_collection_amount-client-error" data-due-collection-amount-error aria-live="polite" aria-atomic="true"></div>
            </div>
            <div class="col-md-4">
                <label class="form-label" for="due_collection_payment_type_id">Payment type <span class="text-danger" aria-hidden="true">*</span></label>
                <select class="{{ inputClass('payment_type_id', 'form-select') }}" id="due_collection_payment_type_id" name="payment_type_id" required {{ inputAttrs('payment_type_id', 'due_collection_payment_type_id') }}>
                    <option value="">Select a payment type</option>
                    @foreach ($paymentTypes as $paymentType)
                        <option value="{{ $paymentType->id }}" {{ getSelected($paymentType->id, 'payment_type_id', $due_collection) }}>{{ $paymentType->name }}</option>
                    @endforeach
                </select>
                @error('payment_type_id')<div class="invalid-feedback d-block" id="due_collection_payment_type_id-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label" for="due_collection_remarks">Remarks</label>
                <textarea class="{{ inputClass('remarks') }}" id="due_collection_remarks" name="remarks" rows="2" maxlength="2000" {{ inputAttrs('remarks', 'due_collection_remarks') }}>{{ getValue('remarks', $due_collection) }}</textarea>
                @error('remarks')<div class="invalid-feedback d-block" id="due_collection_remarks-error">{{ $message }}</div>@enderror
            </div>
        </div>

        @include('core::partials.form-actions', ['model' => $due_collection, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel])
    </form>
@endsection
