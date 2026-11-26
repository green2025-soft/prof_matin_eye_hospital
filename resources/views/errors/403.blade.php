@extends('core::layouts.app')

@section('title', '403 - Access denied')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <section class="app-card p-4 p-md-5 text-center">
                <p class="display-5 fw-bold text-danger mb-2">403</p>
                <h1 class="h3 mb-2">Access denied</h1>
                <p class="text-body-secondary mb-4">
                    You do not have permission to access this page.
                </p>

                @if (auth()->guard('web')->check())
                    <form action="{{ route('auth.logout') }}" method="POST">
                        @csrf
                        <button class="btn btn-danger" type="submit">
                            <i class="fa-solid fa-arrow-right-from-bracket me-2" aria-hidden="true"></i>Sign out
                        </button>
                    </form>
                @else
                    <a class="btn btn-primary" href="{{ route('auth.login') }}">Sign in</a>
                @endif
            </section>
        </div>
    </div>
@endsection
