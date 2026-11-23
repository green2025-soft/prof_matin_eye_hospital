@extends('tenantcore::reports.layout')

@section('report-filters')
    <form method="GET" action="{{ url()->current() }}" class="row g-3 align-items-end">
        <div class="col-12 col-md-3">
            <label class="form-label" for="from_date">From date</label>
            <div class="input-group" data-datepicker>
                <input class="{{ inputClass('from_date') }}" id="from_date" name="from_date" type="text"
                    value="{{ getValue('from_date', null, $filters['from_date']) }}" placeholder="YYYY-MM-DD"
                    autocomplete="off" required data-input {{ inputAttrs('from_date') }}>
                <button class="btn btn-outline-secondary" type="button" data-toggle aria-label="Choose from date">
                    <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
                </button>
            </div>
            @error('from_date')<div class="invalid-feedback d-block" id="from_date-error">{{ $message }}</div>@enderror
        </div>
        <div class="col-12 col-md-3">
            <label class="form-label" for="to_date">To date</label>
            <div class="input-group" data-datepicker>
                <input class="{{ inputClass('to_date') }}" id="to_date" name="to_date" type="text"
                    value="{{ getValue('to_date', null, $filters['to_date']) }}" placeholder="YYYY-MM-DD"
                    autocomplete="off" required data-input {{ inputAttrs('to_date') }}>
                <button class="btn btn-outline-secondary" type="button" data-toggle aria-label="Choose to date">
                    <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
                </button>
            </div>
            @error('to_date')<div class="invalid-feedback d-block" id="to_date-error">{{ $message }}</div>@enderror
        </div>
        <div class="col-12 col-md-3">
            <label class="form-label" for="patient_id">Patient</label>
            <select class="{{ inputClass('patient_id', 'form-select') }}" id="patient_id" name="patient_id"
                data-ajax-select data-url="{{ route('hospital.patients.options') }}"
                data-value-field="id" data-label-field="text" data-placeholder="All patients"
                {{ inputAttrs('patient_id') }}>
                <option value=""></option>
                @if ($selectedPatient !== null)
                    <option value="{{ $selectedPatient->id }}" selected>
                        {{ $selectedPatient->patient_id }} — {{ $selectedPatient->name }} — {{ $selectedPatient->mobile_no }}
                    </option>
                @endif
            </select>
            @error('patient_id')<div class="invalid-feedback d-block" id="patient_id-error">{{ $message }}</div>@enderror
        </div>
        <div class="col-12 col-md-3 d-flex gap-2">
            <button class="btn btn-primary" type="submit">Show report</button>
            <a class="btn btn-outline-secondary" href="{{ route('hospital.reports.patient-due') }}">Reset</a>
        </div>
    </form>
@endsection

@section('report-table')
    <div class="table-responsive">
        <table class="app-report-table table app-table table-sm align-middle mb-0">
            <caption class="visually-hidden">Patient clinical invoices and current outstanding balances</caption>
            <thead>
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col">Patient</th>
                    <th scope="col">Particular</th>
                    <th class="text-end" scope="col">Amount</th>
                    <th class="text-end" scope="col">Discount</th>
                    <th class="text-end" scope="col">Due Collection</th>
                    <th class="text-end" scope="col">Total Collection</th>
                    <th class="text-end" scope="col">Rest Due</th>
                </tr>
            </thead>
            <tbody>
                @if($records)
                @php
                    $totalAmount        = 0;
                    $totalDiscount      = 0;
                    $totalDuePayment    = 0;
                    $totalPaid          = 0;
                    $totalDue           = 0;
                @endphp
                @foreach ($records as $invoice)
                 @php
                    $totalAmount        += $invoice->getAttribute('report_amount')??0;
                    $totalDiscount      += $invoice->getAttribute('report_discount')??0;
                    $totalDuePayment    += $invoice->getAttribute('report_due_payment')??0;
                    $totalPaid          += $invoice->getAttribute('report_paid')??0;
                    $totalDue           += $invoice->getAttribute('report_due')??0;
                @endphp
                    <tr>
                        <th class="text-nowrap" scope="row">{{ $invoice->entry_date->toDateString() }}</th>
                        <td>
                            <div class="fw-semibold">{{ $invoice->patient?->patient_id }} — {{ $invoice->patient?->name }}</div>
                            <div class="small text-body-secondary">{{ $invoice->patient?->mobile_no }}</div>
                        </td>
                        <td>
                            <div>{{ clinicalServiceOptions()[$invoice::SERVICE] }}</div>
                            <div class="small text-body-secondary">{{ $invoice->invoice_no ?: '#'.$invoice->getKey() }}</div>
                        </td>
                        <td class="text-end text-nowrap">{{ moneyDecimal((string) $invoice->getAttribute('report_amount')) }}</td>
                        <td class="text-end text-nowrap">{{ moneyDecimal((string) $invoice->getAttribute('report_discount')) }}</td>
                        <td class="text-end text-nowrap">{{ moneyDecimal((string) $invoice->getAttribute('report_due_payment')) }}</td>
                        <td class="text-end text-nowrap">{{ moneyDecimal((string) $invoice->getAttribute('report_paid')) }}</td>
                        <td class="text-end text-nowrap">{{ moneyDecimal((string) $invoice->getAttribute('report_due')) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <th colspan="3" class="text-end">Total :</th>
                    <th class="text-end">{{ moneyDecimal((string) $totalAmount) }}</th>
                    <th class="text-end">{{ moneyDecimal((string) $totalDiscount) }}</th>
                    <th class="text-end">{{ moneyDecimal((string) $totalDuePayment) }}</th>
                    <th class="text-end">{{ moneyDecimal((string) $totalPaid) }}</th>
                    <th class="text-end">{{ moneyDecimal((string) $totalDue) }}</th>
                </tr>
                @else
                 <tr>
                        <td class="text-center text-body-secondary py-4" colspan="8">No patient due records found.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
@endsection
