@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle, 'description' => $account->exists ? 'Update account details and access.' : 'Create an account for this workspace.'])
    <form class="app-card p-3 p-md-4" action="{{ $formAction }}" method="POST">
        @csrf
        @if ($formMethod !== 'POST')
            @method($formMethod)
        @endif
        <section class="app-form-section" aria-labelledby="account-details-heading">
            <h2 class="app-form-section-title" id="account-details-heading">
                <span class="app-form-section-icon">
                    <i class="fa-solid fa-user" aria-hidden="true"></i>
                </span>Account details
            </h2>
            <p class="app-form-section-note">The name and email used to identify this account.</p>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="name">Full name <span class="text-danger" aria-hidden="true">*</span></label>
                    <div class="app-input-wrap">
                        <input class="{{ inputClass('name') }}" id="name" name="name" type="text" value="{{ getValue('name', $account) }}" minlength="5" maxlength="255" autocomplete="name" required autofocus {{ inputAttrs('name') }}>
                    </div>
                    @error('name')
                        <div class="invalid-feedback d-block" id="name-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="email">Email address <span class="text-danger" aria-hidden="true">*</span></label>
                    <div class="app-input-wrap">
                        <input class="{{ inputClass('email') }}" id="email" name="email" type="email" value="{{ getValue('email', $account) }}" minlength="5" maxlength="255" autocomplete="email" required {{ inputAttrs('email') }}>
                    </div>
                    @error('email')
                        <div class="invalid-feedback d-block" id="email-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </section>
        <section class="app-form-section" aria-labelledby="account-security-heading">


            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="password">{{ $account->exists ? 'New password' : 'Password' }} @if (! $account->exists)<span class="text-danger" aria-hidden="true">*</span>@endif</label>
                    <div class="app-input-wrap">
                        <input class="{{ inputClass('password') }} app-password-input" id="password" name="password" type="password" minlength="{{ config('auth.password_min_length', 5) }}" maxlength="4096" autocomplete="new-password" @required(! $account->exists) {{ inputAttrs('password') }}>
                        <button class="app-password-toggle" type="button" data-password-toggle aria-controls="password" aria-label="Show password" aria-pressed="false" title="Show password"><i class="fa-solid fa-eye" aria-hidden="true"></i></button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block" id="password-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="password_confirmation">Confirm password @if (! $account->exists)<span class="text-danger" aria-hidden="true">*</span>@endif</label>
                    <div class="app-input-wrap">
                        <input class="{{ inputClass('password_confirmation') }} app-password-input" id="password_confirmation" name="password_confirmation" type="password" minlength="{{ config('auth.password_min_length', 5) }}" maxlength="4096" autocomplete="new-password" @required(! $account->exists) {{ inputAttrs('password_confirmation') }}>
                        <button class="app-password-toggle" type="button" data-password-toggle aria-controls="password_confirmation" aria-label="Show password" aria-pressed="false" title="Show password"><i class="fa-solid fa-eye" aria-hidden="true"></i></button>
                    </div>
                    @error('password_confirmation')
                        <div class="invalid-feedback d-block" id="password_confirmation-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </section>
        @if ($canAssignRoles)
            <section class="app-form-section" aria-labelledby="account-access-heading">

                <fieldset {{ inputAttrs('roles', null, 'roles-help') }}>

                    <input name="roles_present" type="hidden" value="1">
                    <div class="row g-2">
                        @forelse ($roles as $role)
                            <div class="col-sm-6 col-xl-4">
                                <label class="app-check-option d-flex align-items-start gap-2 h-100" for="roles-{{ $role->id }}">
                                    <input class="{{ inputClass('roles', 'form-check-input') }}" id="roles-{{ $role->id }}" name="roles[]" type="checkbox" value="{{ $role->id }}" {{ getChecked($role->id, 'roles', null, $selectedRoles) }} {{ inputAttrs('roles') }}>
                                    <span class="small text-break">{{ $role->name }}</span>
                                </label>
                            </div>
                        @empty
                            <div class="col-12"><p class="small text-body-secondary border rounded p-3 mb-0">No options are available.</p></div>
                        @endforelse
                    </div>
                    @error('roles')
                        <div class="invalid-feedback d-block" id="roles-error">{{ $message }}</div>
                    @enderror
                </fieldset>
            </section>
        @endif
        @include('core::partials.form-actions', ['model' => $account, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel, 'canSubmit' => true])
    </form>
@endsection
