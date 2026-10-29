@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle, 'description' => 'Select an invoice to adjust its due and refund any remaining paid value.'])

    <form class="app-card p-3 p-md-4" action="{{ $formAction }}" method="POST" data-refund-form
        data-record-exists="{{ $refund->exists ? '1' : '0' }}"
        data-preview-url="{{ route('hospital.refunds.invoice') }}"
        data-initial-preview="{{ json_encode($refundPreview) }}"
        data-selected-item-ids="{{ json_encode($selectedItemIds) }}"
        data-fixed-amount="{{ $refund->amount ?? '0.00' }}"
        data-fixed-due-adjustment="{{ $refund->due_adjustment ?? '0.00' }}"
    >
        @csrf
        @if ($formMethod !== 'POST')
            @method($formMethod)
        @endif

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label" for="refund_service">Service <span class="text-danger" aria-hidden="true">*</span></label>
                <select class="{{ inputClass('service', 'form-select') }}" id="refund_service" name="service" required @disabled($refund->exists) {{ inputAttrs('service', 'refund_service') }}>
                    @foreach ($serviceOptions as $value => $label)
                        <option value="{{ $value }}" @selected($selectedService === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('service')<div class="invalid-feedback d-block" id="refund_service-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-8">
                <label class="form-label" for="refund_invoice">Invoice <span class="text-danger" aria-hidden="true">*</span></label>
                <select class="{{ inputClass('invoice', 'form-select') }}" id="refund_invoice" name="invoice" data-refund-invoice required @disabled($refund->exists)
                    @if (! $refund->exists)
                        data-ajax-select data-url="{{ route('hospital.refunds.invoices') }}" data-value-field="id" data-label-field="text" data-placeholder="Search invoice or patient" data-parent-select="refund_service" data-parent-field="service" data-no-results-text="No matching refundable invoice is available."
                    @endif
                    {{ inputAttrs('invoice', 'refund_invoice', 'refund_invoice-help refund_preview-error') }}
                >
                    <option value=""></option>
                    @if ($selectedInvoice !== null)
                        <option value="{{ $selectedInvoice['id'] }}" data-service="{{ $selectedInvoice['service'] }}" selected>{{ $selectedInvoice['text'] }}</option>
                    @endif
                </select>
                <div class="form-text" id="refund_invoice-help">{{ $refund->exists ? 'The service, invoice, and refunded items are fixed after saving.' : 'Search by invoice number, patient name, or mobile. Select the investigation items to refund.' }}</div>
                @error('invoice')<div class="invalid-feedback d-block" id="refund_invoice-error">{{ $message }}</div>@enderror
                <div class="form-text" data-refund-status role="status" aria-live="polite" hidden></div>
                <div class="alert alert-danger py-2 mt-2 mb-0" id="refund_preview-error" data-refund-error role="alert" hidden></div>
            </div>
            <div class="col-12" data-refund-items-section @if (($refundPreview['service'] ?? null) !== 'investigation') hidden @endif>
                <div class="table-responsive">
                    <table class="table app-table align-middle mb-0">
                        <caption class="caption-top pt-0">Investigation items</caption>
                        <thead>
                            <tr>
                                <th scope="col">Select</th>
                                <th scope="col">Test</th>
                                <th scope="col">Room</th>
                                <th scope="col">Eye</th>
                                <th class="text-end" scope="col">Refund value</th>
                            </tr>
                        </thead>
                        <tbody data-refund-items>
                            @foreach ($refundPreview['items'] ?? [] as $item)
                                <tr>
                                    <td>
                                        <input class="{{ inputClass('item_ids', 'form-check-input') }}" id="refund_item_{{ $item['id'] }}" name="item_ids[]" type="checkbox" value="{{ $item['id'] }}" data-refund-item data-amount="{{ $item['amount'] }}" @checked(in_array((int) $item['id'], $selectedItemIds, true)) @disabled($refund->exists) aria-describedby="refund_items-help">
                                    </td>
                                    <th class="fw-normal text-break" scope="row"><label class="mb-0" for="refund_item_{{ $item['id'] }}">{{ $item['name'] }}</label></th>
                                    <td>{{ filled($item['room']) ? $item['room'] : '-' }}</td>
                                    <td>{{ $item['eye_label'] }}</td>
                                    <td class="text-end text-nowrap">{{ $item['amount'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="form-text" id="refund_items-help">Select one or more items. Values include the invoice discounts.</div>
                @error('item_ids')<div class="invalid-feedback d-block" id="item_ids-error">{{ $message }}</div>@enderror
                @error('item_ids.*')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label" for="refund_credit_amount">Selected value</label>
                <input class="form-control" id="refund_credit_amount" type="text" value="0.00" readonly data-refund-credit>
            </div>
            <div class="col-md-4">
                <label class="form-label" for="refund_due_adjustment">Due adjustment</label>
                <input class="form-control" id="refund_due_adjustment" type="text" value="{{ $refund->exists ? $refund->due_adjustment : '0.00' }}" readonly data-refund-due-adjustment>
            </div>
            <div class="col-md-4">
                <label class="form-label" for="refund_amount">Cash refund</label>
                <input class="form-control" id="refund_amount" type="text" value="{{ $refund->exists ? $refund->amount : '0.00' }}" readonly data-refund-amount aria-describedby="refund_amount-help">
                <div class="form-text" id="refund_amount-help">The selected value reduces the outstanding due first. Any balance is refunded in cash.</div>
            </div>
            <div class="col-md-4">
                <label class="form-label" for="cause_id">Refund cause <span class="text-danger" aria-hidden="true">*</span></label>
                <select class="{{ inputClass('cause_id', 'form-select') }}" id="cause_id" name="cause_id" required {{ inputAttrs('cause_id') }}>
                    <option value="">Select a refund cause</option>
                    @foreach ($refundCauses as $cause)
                        <option value="{{ $cause->id }}" {{ getSelected($cause->id, 'cause_id', $refund) }}>{{ $cause->name }}</option>
                    @endforeach
                </select>
                @error('cause_id')<div class="invalid-feedback d-block" id="cause_id-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-8">
                <label class="form-label" for="refund_remarks">Remarks</label>
                <textarea class="{{ inputClass('remarks') }}" id="refund_remarks" name="remarks" rows="2" maxlength="2000" {{ inputAttrs('remarks', 'refund_remarks') }}>{{ getValue('remarks', $refund) }}</textarea>
                @error('remarks')<div class="invalid-feedback d-block" id="refund_remarks-error">{{ $message }}</div>@enderror
            </div>
        </div>

        @include('core::partials.form-actions', ['model' => $refund, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel])
    </form>
@endsection
