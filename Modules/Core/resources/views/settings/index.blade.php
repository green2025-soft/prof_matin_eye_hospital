@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle, 'description' => 'Manage company details, workspace preferences, and available payment settings.'])
    <form class="nav nav-tabs flex-wrap gap-1 mb-4" action="{{ route($routePrefix.'.index') }}" method="GET" aria-label="Settings sections">
        <button class="nav-link{{ $activeGroup === 'company' ? ' active' : '' }}" type="submit" name="group" value="company" aria-pressed="{{ $activeGroup === 'company' ? 'true' : 'false' }}">Company</button>
        <button class="nav-link{{ $activeGroup === 'branding' ? ' active' : '' }}" type="submit" name="group" value="branding" aria-pressed="{{ $activeGroup === 'branding' ? 'true' : 'false' }}">Branding</button>
        <button class="nav-link{{ $activeGroup === 'general' ? ' active' : '' }}" type="submit" name="group" value="general" aria-pressed="{{ $activeGroup === 'general' ? 'true' : 'false' }}">General</button>
        @if ($isPlatform)
            <button class="nav-link{{ $activeGroup === 'billing' ? ' active' : '' }}" type="submit" name="group" value="billing" aria-pressed="{{ $activeGroup === 'billing' ? 'true' : 'false' }}">Billing</button>
            <button class="nav-link{{ $activeGroup === 'payments' ? ' active' : '' }}" type="submit" name="group" value="payments" aria-pressed="{{ $activeGroup === 'payments' ? 'true' : 'false' }}">Payments</button>
        @endif
    </form>

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
