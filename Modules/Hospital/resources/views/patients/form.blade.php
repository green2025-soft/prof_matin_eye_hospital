@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle, 'description' => 'Register the patient and their contact details at the selected branch.'])

    <form class="app-card p-3 p-md-4" action="{{ $formAction }}" method="POST">
        @csrf
        @if ($formMethod !== 'POST')
            @method($formMethod)
        @endif

        @include('hospital::patients.fields', ['fieldPrefix' => 'patient_'])

        @include('core::partials.form-actions', ['model' => $patient, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel])
    </form>
@endsection
