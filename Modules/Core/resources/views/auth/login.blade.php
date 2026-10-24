@extends('core::layouts.app')

@section('title', 'Sign in')

@section('content')
    <span class="app-auth-form-icon"><i class="fa-solid fa-arrow-right-to-bracket" aria-hidden="true"></i></span>
    <p class="app-eyebrow mb-2">Welcome back</p>
    <h2 class="h3 fw-semibold mb-2">Sign in to your workspace</h2>
    <p class="text-body-secondary mb-4">Enter your account details to continue.</p>
    <form action="{{ route('auth.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label" for="email">Email address <span class="text-danger" aria-hidden="true">*</span></label>
            <input class="{{ inputClass('email') }}" id="email" name="email" type="email" value="{{ getValue('email') }}" autocomplete="username" placeholder="you@example.com" required autofocus {{ inputAttrs('email') }}>
            @error('email')
                <div class="invalid-feedback d-block" id="email-error">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label" for="password">Password <span class="text-danger" aria-hidden="true">*</span></label>
            <div class="app-input-wrap">
                <input class="{{ inputClass('password') }} app-password-input" id="password" name="password" type="password" autocomplete="current-password" placeholder="Enter your password" required {{ inputAttrs('password') }}>
                <button class="app-password-toggle" type="button" data-password-toggle aria-controls="password" aria-label="Show password" aria-pressed="false" title="Show password"><i class="fa-solid fa-eye" aria-hidden="true"></i></button>
            </div>
            @error('password')
                <div class="invalid-feedback d-block" id="password-error">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-check my-4">
            <input class="form-check-input" id="remember" name="remember" type="checkbox" value="1" {{ getChecked(1, 'remember', null, 0) }}>
            <label class="form-check-label" for="remember">Keep me signed in</label>
        </div>
        <button class="btn btn-primary w-100" type="submit">Sign in<i class="fa-solid fa-arrow-right ms-2" aria-hidden="true"></i></button>
        <p class="small text-body-secondary text-center mt-4 mb-0"><i class="fa-solid fa-lock me-1" aria-hidden="true"></i>Access is limited to authorized accounts.</p>
    </form>
@endsection
