@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    <div class="app-print-hidden">
        @include('core::partials.heading', ['title' => $pageTitle, 'description' => 'Saved invoice and payment details.'])
        <div class="d-flex flex-wrap gap-2 mb-3">
            <a class="btn btn-outline-secondary btn-sm" href="{{ $backUrl }}"><i class="fa-solid fa-arrow-left me-2" aria-hidden="true"></i>Back</a>
            <button class="btn btn-primary btn-sm" type="button" data-print><i class="fa-solid fa-print me-2" aria-hidden="true"></i>Print</button>
        </div>
    </div>

    <article class="app-card app-invoice-document app-clinical-invoice" aria-labelledby="clinical-invoice-title">
        <header class="app-clinical-invoice-header">
            <div class="app-clinical-invoice-brand">
                @if ($logoUrl)
                    <img class="app-clinical-invoice-logo" src="{{ $logoUrl }}" alt="" width="76" height="76">
                @endif
                <div>
                    <h2 class="app-clinical-invoice-company">{{ $company['name'] }}</h2>
                    @if ($company['address'] ?? '')
                        <p class="app-clinical-invoice-address">{{ $company['address'] }}</p>
                    @endif
                    <div class="app-clinical-invoice-contact">
                        @if ($company['phone'] ?? '')<span>Phone: {{ $company['phone'] }}</span>@endif
                        @if ($company['email'] ?? '')<span>{{ $company['email'] }}</span>@endif
                        @if ($company['tax_id'] ?? '')<span>Tax ID: {{ $company['tax_id'] }}</span>@endif
                    </div>
                </div>
            </div>
            <div class="app-clinical-invoice-title-row">
                <div>
                    <h1 id="clinical-invoice-title">{{ $documentTitle }}</h1>
                    <p class="mb-0 fw-semibold">{{ $invoiceNumber }}</p>
                    @if ($originalInvoiceNumber !== null)
                        <p class="small mb-0">Original invoice: {{ $originalInvoiceNumber }}</p>
                    @endif
                </div>
                <span class="app-clinical-invoice-status">{{ $status }}</span>
            </div>
        </header>

        <dl class="app-clinical-invoice-meta">
            <div><dt>Patient ID</dt><dd>{{ $patient?->patient_id ?? '-' }}</dd></div>
            <div><dt>Patient name</dt><dd>{{ $patient?->name ?? '-' }}</dd></div>
            <div><dt>Mobile no.</dt><dd>{{ $patient?->mobile_no ?? '-' }}</dd></div>
            <div><dt>Age / Gender</dt><dd>{{ $patient?->age ?? '-' }} / {{ $patientGenderLabel }}</dd></div>
            <div><dt>Doctor</dt><dd>{{ $doctor?->name ?? '-' }}</dd></div>
            <div><dt>{{ $entryDateLabel }}</dt><dd>{{ $entryDate }} @if ($entryTime !== '')<span class="text-nowrap">{{ $entryTime }}</span>@endif</dd></div>
        </dl>

        @yield('invoice-content')

        <footer class="app-clinical-invoice-footer">
            <div class="small text-body-secondary">
                <p class="mb-1">Printed by: {{ $printedBy }}</p>
                <p class="mb-0">Printed at: {{ $printedAt }}</p>
            </div>
            <div class="app-clinical-invoice-signature"><span>Signature</span></div>
        </footer>
    </article>
@endsection
