@extends('core::layouts.app')

@section('title', $documentTitle.' '.$referenceNumber)

@section('content')
    <div class="d-print-none">
        @include('core::partials.heading', ['title' => $documentTitle.' '.$referenceNumber])
        <div class="d-flex flex-wrap gap-2 mb-3">
            <a class="btn btn-outline-secondary btn-sm" href="{{ $backUrl }}"><i class="fa-solid fa-arrow-left me-2" aria-hidden="true"></i>Back</a>
            <button class="btn btn-primary btn-sm" type="button" data-print><i class="fa-solid fa-print me-2" aria-hidden="true"></i>Print</button>
            @if ($canEdit)
                <a class="btn btn-outline-secondary btn-sm" href="{{ $editUrl }}"><i class="fa-solid fa-pen-to-square me-2" aria-hidden="true"></i>Edit</a>
            @endif
            @yield('prescription-actions')
        </div>
    </div>

    <article @class(['app-card app-invoice-document app-prescription-document', 'app-prescription-paper-a5' => $paperSize === 'a5']) aria-labelledby="prescription-print-title">
        @if ($showHeader)
            <header class="app-prescription-letterhead">
                @if ($logoUrl)
                    <img class="app-prescription-logo" src="{{ $logoUrl }}" width="76" height="76" alt="">
                @endif
                <div>
                    <h2>{{ $company['name'] }}</h2>
                    <p>{{ $company['address'] ?? '' }}</p>
                    <p>{{ $company['phone'] ?? '' }} {{ $company['email'] ?? '' }}</p>
                </div>
            </header>
        @endif

        <div class="app-prescription-doctor">
            <div>
                <h2>{{ $doctor?->name }}</h2>
                <p>{{ $doctor?->education_degree }}</p>
                <p>{{ $doctor?->designation }}</p>
                <p>{{ $doctor?->specialist }}</p>
                @if (filled($doctor?->bmdc_reg))<p>BMDC: {{ $doctor->bmdc_reg }}</p>@endif
            </div>
            <div class="app-prescription-reference">
                <h1 id="prescription-print-title">{{ $documentTitle }}</h1>
                <p>{{ $referenceNumber }}</p>
                <p>Date: {{ $entryDate }}</p>
            </div>
        </div>

        <dl class="app-prescription-patient">
            <div><dt>Patient</dt><dd>{{ $patient?->name }}</dd></div>
            <div><dt>Patient ID</dt><dd>{{ $patient?->patient_id }}</dd></div>
            <div><dt>Age / Gender</dt><dd>{{ $patient?->age ?? '--' }} / {{ ucfirst($patient?->gender ?? '') }}</dd></div>
            <div><dt>Mobile</dt><dd>{{ $patient?->mobile_no }}</dd></div>
        </dl>

        @yield('prescription-content')

        <footer class="app-prescription-footer">
            <div>
                <p class="app-prescription-text">{{ $doctor?->pad_footer_text }}</p>
                <p class="small mb-0">Printed by: {{ $printedBy }} / {{ $printedAt }}</p>
            </div>
            <div class="app-prescription-signature"><span>Doctor's signature</span></div>
        </footer>
    </article>
@endsection
