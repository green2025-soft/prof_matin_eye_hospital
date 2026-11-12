@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle])
    <form class="d-flex flex-wrap gap-2 mb-3" action="{{ route($routePrefix.'.index') }}" method="GET" aria-label="Hospital settings sections">
        <button class="btn btn-sm {{ $activeHospitalSection === 'patient' ? 'btn-primary' : 'btn-outline-secondary' }}" type="submit" name="section" value="patient" aria-pressed="{{ $activeHospitalSection === 'patient' ? 'true' : 'false' }}">Patient</button>
        <button class="btn btn-sm {{ $activeHospitalSection === 'doctor' ? 'btn-primary' : 'btn-outline-secondary' }}" type="submit" name="section" value="doctor" aria-pressed="{{ $activeHospitalSection === 'doctor' ? 'true' : 'false' }}">Doctor</button>
        <button class="btn btn-sm {{ $activeHospitalSection === 'reference' ? 'btn-primary' : 'btn-outline-secondary' }}" type="submit" name="section" value="reference" aria-pressed="{{ $activeHospitalSection === 'reference' ? 'true' : 'false' }}">Reference</button>
        <button class="btn btn-sm {{ $activeHospitalSection === 'appointment' ? 'btn-primary' : 'btn-outline-secondary' }}" type="submit" name="section" value="appointment" aria-pressed="{{ $activeHospitalSection === 'appointment' ? 'true' : 'false' }}">Appointment</button>
        <button class="btn btn-sm {{ $activeHospitalSection === 'consultation' ? 'btn-primary' : 'btn-outline-secondary' }}" type="submit" name="section" value="consultation" aria-pressed="{{ $activeHospitalSection === 'consultation' ? 'true' : 'false' }}">Consultation</button>
        <button class="btn btn-sm {{ $activeHospitalSection === 'investigation' ? 'btn-primary' : 'btn-outline-secondary' }}" type="submit" name="section" value="investigation" aria-pressed="{{ $activeHospitalSection === 'investigation' ? 'true' : 'false' }}">Investigation</button>
        <button class="btn btn-sm {{ $activeHospitalSection === 'operation' ? 'btn-primary' : 'btn-outline-secondary' }}" type="submit" name="section" value="operation" aria-pressed="{{ $activeHospitalSection === 'operation' ? 'true' : 'false' }}">Operation</button>
        <button class="btn btn-sm {{ $activeHospitalSection === 'refund' ? 'btn-primary' : 'btn-outline-secondary' }}" type="submit" name="section" value="refund" aria-pressed="{{ $activeHospitalSection === 'refund' ? 'true' : 'false' }}">Refund</button>
        <button class="btn btn-sm {{ $activeHospitalSection === 'due_collection' ? 'btn-primary' : 'btn-outline-secondary' }}" type="submit" name="section" value="due_collection" aria-pressed="{{ $activeHospitalSection === 'due_collection' ? 'true' : 'false' }}">Due Collection</button>
        @if ($smsNotifications !== [])
            <button class="btn btn-sm {{ $activeHospitalSection === 'sms' ? 'btn-primary' : 'btn-outline-secondary' }}" type="submit" name="section" value="sms" aria-pressed="{{ $activeHospitalSection === 'sms' ? 'true' : 'false' }}">SMS</button>
        @endif
    </form>

    @include('hospital::settings.form')
@endsection
