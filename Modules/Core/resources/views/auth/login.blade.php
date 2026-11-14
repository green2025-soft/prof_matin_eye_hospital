@extends('core::layouts.app')

@section('title', 'Sign in')

@section('content')
    <div class="app-auth-heading mb-4 text-center">
        <h2>Welcome</h2>
        {{-- <p class="text-body-secondary mb-0">Sign in to continue.</p> --}}
    </div>
    <form action="{{ route('auth.store') }}" method="POST">
        @csrf
        @if ($tenantLogin && count($accountTypes) > 1)
            <div class="mb-3">
                <label class="form-label" for="account_type">Sign in as</label>
                <select class="{{ inputClass('account_type', 'form-select') }}" id="account_type" name="account_type" required
                    {{ inputAttrs('account_type') }}>
                    @foreach ($accountTypes as $type => $details)
                        <option value="{{ $type }}" {{ getSelected($type, 'account_type', null, $accountType) }}>{{ $details['label'] }}</option>
                    @endforeach
                </select>
                @error('account_type')
                    <div class="invalid-feedback d-block" id="account_type-error">{{ $message }}</div>
                @enderror
            </div>
        @endif
        <div class="mb-3">
            <label class="form-label" for="email">Email address <span class="text-danger" aria-hidden="true">*</span></label>
            <input class="{{ inputClass('email') }}" id="email" name="email" type="email"
                value="{{ getValue('email') }}" maxlength="255" autocomplete="username"
                placeholder="you@example.com" required autofocus {{ inputAttrs('email') }}>
            @error('email')
                <div class="invalid-feedback d-block" id="email-error">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label" for="password">Password <span class="text-danger" aria-hidden="true">*</span></label>
            <div class="app-input-wrap">
                <input class="{{ inputClass('password') }} app-password-input" id="password" name="password" type="password"
                    minlength="{{ config('auth.password_min_length') }}" maxlength="4096" autocomplete="current-password"
                    placeholder="Enter your password" required {{ inputAttrs('password') }}>
                <button class="app-password-toggle" type="button" data-password-toggle aria-controls="password"
                    aria-label="Show password" aria-pressed="false" title="Show password">
                    <i class="fa-solid fa-eye" aria-hidden="true"></i>
                </button>
            </div>
            @error('password')
                <div class="invalid-feedback d-block" id="password-error">{{ $message }}</div>
            @enderror
        </div>
        <div class="app-auth-options d-flex flex-wrap align-items-center justify-content-between row-gap-1 column-gap-3 mb-3">
            <div class="form-check d-flex align-items-center gap-2 m-0 p-0">
                <input class="form-check-input float-none flex-shrink-0 m-0" id="remember" name="remember" type="checkbox" value="1" {{ getChecked(1, 'remember', null, 0) }}>
                <label class="form-check-label d-inline-flex align-items-center" for="remember">Keep me signed in</label>
            </div>
            <a class="small d-inline-flex align-items-center" href="{{ route('auth.password.request', $tenantLogin ? ['account_type' => getValue('account_type', null, $accountType)] : []) }}">Forgot password?</a>
        </div>
        <button class="btn btn-primary w-100" type="submit">
            Sign in<i class="fa-solid fa-arrow-right ms-2" aria-hidden="true"></i>
        </button>
    </form>
@endsection
