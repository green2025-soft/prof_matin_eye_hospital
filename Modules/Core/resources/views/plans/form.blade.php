@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle, 'description' => 'Define the price and access included in this plan.'])
    <form class="app-card p-3 p-md-4" action="{{ $formAction }}" method="POST">
        @csrf
        @if ($formMethod !== 'POST') @method($formMethod) @endif
        <section class="app-form-section" aria-labelledby="plan-details-heading">
            <h2 class="app-form-section-title" id="plan-details-heading">Plan details</h2>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="name">Name <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="{{ inputClass('name') }}" id="name" name="name" type="text" value="{{ getValue('name', $plan) }}" maxlength="255" required autofocus {{ inputAttrs('name') }}>
                    @error('name')<div class="invalid-feedback d-block" id="name-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="slug">Slug <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="{{ inputClass('slug') }}" id="slug" name="slug" type="text" value="{{ getValue('slug', $plan) }}" maxlength="80" required {{ inputAttrs('slug') }}>
                    @error('slug')<div class="invalid-feedback d-block" id="slug-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label" for="description">Description</label>
                    <textarea class="{{ inputClass('description') }}" id="description" name="description" rows="3" maxlength="5000" {{ inputAttrs('description') }}>{{ getValue('description', $plan) }}</textarea>
                    @error('description')<div class="invalid-feedback d-block" id="description-error">{{ $message }}</div>@enderror
                </div>
            </div>
        </section>
        <section class="app-form-section" aria-labelledby="plan-pricing-heading">
            <h2 class="app-form-section-title" id="plan-pricing-heading">Pricing</h2>
            <p class="app-form-section-note">New assignments use this price. Existing subscription pricing stays unchanged.</p>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label" for="price">Price <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="{{ inputClass('price') }}" id="price" name="price" type="text" inputmode="decimal" value="{{ getValue('price', null, $price) }}" maxlength="13" required {{ inputAttrs('price') }}>
                    @error('price')<div class="invalid-feedback d-block" id="price-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="currency">Currency <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="{{ inputClass('currency') }} text-uppercase" id="currency" name="currency" type="text" value="{{ getValue('currency', $plan, $currency) }}" minlength="3" maxlength="3" required {{ inputAttrs('currency') }}>
                    @error('currency')<div class="invalid-feedback d-block" id="currency-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="billing_interval">Billing interval <span class="text-danger" aria-hidden="true">*</span></label>
                    <select class="{{ inputClass('billing_interval', 'form-select') }}" id="billing_interval" name="billing_interval" required {{ inputAttrs('billing_interval') }}>
                        @foreach ($intervals as $value => $label)<option value="{{ $value }}" {{ getSelected($value, 'billing_interval', $plan) }}>{{ $label }}</option>@endforeach
                    </select>
                    @error('billing_interval')<div class="invalid-feedback d-block" id="billing_interval-error">{{ $message }}</div>@enderror
                </div>
            </div>
        </section>
        <section class="app-form-section" aria-labelledby="plan-access-heading">
            <h2 class="app-form-section-title" id="plan-access-heading">Access and limits</h2>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="trial_days">Trial days</label>
                    <input class="{{ inputClass('trial_days') }}" id="trial_days" name="trial_days" type="number" value="{{ getValue('trial_days', $plan, 0) }}" min="0" max="365" required {{ inputAttrs('trial_days') }}>
                    @error('trial_days')<div class="invalid-feedback d-block" id="trial_days-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="user_limit">User limit</label>
                    <input class="{{ inputClass('user_limit') }}" id="user_limit" name="user_limit" type="number" value="{{ getValue('user_limit', $plan) }}" min="1" max="1000000" {{ inputAttrs('user_limit', null, 'user-limit-help') }}>
                    <p class="form-text" id="user-limit-help">Leave blank for unlimited users.</p>
                    @error('user_limit')<div class="invalid-feedback d-block" id="user_limit-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label" for="features">Feature keys</label>
                    <textarea class="{{ inputClass('features') }}" id="features" name="features" rows="4" maxlength="5000" {{ inputAttrs('features', null, 'features-help') }}>{{ getValue('features', null, $featuresText) }}</textarea>
                    <p class="form-text" id="features-help">One lowercase feature key per line. Use letters, numbers, dots, underscores, or hyphens; start each key with a letter.</p>
                    @error('features')<div class="invalid-feedback d-block" id="features-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <input name="is_active" type="hidden" value="0">
                    <label class="form-check d-flex align-items-center gap-2"><input class="{{ inputClass('is_active', 'form-check-input') }}" id="is_active" name="is_active" type="checkbox" value="1" {{ getChecked(1, 'is_active', $plan, true) }} {{ inputAttrs('is_active') }}>Active and available for assignment</label>
                    @error('is_active')<div class="invalid-feedback d-block" id="is_active-error">{{ $message }}</div>@enderror
                </div>
            </div>
        </section>
        @include('core::partials.form-actions', ['model' => $plan, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel])
    </form>
@endsection
