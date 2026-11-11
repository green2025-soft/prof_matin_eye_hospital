@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle])

    <form class="d-flex flex-wrap gap-2 mb-3" action="{{ route('pharmacy.settings.index') }}" method="GET" aria-label="Pharmacy settings sections">
        <button class="btn btn-sm {{ $activePharmacySection === 'general' ? 'btn-primary' : 'btn-outline-secondary' }}" type="submit" name="section" value="general" aria-pressed="{{ $activePharmacySection === 'general' ? 'true' : 'false' }}">General</button>
        <button class="btn btn-sm {{ $activePharmacySection === 'numbering' ? 'btn-primary' : 'btn-outline-secondary' }}" type="submit" name="section" value="numbering" aria-pressed="{{ $activePharmacySection === 'numbering' ? 'true' : 'false' }}">Numbering</button>
        <button class="btn btn-sm {{ $activePharmacySection === 'items' ? 'btn-primary' : 'btn-outline-secondary' }}" type="submit" name="section" value="items" aria-pressed="{{ $activePharmacySection === 'items' ? 'true' : 'false' }}">Item fields</button>
    </form>

    @include('pharmacy::settings.form')
@endsection
