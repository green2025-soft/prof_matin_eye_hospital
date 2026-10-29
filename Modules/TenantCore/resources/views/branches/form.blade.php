@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle, 'description' => 'Set the branch location and its contact details.'])
    <form class="app-card p-3 p-md-4" action="{{ $formAction }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if ($formMethod !== 'POST')
            @method($formMethod)
        @endif
        <section class="app-form-section" aria-labelledby="branch-details-heading">
            <h2 class="app-form-section-title" id="branch-details-heading">
                <span class="app-form-section-icon"><i class="fa-solid fa-building" aria-hidden="true"></i></span>Branch details
            </h2>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="branch-name">Branch name <span class="text-danger" aria-hidden="true">*</span></label>
                    <input
                        class="{{ inputClass('name') }}"
                        id="branch-name"
                        name="name"
                        type="text"
                        value="{{ getValue('name', $branch) }}"
                        maxlength="255"
                        autocomplete="organization"
                        required
                        autofocus
                        {{ inputAttrs('name', 'branch-name') }}
                    >
                    @error('name')
                        <div class="invalid-feedback d-block" id="branch-name-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <label class="form-label" for="branch-address">Address <span class="text-danger" aria-hidden="true">*</span></label>
                    <textarea
                        class="{{ inputClass('address') }}"
                        id="branch-address"
                        name="address"
                        rows="3"
                        maxlength="2000"
                        autocomplete="street-address"
                        required
                        {{ inputAttrs('address', 'branch-address') }}
                    >{{ getValue('address', $branch) }}</textarea>
                    @error('address')
                        <div class="invalid-feedback d-block" id="branch-address-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </section>
        <section class="app-form-section" aria-labelledby="branch-contact-heading">
            <h2 class="app-form-section-title" id="branch-contact-heading">Contact details</h2>
            <p class="app-form-section-note">These contact fields are optional.</p>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="branch-mobile">Mobile number</label>
                    <input
                        class="{{ inputClass('mobile_no') }}"
                        id="branch-mobile"
                        name="mobile_no"
                        type="tel"
                        value="{{ getValue('mobile_no', $branch) }}"
                        maxlength="30"
                        autocomplete="tel"
                        {{ inputAttrs('mobile_no', 'branch-mobile') }}
                    >
                    @error('mobile_no')
                        <div class="invalid-feedback d-block" id="branch-mobile-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="branch-telephone">Telephone number</label>
                    <input
                        class="{{ inputClass('telephone_no') }}"
                        id="branch-telephone"
                        name="telephone_no"
                        type="tel"
                        value="{{ getValue('telephone_no', $branch) }}"
                        maxlength="30"
                        autocomplete="tel"
                        {{ inputAttrs('telephone_no', 'branch-telephone') }}
                    >
                    @error('telephone_no')
                        <div class="invalid-feedback d-block" id="branch-telephone-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="branch-email">Email address</label>
                    <input
                        class="{{ inputClass('email') }}"
                        id="branch-email"
                        name="email"
                        type="email"
                        value="{{ getValue('email', $branch) }}"
                        maxlength="255"
                        autocomplete="email"
                        {{ inputAttrs('email', 'branch-email') }}
                    >
                    @error('email')
                        <div class="invalid-feedback d-block" id="branch-email-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </section>
        <section class="app-form-section" aria-labelledby="branch-logo-heading">
            <h2 class="app-form-section-title" id="branch-logo-heading">Branch logo</h2>
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label" for="branch-logo">Logo</label>
                    @if ($branch->logo_path)
                        <img class="app-brand-preview img-thumbnail mb-2" src="{{ $branch->logo_url }}" alt="Current branch logo" width="160" height="80">
                    @endif
                    <input
                        class="{{ inputClass('logo') }}"
                        id="branch-logo"
                        name="logo"
                        type="file"
                        accept=".png,.jpg,.jpeg,.webp,.gif,image/png,image/jpeg,image/webp,image/gif"
                        {{ inputAttrs('logo', 'branch-logo', 'branch-logo-help') }}
                    >
                    <div class="form-text" id="branch-logo-help">Optional PNG, JPG, WebP, or GIF up to 2 MB. Leave blank to keep the current logo.</div>
                    @error('logo')
                        <div class="invalid-feedback d-block" id="branch-logo-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </section>
        @include('core::partials.form-actions', ['model' => $branch, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel])
    </form>
@endsection
