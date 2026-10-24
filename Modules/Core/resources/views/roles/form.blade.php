@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle, 'description' => 'Create or update a role name. Assign its access from Permissions.'])
    <form class="app-card p-3 p-md-4" action="{{ $formAction }}" method="POST">
        @csrf
        @if ($formMethod !== 'POST')
            @method($formMethod)
        @endif
        <section class="app-form-section" aria-labelledby="role-details-heading">
            <h2 class="app-form-section-title" id="role-details-heading"><span class="app-form-section-icon"><i class="fa-solid fa-shield-halved" aria-hidden="true"></i></span>Role details</h2>
            <p class="app-form-section-note">{{ $isReservedRole ? 'This administrator role is protected and cannot be changed.' : 'Use a name that describes the responsibilities of this role.' }}</p>
            <div class="row">
                <div class="col-lg-7">
                    <label class="form-label" for="name">Role name <span class="text-danger" aria-hidden="true">*</span></label>
                    <div class="app-input-wrap">
                        <input class="{{ inputClass('name') }}" id="name" name="name" type="text" value="{{ getValue('name', $role) }}" maxlength="255" autocomplete="off" required autofocus @readonly($isReservedRole) {{ inputAttrs('name') }}>
                    </div>
                    @error('name')
                        <div class="invalid-feedback d-block" id="name-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </section>
        @include('core::partials.form-actions', ['model' => $role, 'routePrefix' => 'core.roles', 'submitLabel' => $submitLabel, 'canSubmit' => ! $isReservedRole])
    </form>
@endsection
