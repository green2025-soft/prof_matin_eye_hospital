@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle])
    <form class="nav nav-tabs flex-wrap gap-1 mb-2" action="{{ route($routePrefix.'.index') }}" method="GET" aria-label="Settings sections">
        <button class="nav-link{{ $activeGroup === 'company' ? ' active' : '' }}" type="submit" name="group" value="company" aria-pressed="{{ $activeGroup === 'company' ? 'true' : 'false' }}">Company</button>
        <button class="nav-link{{ $activeGroup === 'branding' ? ' active' : '' }}" type="submit" name="group" value="branding" aria-pressed="{{ $activeGroup === 'branding' ? 'true' : 'false' }}">Branding</button>
        <button class="nav-link{{ $activeGroup === 'general' ? ' active' : '' }}" type="submit" name="group" value="general" aria-pressed="{{ $activeGroup === 'general' ? 'true' : 'false' }}">General</button>
        <button class="nav-link{{ $activeGroup === 'email' ? ' active' : '' }}" type="submit" name="group" value="email" aria-pressed="{{ $activeGroup === 'email' ? 'true' : 'false' }}">Email</button>
        @if (! $isPlatform)
            <button class="nav-link{{ $activeGroup === 'hospital' ? ' active' : '' }}" type="submit" name="group" value="hospital" aria-pressed="{{ $activeGroup === 'hospital' ? 'true' : 'false' }}">Hospital</button>
        @endif
        @if ($isPlatform)
            <button class="nav-link{{ $activeGroup === 'billing' ? ' active' : '' }}" type="submit" name="group" value="billing" aria-pressed="{{ $activeGroup === 'billing' ? 'true' : 'false' }}">Billing</button>
            <button class="nav-link{{ $activeGroup === 'payments' ? ' active' : '' }}" type="submit" name="group" value="payments" aria-pressed="{{ $activeGroup === 'payments' ? 'true' : 'false' }}">Payments</button>
            <button class="nav-link{{ $activeGroup === 'sms' ? ' active' : '' }}" type="submit" name="group" value="sms" aria-pressed="{{ $activeGroup === 'sms' ? 'true' : 'false' }}">SMS</button>
        @endif
    </form>

    @if ($activeGroup === 'hospital')
        <form class="d-flex flex-wrap gap-2 mb-3" action="{{ route($routePrefix.'.index') }}" method="GET" aria-label="Hospital settings sections">
            <input name="group" type="hidden" value="hospital">
            <button class="btn btn-sm {{ $activeHospitalSection === 'patient' ? 'btn-primary' : 'btn-outline-secondary' }}" type="submit" name="section" value="patient" aria-pressed="{{ $activeHospitalSection === 'patient' ? 'true' : 'false' }}">Patient</button>
            <button class="btn btn-sm {{ $activeHospitalSection === 'doctor' ? 'btn-primary' : 'btn-outline-secondary' }}" type="submit" name="section" value="doctor" aria-pressed="{{ $activeHospitalSection === 'doctor' ? 'true' : 'false' }}">Doctor</button>
            <button class="btn btn-sm {{ $activeHospitalSection === 'reference' ? 'btn-primary' : 'btn-outline-secondary' }}" type="submit" name="section" value="reference" aria-pressed="{{ $activeHospitalSection === 'reference' ? 'true' : 'false' }}">Reference</button>
            <button class="btn btn-sm {{ $activeHospitalSection === 'appointment' ? 'btn-primary' : 'btn-outline-secondary' }}" type="submit" name="section" value="appointment" aria-pressed="{{ $activeHospitalSection === 'appointment' ? 'true' : 'false' }}">Appointment</button>
            <button class="btn btn-sm {{ $activeHospitalSection === 'consultation' ? 'btn-primary' : 'btn-outline-secondary' }}" type="submit" name="section" value="consultation" aria-pressed="{{ $activeHospitalSection === 'consultation' ? 'true' : 'false' }}">Consultation</button>
            <button class="btn btn-sm {{ $activeHospitalSection === 'investigation' ? 'btn-primary' : 'btn-outline-secondary' }}" type="submit" name="section" value="investigation" aria-pressed="{{ $activeHospitalSection === 'investigation' ? 'true' : 'false' }}">Investigation</button>
            <button class="btn btn-sm {{ $activeHospitalSection === 'operation' ? 'btn-primary' : 'btn-outline-secondary' }}" type="submit" name="section" value="operation" aria-pressed="{{ $activeHospitalSection === 'operation' ? 'true' : 'false' }}">Operation</button>
            <button class="btn btn-sm {{ $activeHospitalSection === 'refund' ? 'btn-primary' : 'btn-outline-secondary' }}" type="submit" name="section" value="refund" aria-pressed="{{ $activeHospitalSection === 'refund' ? 'true' : 'false' }}">Refund</button>
            <button class="btn btn-sm {{ $activeHospitalSection === 'due_collection' ? 'btn-primary' : 'btn-outline-secondary' }}" type="submit" name="section" value="due_collection" aria-pressed="{{ $activeHospitalSection === 'due_collection' ? 'true' : 'false' }}">Due Collection</button>
        </form>
    @endif

    @if ($activeGroup === 'payments')
        <form
            class="d-flex flex-wrap gap-2 mb-4"
            action="{{ route($routePrefix.'.index') }}"
            method="GET"
            aria-label="Payment providers"
        >
            <input name="group" type="hidden" value="payments">
            <button class="btn {{ $activeProvider === 'manual' ? 'btn-primary' : 'btn-outline-secondary' }}" type="submit" name="provider" value="manual" aria-pressed="{{ $activeProvider === 'manual' ? 'true' : 'false' }}">Manual</button>
            <button class="btn {{ $activeProvider === 'sslcommerz' ? 'btn-primary' : 'btn-outline-secondary' }}" type="submit" name="provider" value="sslcommerz" aria-pressed="{{ $activeProvider === 'sslcommerz' ? 'true' : 'false' }}">SSLCommerz</button>
            <button class="btn {{ $activeProvider === 'bkash' ? 'btn-primary' : 'btn-outline-secondary' }}" type="submit" name="provider" value="bkash" aria-pressed="{{ $activeProvider === 'bkash' ? 'true' : 'false' }}">bKash</button>
            <button class="btn {{ $activeProvider === 'nagad' ? 'btn-primary' : 'btn-outline-secondary' }}" type="submit" name="provider" value="nagad" aria-pressed="{{ $activeProvider === 'nagad' ? 'true' : 'false' }}">Nagad</button>
            <button class="btn {{ $activeProvider === 'eps' ? 'btn-primary' : 'btn-outline-secondary' }}" type="submit" name="provider" value="eps" aria-pressed="{{ $activeProvider === 'eps' ? 'true' : 'false' }}">EPS</button>
        </form>
    @endif

    @include('core::settings.form')
@endsection
