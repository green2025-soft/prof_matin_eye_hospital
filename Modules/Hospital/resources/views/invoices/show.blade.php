@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    <div class="app-print-hidden">
        {{-- @include('core::partials.heading', ['title' => $pageTitle, 'description' => 'Saved invoice and payment details.']) --}}
        <div class="d-flex flex-wrap justify-content-end gap-2 mb-3">
            <a class="btn btn-outline-secondary btn-sm" href="{{ $backUrl }}"><i class="fa-solid fa-arrow-left me-2" aria-hidden="true"></i>Back</a>
            <button class="btn btn-primary btn-sm" type="button" data-print><i class="fa-solid fa-print me-2" aria-hidden="true"></i>Print</button>
        </div>
    </div>

    <article class="app-card app-invoice-document app-clinical-invoice" aria-labelledby="clinical-invoice-title">
        <header class="app-clinical-invoice-header">
            <div class="app-clinical-invoice-brand">
                @if ($logoUrl)
                    <img class="app-clinical-invoice-logo" src="{{ $logoUrl }}" alt="" width="110" height="110">
                @endif
                <div class="app-clinical-invoice-company-details">
                    <h2 class="app-clinical-invoice-company">{{ $company['name'] }}</h2>
                    @if ($company['address'] ?? '')
                        <p class="app-clinical-invoice-address invoice-body-font">{{ $company['address'] }}</p>
                    @endif
                    @if ($company['phone'] ?? '')
                    <div class="app-clinical-invoice-contact-line invoice-body-font">
                        <strong>HOTLINE : </strong>{{ $company['phone'] }}
                    </div>
                    @endif
                    @if ($company['email'] ?? '')
                    <div class="app-clinical-invoice-contact-line invoice-body-font">
                        <strong>E-MAIL : </strong>{{ $company['email'] }}
                    </div>
                    @endif
                    @if ($company['tax_id'] ?? '')
                    <div class="app-clinical-invoice-contact-line invoice-body-font">
                        <strong>Tax ID : </strong>{{ $company['tax_id'] }}
                    </div>
                    @endif
                </div>
                <div class="app-clinical-invoice-document-mark" aria-label="Invoice number {{ $invoiceNumber }}">
                    <canvas class="app-clinical-invoice-qr"
                        data-qr-value="{{ $company['name'] }}&#10;{{ $invoiceNumber }}&#10;{{ $patient?->name ?? '' }}&#10;{{ $patient?->patient_id ?? '' }}&#10;{{ $patient?->mobile_no ?? '' }}"
                        role="img"
                        aria-label="Invoice QR code for {{ $invoiceNumber }}">
                    </canvas>
                </div>
            </div>
            <div class="app-clinical-invoice-title-row text-center">
                <u>{{ $documentTitle }}</u>
            </div>
        </header>
        <div class="invoice-patient-box invoice-body-font">
            <div class="info-row">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td style="width: 105px;">
                                <div class="info-label">Invoice No.</div>
                            </td>
                            <td>
                                <div class="info-value">:<b> {{ $invoiceNumber }}</b></div>
                            </td>
                            <td style="width: 75px;">
                                <div class="info-label">Date</div>
                            </td>
                            <td>
                                <div class="info-value">: {{ $entryDate }}</div>
                            </td>
                            <td style="width: 70px;">
                                <div class="info-label">Time</div>
                            </td>
                            <td>
                                <div class="info-value">: {{ $entryTime }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="info-label">P.Name</div>
                            </td>
                            <td colspan="3">
                                <div class="info-value">: <b>{{ $patient?->name ?? '-' }}</b></div>
                            </td>

                            <td>
                                <div class="info-label">Age</div>
                            </td>
                            <td>
                                <div class="info-value">: {{ $patient?->age ?? '-' }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td><div class="info-label">Mobile</div></td>
                            <td><div class="info-value">: {{ $patient?->mobile_no ?? '-' }}</div></td>
                            <td><div class="info-label">P.ID</div></td>
                            <td><div class="info-value">: {{ $patient?->patient_id ?? '-' }}</div></td>
                            <td><div class="info-label">Gender</div></td>
                            <td><div class="info-value">: {{ $patientGenderLabel }}</div></td>
                        </tr>
                        @if(!isset($isDoctor))
                        <tr>
                            <td>
                                <div class="info-label">Doctor</div>
                            </td>
                            <td colspan="5">
                                <div class="info-value">:  {{ $doctor?->name ?? '-' }}</div>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>



@yield('invoice-content')
        <footer class="app-clinical-invoice-footer">
            <div class="app-clinical-invoice-signature"><span>Authorized signature</span></div>
            <div class="app-clinical-invoice-printed">Printed on: {{ $printedAt }}</div>
            <div class="app-clinical-invoice-signature"><span>Prepared by: {{ $printedBy }}</span></div>
        </footer>
    </article>
@endsection
