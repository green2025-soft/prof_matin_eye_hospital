@extends('core::layouts.app')

@section('title', $documentTitle.' '.$referenceNumber)

@section('content')
    <div class="d-print-none">
        {{-- @include('core::partials.heading', ['title' => $documentTitle.' '.$referenceNumber]) --}}
        <div class="mb-3 text-end">
            <button class="btn btn-primary btn-sm" type="button" data-print
                data-print-config="{{ htmlJson($printConfiguration) }}">
                <i class="fa-solid fa-print me-2" aria-hidden="true"></i>Print
            </button>
            @if ($canEdit)
                <a class="btn btn-warning btn-sm" href="{{ $editUrl }}"><i class="fa-solid fa-pen-to-square me-2" aria-hidden="true"></i>Edit</a>
            @endif
            @yield('prescription-actions')
            <a class="btn btn-success btn-sm" href="{{ $backUrl }}"><i class="fa-solid fa-arrow-left me-2" aria-hidden="true"></i>Back</a>
        </div>
    </div>

    <article @class([
        'app-card app-invoice-document app-prescription-document',
        'app-prescription-paper-a5' => $paperSize === 'a5',
        'app-prescription-paper-custom' => $paperSize === 'custom',
    ])>
        @if ($showHeader)
            <header class="app-prescription-letterhead">
                @if ($logoUrl)
                    <img class="app-prescription-logo" src="{{ $logoUrl }}" alt="">
                @endif
                <div>
                    <h2>{{ $company['name'] }}</h2>
                    <p>{{ $company['address'] }}</p>
                    <p>{{ $company['phone'] }} {{ $company['email'] }}</p>
                </div>
            </header>
        @endif
        @if ($showHeader)
            <div class="app-prescription-doctor">
                <div>
                    <h2>{{ $doctor?->name }}</h2>
                    <p class="app-prescription-text">{{ $doctor?->education_degree }}</p>
                    <p>{{ $doctor?->designation }}</p>
                    <p>{{ $doctor?->specialist }}</p>
                    <p class="app-prescription-text">{{ $doctor?->current_employment }}</p>
                    @if (filled($doctor?->bmdc_reg))<p>BMDC: {{ $doctor->bmdc_reg }}</p>@endif
                </div>
                <div class="app-prescription-doctor-bangla" lang="bn">
                    <h2>{{ $doctor?->bn_name }}</h2>
                    <p class="app-prescription-text">{{ $doctor?->bn_education_degree }}</p>
                    <p>{{ $doctor?->bn_specialist }}</p>
                    <p class="app-prescription-text">{{ $doctor?->bn_current_employment }}</p>
                    @if (filled($doctor?->bn_bmdc_reg))<p>বিএমডিসি: {{ $doctor->bn_bmdc_reg }}</p>@endif
                </div>
            </div>
        @endif

        <div class="app-prescription-identity">
            <div class="app-prescription-patient-barcode">
                @if (filled($patient?->patient_id))
                    <svg class="app-prescription-barcode" data-patient-id="{{ $patient->patient_id }}"
                        role="img" aria-label="Patient ID barcode {{ $patient->patient_id }}"></svg>
                @endif
            </div>
            <div class="app-prescription-reference">
                <p><b>V.Date:</b> {{ $entryDate }}</p>
                <p><i class="fa-solid fa-prescription" aria-hidden="true"></i> {{ $referenceNumber }}</p>
            </div>
        </div>

        {{-- <dl class="app-prescription-patient"> --}}
        <div class="app-prescription-patien">
            <div class="row gx-0">
                <div class="col-6">
                    <div><b>P.Name:</b> {{ $patient?->name }}</div>
                    <div><b>Age:</b> {{ $patientAge }} <b> Sex:</b>{{ ucfirst($patient?->gender ?? '') }}</div>
                </div>
                <div class="col-6 text-end">
                    <div><b>Patient ID:</b> {{ $patient?->patient_id }}</div>
                    <div><b>Mobile:</b> {{ $patient?->mobile_no }} </div>
                </div>
            </div>
        </div>

        @yield('prescription-content')

        <footer class="app-prescription-footer">
            <div>
                {{-- <p class="app-prescription-text">{{ $doctor?->pad_footer_text }}</p>
                <p class="small mb-0">{{ $printedBy }} · {{ $printedAt }}</p> --}}
            </div>
            {{-- <div class="app-prescription-signature"><span>Doctor's signature</span></div> --}}
        </footer>
    </article>
@endsection
