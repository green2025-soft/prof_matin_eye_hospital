@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    <span class="app-auth-form-icon"><i class="fa-solid fa-key" aria-hidden="true"></i></span>
    <h2 class="h3 fw-semibold mb-2">{{ $pageTitle }}</h2>
    <p class="text-body-secondary mb-4">{{ $description }}</p>

    <form action="{{ $formAction }}" method="POST">
        @csrf
        @if ($token !== null)
            <input type="hidden" name="token" value="{{ $token }}">
        @endif
        <div class="mb-3">
            <label class="form-label" for="email">Email address</label>
            <input class="{{ inputClass('email') }}" id="email" name="email" type="email" value="{{ getValue('email', null, $email) }}" minlength="5" maxlength="255" autocomplete="email" required autofocus {{ inputAttrs('email') }}>
            @error('email')
                <div class="invalid-feedback" id="email-error">{{ $message }}</div>
            @enderror
        </div>
        @if ($token !== null)
            <div class="mb-3">
                <label class="form-label" for="password">New password</label>
                <div class="app-input-wrap">
                    <input class="{{ inputClass('password') }} app-password-input" id="password" name="password" type="password" minlength="{{ $passwordMinimum }}" maxlength="4096" autocomplete="new-password" required {{ inputAttrs('password', null, 'password-help') }}>
                    <button class="app-password-toggle" type="button" data-password-toggle aria-controls="password" aria-label="Show password" aria-pressed="false"><i class="fa-solid fa-eye" aria-hidden="true"></i></button>
                </div>
                <p class="form-text" id="password-help">Use at least {{ $passwordMinimum }} characters.</p>
                @error('password')
                    <div class="invalid-feedback d-block" id="password-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4">
                <label class="form-label" for="password_confirmation">Confirm password</label>
                <div class="app-input-wrap">
                    <input class="{{ inputClass('password_confirmation') }} app-password-input" id="password_confirmation" name="password_confirmation" type="password" minlength="{{ $passwordMinimum }}" maxlength="4096" autocomplete="new-password" required {{ inputAttrs('password_confirmation') }}>
                    <button class="app-password-toggle" type="button" data-password-toggle aria-controls="password_confirmation" aria-label="Show password" aria-pressed="false"><i class="fa-solid fa-eye" aria-hidden="true"></i></button>
                </div>
                @error('password_confirmation')
                    <div class="invalid-feedback d-block" id="password_confirmation-error">{{ $message }}</div>
                @enderror
            </div>
        @endif
        <button class="btn btn-primary w-100" type="submit">{{ $submitLabel }}</button>
        <p class="text-center small mt-4 mb-0"><a href="{{ route('auth.login') }}">Back to sign in</a></p>
    </form>
@endsection
