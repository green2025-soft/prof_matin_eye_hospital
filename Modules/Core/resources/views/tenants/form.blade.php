@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @php($domainValues = array_slice(array_values(getValue('domains', null, $domains)), 0, 20) ?: [''])
    @include('core::partials.heading', ['title' => $pageTitle, 'description' => $tenant->exists ? 'Update workspace details and domain access.' : 'Set up a workspace and its administrator.'])
    <form
        class="app-card p-3 p-md-4"
        action="{{ $formAction }}"
        method="POST"
        enctype="multipart/form-data"
        data-tenant-form
        data-tenant-create="{{ $tenant->exists ? '0' : '1' }}"
        data-tenant-base-domain="{{ $tenantBaseDomain }}"
    >
        @csrf
        <input name="domains_auto" type="hidden" value="0" data-tenant-domain-auto>
        @if ($formMethod !== 'POST')
            @method($formMethod)
        @endif
        <div class="row g-4">
            <div class="col-lg-7">
                <section class="app-form-section" aria-labelledby="tenant-details-heading">
                    <h2 class="app-form-section-title" id="tenant-details-heading"><span class="app-form-section-icon"><i class="fa-solid fa-building" aria-hidden="true"></i></span>Workspace details</h2>
                    <p class="app-form-section-note">The workspace name and its unique identifier.</p>
                    <div class="mb-3">
                        <label class="form-label" for="tenant-name">Workspace name <span class="text-danger" aria-hidden="true">*</span></label>
                        <input class="{{ inputClass('name') }}" id="tenant-name" name="name" type="text" value="{{ getValue('name', $tenant) }}" minlength="5" maxlength="255" required autofocus {{ inputAttrs('name', 'tenant-name') }}>
                        @error('name')
                            <div class="invalid-feedback d-block" id="tenant-name-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-0">
                        <label class="form-label" for="tenant-slug">Slug <span class="text-danger" aria-hidden="true">*</span></label>
                        <input class="{{ inputClass('slug') }}" id="tenant-slug" name="slug" type="text" value="{{ getValue('slug', $tenant) }}" minlength="5" maxlength="80" autocomplete="off" required @readonly(! $tenant->exists) {{ inputAttrs('slug', 'tenant-slug', 'tenant-slug-help') }}>
                        @error('slug')
                            <div class="invalid-feedback d-block" id="tenant-slug-error">{{ $message }}</div>
                        @enderror
                        <div class="form-text" id="tenant-slug-help">
                            {{ $tenant->exists ? 'Use at least 5 lowercase letters, numbers, or hyphens. Changing the name does not change the slug.' : 'Generated from the workspace name. A suffix is added if the name is already used.' }}
                        </div>
                    </div>
                </section>
                <section class="app-form-section" aria-labelledby="tenant-contact-heading">
                    <h2 class="app-form-section-title" id="tenant-contact-heading">Contact and branding</h2>
                    <div class="mb-3">
                        <label class="form-label" for="tenant-address">Address</label>
                        <textarea class="{{ inputClass('address') }}" id="tenant-address" name="address" rows="3" minlength="5" maxlength="2000" {{ inputAttrs('address', 'tenant-address') }}>{{ getValue('address', $tenant) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback d-block" id="tenant-address-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="tenant-phone">Phone</label>
                        <input class="{{ inputClass('phone') }}" id="tenant-phone" name="phone" type="tel" inputmode="numeric" value="{{ getValue('phone', $tenant) }}" minlength="5" maxlength="30" pattern="[0-9]{5,30}" {{ inputAttrs('phone', 'tenant-phone', 'tenant-phone-help') }}>
                        <div class="form-text" id="tenant-phone-help">Optional. Enter at least 5 digits without spaces or symbols.</div>
                        @error('phone')
                            <div class="invalid-feedback d-block" id="tenant-phone-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label" for="tenant-logo">Logo</label>
                        @if ($logoUrl)
                            <div class="mb-2">
                                <img class="rounded border object-fit-cover" src="{{ $logoUrl }}" width="96" height="96" alt="{{ $tenant->name }} logo">
                            </div>
                        @endif
                        <input class="{{ inputClass('logo') }}" id="tenant-logo" name="logo" type="file" accept="image/png,image/jpeg,image/webp,image/gif" {{ inputAttrs('logo', 'tenant-logo', 'tenant-logo-help') }}>
                        <div class="form-text" id="tenant-logo-help">Optional PNG, JPG, WebP, or GIF, up to 2 MB. Leave blank to keep the current logo.</div>
                        @error('logo')
                            <div class="invalid-feedback d-block" id="tenant-logo-error">{{ $message }}</div>
                        @enderror
                    </div>
                </section>
                <fieldset class="app-form-section" data-domain-editor {{ inputAttrs('domains', null, 'domains-help') }}>
                    <legend class="app-form-section-title"><span class="app-form-section-icon"><i class="fa-solid fa-globe" aria-hidden="true"></i></span>Domains</legend>
                    <p class="app-form-section-note" id="domains-help">A domain is suggested from the slug and {{ $tenantBaseDomain }}. Edit it or add up to 20 hostnames without http:// or ports. Blank domains use the automatic domain.</p>
                    <div class="d-grid gap-2 mb-3" data-domain-list>
                        @foreach ($domainValues as $index => $domainValue)
                            <div class="d-flex align-items-start gap-2" data-domain-row>
                                <div class="flex-grow-1 app-domain-field">
                                    <label class="visually-hidden" for="tenant-domain-{{ $index }}" data-domain-label>Domain {{ $index + 1 }}</label>
                                    <input class="{{ inputClass('domains.'.$index) }}" id="tenant-domain-{{ $index }}" name="domains[]" type="text" value="{{ getValue('domains.'.$index, null, is_scalar($domainValue) ? $domainValue : '') }}" placeholder="workspace.{{ $tenantBaseDomain }}" minlength="5" maxlength="253" autocomplete="off" autocapitalize="none" spellcheck="false" {{ inputAttrs('domains.'.$index, 'tenant-domain-'.$index, 'domains-help') }}>
                                    @error('domains.'.$index)
                                        <div class="invalid-feedback d-block" id="tenant-domain-{{ $index }}-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button class="btn btn-outline-secondary app-domain-remove" type="button" data-domain-remove aria-label="Remove domain {{ $index + 1 }}" @disabled(count($domainValues) === 1)><i class="fa-solid fa-xmark" aria-hidden="true"></i></button>
                            </div>
                        @endforeach
                    </div>
                    @error('domains')
                        <div class="invalid-feedback d-block mb-2" id="domains-error">{{ $message }}</div>
                    @enderror
                    <button class="btn btn-outline-secondary btn-sm" type="button" data-domain-add @disabled(count($domainValues) >= 20)><i class="fa-solid fa-plus me-2" aria-hidden="true"></i>Add domain</button>
                    <span class="visually-hidden" data-domain-announcement aria-live="polite"></span>
                    <template data-domain-template>
                        <div class="d-flex align-items-start gap-2" data-domain-row>
                            <div class="flex-grow-1 app-domain-field">
                                <label class="visually-hidden" for="tenant-domain-template" data-domain-label>Domain</label>
                                <input class="form-control" id="tenant-domain-template" name="domains[]" type="text" placeholder="workspace.{{ $tenantBaseDomain }}" minlength="5" maxlength="253" autocomplete="off" autocapitalize="none" spellcheck="false" aria-describedby="domains-help">
                            </div>
                            <button class="btn btn-outline-secondary app-domain-remove" type="button" data-domain-remove aria-label="Remove domain"><i class="fa-solid fa-xmark" aria-hidden="true"></i></button>
                        </div>
                    </template>
                </fieldset>
                <section class="app-form-section">
                    <input name="has_branches" type="hidden" value="0">
                    <label class="form-check d-flex align-items-center gap-2" for="tenant-has-branches">
                        <input class="{{ inputClass('has_branches', 'form-check-input') }}" id="tenant-has-branches" name="has_branches" type="checkbox" value="1" {{ getChecked(1, 'has_branches', $tenant, false) }} {{ inputAttrs('has_branches', 'tenant-has-branches') }}>
                        This workspace has branches
                    </label>
                    @error('has_branches')
                        <div class="invalid-feedback d-block" id="tenant-has-branches-error">{{ $message }}</div>
                    @enderror
                </section>
            </div>
            <div class="col-lg-5">
                <section class="app-form-section" aria-labelledby="tenant-account-heading">
                    <h2 class="app-form-section-title" id="tenant-account-heading"><span class="app-form-section-icon"><i class="fa-solid fa-user-shield" aria-hidden="true"></i></span>Administrator</h2>
                    <p class="app-form-section-note">{{ $tenant->exists ? 'The administrator email is fixed for this workspace.' : 'Create the first administrator for this workspace.' }}</p>
                    @if ($tenant->exists)
                        <div class="mb-0">
                            <label class="form-label" for="owner-email">Email address</label>
                            <input class="{{ inputClass('owner_email') }}" id="owner-email" type="email" value="{{ $tenant->owner_email }}" readonly {{ inputAttrs('owner_email', 'owner-email') }}>
                            @error('owner_email')
                                <div class="invalid-feedback d-block" id="owner-email-error">{{ $message }}</div>
                            @enderror
                        </div>
                    @else
                        <div class="mb-3">
                            <label class="form-label" for="owner-name">Full name <span class="text-danger" aria-hidden="true">*</span></label>
                            <input class="{{ inputClass('owner_name') }}" id="owner-name" name="owner_name" type="text" value="{{ getValue('owner_name') }}" minlength="5" maxlength="255" autocomplete="name" required {{ inputAttrs('owner_name', 'owner-name') }}>
                            @error('owner_name')
                                <div class="invalid-feedback d-block" id="owner-name-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-0">
                            <label class="form-label" for="owner-email">Email address <span class="text-danger" aria-hidden="true">*</span></label>
                            <input class="{{ inputClass('owner_email') }}" id="owner-email" name="owner_email" type="email" value="{{ getValue('owner_email') }}" minlength="5" maxlength="255" autocomplete="email" required {{ inputAttrs('owner_email', 'owner-email') }}>
                            @error('owner_email')
                                <div class="invalid-feedback d-block" id="owner-email-error">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif
                </section>
                <section class="app-form-section" aria-labelledby="tenant-access-heading">
                    <h2 class="app-form-section-title" id="tenant-access-heading"><span class="app-form-section-icon"><i class="fa-solid fa-lock" aria-hidden="true"></i></span>{{ $tenant->exists ? 'Workspace access' : 'Password & security' }}</h2>
                    <p class="app-form-section-note">{{ $tenant->exists ? 'Control whether this workspace can be accessed.' : 'Set an administrator password with at least '.$passwordMinLength.' characters.' }}</p>
                    @if ($tenant->exists)
                        <div class="mb-0">
                            <label class="form-label" for="tenant-status">Status @if (count($statusOptions) > 1)<span class="text-danger" aria-hidden="true">*</span>@endif</label>
                            @if (count($statusOptions) > 1)
                                <select class="{{ inputClass('status', 'form-select') }}" id="tenant-status" name="status" required {{ inputAttrs('status', 'tenant-status') }}>
                                    @foreach ($statusOptions as $value => $label)
                                        <option value="{{ $value }}" {{ getSelected($value, 'status', $tenant) }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            @else
                                <input class="{{ inputClass('status') }}" id="tenant-status" type="text" value="{{ array_values($statusOptions)[0] ?? '' }}" disabled {{ inputAttrs('status', 'tenant-status', 'tenant-status-help') }}>
                                <input name="status" type="hidden" value="{{ $tenant->status }}">
                                <div class="form-text" id="tenant-status-help">Access can be changed after workspace setup completes.</div>
                            @endif
                            @error('status')
                                <div class="invalid-feedback d-block" id="tenant-status-error">{{ $message }}</div>
                            @enderror
                        </div>
                    @else
                        <div class="mb-3">
                            <label class="form-label" for="owner-password">Password <span class="text-danger" aria-hidden="true">*</span></label>
                            <div class="app-input-wrap">
                                <input class="{{ inputClass('password') }} app-password-input" id="owner-password" name="password" type="password" minlength="{{ $passwordMinLength }}" maxlength="4096" autocomplete="new-password" required {{ inputAttrs('password', 'owner-password') }}>
                                <button class="app-password-toggle" type="button" data-password-toggle aria-controls="owner-password" aria-label="Show password" aria-pressed="false" title="Show password"><i class="fa-solid fa-eye" aria-hidden="true"></i></button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block" id="owner-password-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-0">
                            <label class="form-label" for="owner-password-confirmation">Confirm password <span class="text-danger" aria-hidden="true">*</span></label>
                            <div class="app-input-wrap">
                                <input class="{{ inputClass('password_confirmation') }} app-password-input" id="owner-password-confirmation" name="password_confirmation" type="password" minlength="{{ $passwordMinLength }}" maxlength="4096" autocomplete="new-password" required {{ inputAttrs('password_confirmation', 'owner-password-confirmation') }}>
                                <button class="app-password-toggle" type="button" data-password-toggle aria-controls="owner-password-confirmation" aria-label="Show password" aria-pressed="false" title="Show password"><i class="fa-solid fa-eye" aria-hidden="true"></i></button>
                            </div>
                            @error('password_confirmation')
                                <div class="invalid-feedback d-block" id="owner-password-confirmation-error">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif
                </section>
            </div>
        </div>
        @include('core::partials.form-actions', ['model' => $tenant, 'routePrefix' => 'core.tenants', 'submitLabel' => $submitLabel])
    </form>
@endsection
