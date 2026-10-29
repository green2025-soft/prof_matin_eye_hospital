<form class="app-card p-3 p-md-4" action="{{ $formAction }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <fieldset class="app-form-section border-0 p-0 mb-0" aria-labelledby="settings-{{ $setting->group }}-heading" @disabled(! $canUpdate)>
        <h2 class="app-form-section-title" id="settings-{{ $setting->group }}-heading"><span class="app-form-section-icon"><i class="fa-solid fa-{{ $groupIcon }}" aria-hidden="true"></i></span>{{ $groupLabel }}</h2>
        @error('data')<div class="invalid-feedback d-block mb-3">{{ $message }}</div>@enderror

        @if ($activeGroup === 'payments')
            <div class="row g-3 align-items-start mb-4">
                <div class="col-md-6">
                    <div class="form-check mb-3">
                        <input name="data[enabled]" type="hidden" value="0">
                        <input
                            class="{{ inputClass('data.enabled', 'form-check-input') }}"
                            id="payment-{{ $setting->group }}-enabled"
                            name="data[enabled]"
                            type="checkbox"
                            value="1"
                            {{ getChecked(1, 'data.enabled', null, $values['data']['enabled']) }}
                            {{ inputAttrs('data.enabled', 'payment-'.$setting->group.'-enabled') }}
                        >
                        <label class="form-check-label" for="payment-{{ $setting->group }}-enabled">
                            Enable {{ $groupLabel }}
                        </label>
                        @error('data.enabled')
                            <div class="invalid-feedback d-block" id="payment-{{ $setting->group }}-enabled-error">{{ $message }}</div>
                        @enderror
                    </div>
                    @if ($setting->group !== 'manual')
                        <div class="form-check">
                            <input name="data[sandbox]" type="hidden" value="0">
                            <input
                                class="{{ inputClass('data.sandbox', 'form-check-input') }}"
                                id="payment-{{ $setting->group }}-sandbox"
                                name="data[sandbox]"
                                type="checkbox"
                                value="1"
                                {{ getChecked(1, 'data.sandbox', null, $values['data']['sandbox']) }}
                                {{ inputAttrs('data.sandbox', 'payment-'.$setting->group.'-sandbox') }}
                            >
                            <label class="form-check-label" for="payment-{{ $setting->group }}-sandbox">Sandbox mode</label>
                            @error('data.sandbox')
                                <div class="invalid-feedback d-block" id="payment-{{ $setting->group }}-sandbox-error">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="payment-{{ $setting->group }}-charge">Extra charge</label>
                    <input
                        class="{{ inputClass('data.extra_charge') }}"
                        id="payment-{{ $setting->group }}-charge"
                        name="data[extra_charge]"
                        type="text"
                        inputmode="decimal"
                        value="{{ getValue('data.extra_charge', null, $values['data']['extra_charge']) }}"
                        maxlength="13"
                        placeholder="0.00"
                        required
                        {{ inputAttrs('data.extra_charge', 'payment-'.$setting->group.'-charge', 'payment-'.$setting->group.'-charge-help') }}
                    >
                    <div class="form-text" id="payment-{{ $setting->group }}-charge-help">
                        Fixed amount for this payment method. Enter 0.00 for no extra charge.
                    </div>
                    @error('data.extra_charge')
                        <div class="invalid-feedback d-block" id="payment-{{ $setting->group }}-charge-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        @endif

        @if ($setting->group === 'company')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="company-name">Company name <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="{{ inputClass('data.name') }}" id="company-name" name="data[name]" type="text" value="{{ getValue('data.name', null, $values['data']['name']) }}" maxlength="255" autocomplete="organization" required {{ inputAttrs('data.name', 'company-name') }}>
                    @error('data.name')<div class="invalid-feedback d-block" id="company-name-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="company-email">Email address</label>
                    <input class="{{ inputClass('data.email') }}" id="company-email" name="data[email]" type="email" value="{{ getValue('data.email', null, $values['data']['email']) }}" maxlength="255" autocomplete="email" {{ inputAttrs('data.email', 'company-email') }}>
                    @error('data.email')<div class="invalid-feedback d-block" id="company-email-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="company-phone">Phone</label>
                    <input class="{{ inputClass('data.phone') }}" id="company-phone" name="data[phone]" type="tel" inputmode="numeric" pattern="[0-9]{1,30}" value="{{ getValue('data.phone', null, $values['data']['phone']) }}" maxlength="30" autocomplete="tel" {{ inputAttrs('data.phone', 'company-phone') }}>
                    @error('data.phone')<div class="invalid-feedback d-block" id="company-phone-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="company-tax-id">Tax identification</label>
                    <input class="{{ inputClass('data.tax_id') }}" id="company-tax-id" name="data[tax_id]" type="text" value="{{ getValue('data.tax_id', null, $values['data']['tax_id']) }}" maxlength="100" autocomplete="off" {{ inputAttrs('data.tax_id', 'company-tax-id') }}>
                    @error('data.tax_id')<div class="invalid-feedback d-block" id="company-tax-id-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label" for="company-address">Address</label>
                    <textarea class="{{ inputClass('data.address') }}" id="company-address" name="data[address]" rows="3" maxlength="2000" autocomplete="street-address" {{ inputAttrs('data.address', 'company-address') }}>{{ getValue('data.address', null, $values['data']['address']) }}</textarea>
                    @error('data.address')<div class="invalid-feedback d-block" id="company-address-error">{{ $message }}</div>@enderror
                </div>
            </div>
        @elseif ($setting->group === 'branding')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="branding-name">Application name <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="{{ inputClass('data.app_name') }}" id="branding-name" name="data[app_name]" type="text" value="{{ getValue('data.app_name', null, $values['data']['app_name']) }}" maxlength="120" required {{ inputAttrs('data.app_name', 'branding-name') }}>
                    @error('data.app_name')<div class="invalid-feedback d-block" id="branding-name-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="branding-tagline">Tagline</label>
                    <input class="{{ inputClass('data.tagline') }}" id="branding-tagline" name="data[tagline]" type="text" value="{{ getValue('data.tagline', null, $values['data']['tagline']) }}" maxlength="255" {{ inputAttrs('data.tagline', 'branding-tagline') }}>
                    @error('data.tagline')<div class="invalid-feedback d-block" id="branding-tagline-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="branding-logo">Logo</label>
                    @if ($logoUrl)
                        <img class="app-brand-preview img-thumbnail mb-2" src="{{ $logoUrl }}" alt="Current workspace logo" width="160" height="80">
                    @endif
                    <input
                        class="{{ inputClass('logo') }}"
                        id="branding-logo"
                        name="logo"
                        type="file"
                        accept=".png,.jpg,.jpeg,.webp,.gif,image/png,image/jpeg,image/webp,image/gif"
                        {{ inputAttrs('logo', 'branding-logo', 'branding-logo-help') }}
                    >
                    <div class="form-text" id="branding-logo-help">
                        PNG, JPG, WebP, or GIF up to 2 MB. Leave empty to keep the current logo.
                    </div>
                    @error('logo')
                        <div class="invalid-feedback d-block" id="branding-logo-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="branding-favicon">Favicon</label>
                    @if ($faviconUrl)
                        <img class="app-brand-preview app-favicon-preview img-thumbnail mb-2" src="{{ $faviconUrl }}" alt="Current workspace favicon" width="48" height="48">
                    @endif
                    <input
                        class="{{ inputClass('favicon') }}"
                        id="branding-favicon"
                        name="favicon"
                        type="file"
                        accept=".png,.ico,image/png,image/x-icon,image/vnd.microsoft.icon"
                        {{ inputAttrs('favicon', 'branding-favicon', 'branding-favicon-help') }}
                    >
                    <div class="form-text" id="branding-favicon-help">
                        PNG or ICO up to 512 KB. Leave empty to keep the current browser icon.
                    </div>
                    @error('favicon')
                        <div class="invalid-feedback d-block" id="branding-favicon-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        @elseif ($setting->group === 'billing')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="billing-currency">Currency code <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="{{ inputClass('data.currency') }}" id="billing-currency" name="data[currency]" type="text" value="{{ getValue('data.currency', null, $values['data']['currency']) }}" minlength="3" maxlength="3" placeholder="BDT" required {{ inputAttrs('data.currency', 'billing-currency') }}>
                    @error('data.currency')<div class="invalid-feedback d-block" id="billing-currency-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="billing-prefix">Invoice prefix <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="{{ inputClass('data.invoice_prefix') }}" id="billing-prefix" name="data[invoice_prefix]" type="text" value="{{ getValue('data.invoice_prefix', null, $values['data']['invoice_prefix']) }}" maxlength="20" required {{ inputAttrs('data.invoice_prefix', 'billing-prefix') }}>
                    @error('data.invoice_prefix')<div class="invalid-feedback d-block" id="billing-prefix-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label" for="billing-instructions">Payment instructions</label>
                    <textarea class="{{ inputClass('data.payment_instructions') }}" id="billing-instructions" name="data[payment_instructions]" rows="4" maxlength="4000" {{ inputAttrs('data.payment_instructions', 'billing-instructions') }}>{{ getValue('data.payment_instructions', null, $values['data']['payment_instructions']) }}</textarea>
                    @error('data.payment_instructions')<div class="invalid-feedback d-block" id="billing-instructions-error">{{ $message }}</div>@enderror
                </div>
            </div>
        @elseif ($setting->group === 'general')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="general-timezone">Timezone <span class="text-danger" aria-hidden="true">*</span></label>
                    <select class="{{ inputClass('data.timezone', 'form-select') }}" id="general-timezone" name="data[timezone]" required {{ inputAttrs('data.timezone', 'general-timezone') }}>
                        @foreach ($timezones as $timezone)
                            <option value="{{ $timezone }}" {{ getSelected($timezone, 'data.timezone', null, $values['data']['timezone']) }}>{{ $timezone }}</option>
                        @endforeach
                    </select>
                    @error('data.timezone')<div class="invalid-feedback d-block" id="general-timezone-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="general-locale">Locale <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="{{ inputClass('data.locale') }}" id="general-locale" name="data[locale]" type="text" value="{{ getValue('data.locale', null, $values['data']['locale']) }}" maxlength="12" placeholder="en" required {{ inputAttrs('data.locale', 'general-locale', 'locale-help') }}>
                    @error('data.locale')<div class="invalid-feedback d-block" id="general-locale-error">{{ $message }}</div>@enderror
                    <div class="form-text" id="locale-help">Language code, such as en or bn.</div>
                </div>
            </div>
        @elseif ($setting->group === 'hospital')
            <input name="section" type="hidden" value="{{ $activeHospitalSection }}">
            @if (! in_array($activeHospitalSection, ['patient', 'doctor', 'reference'], true))
                <p class="small text-body-secondary mb-2">Numbers are generated automatically. Settings changes apply to future records.</p>
                <details class="mb-3">
                    <summary class="small text-body-secondary">Pattern guide</summary>
                    <p class="small text-body-secondary mt-2 mb-0" id="hospital-number-pattern-help">Patterns support {YYYY}, {MM}, {DD}, {BRANCH}, {DOCTOR}, and exactly one {SEQ} or {SEQ:1} to {SEQ:12} token. {SEQ} uses at least six digits. The number after SEQ sets its minimum digits. Use letters, numbers, spaces, . _ / - between tokens. Branch invoice patterns need {BRANCH}; yearly, monthly, and daily invoice resets also need the matching date tokens. Invoice numbers stay fixed; changing a doctor or date regenerates the serial only when its numbering series changes.</p>
                </details>
            @endif

            @if ($activeHospitalSection === 'patient')
                <section aria-labelledby="hospital-patient-heading">
                    <h3 class="h6 mb-3" id="hospital-patient-heading">Patient</h3>
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label" for="hospital-patient-id-scope">Patient ID numbering <span class="text-danger" aria-hidden="true">*</span></label>
                            <select class="{{ inputClass('data.id_scope', 'form-select') }}" id="hospital-patient-id-scope" name="data[id_scope]" required {{ inputAttrs('data.id_scope', 'hospital-patient-id-scope', 'hospital-patient-id-scope-help') }}>
                                <option value="tenant" {{ getSelected('tenant', 'data.id_scope', null, $values['data']['id_scope']) }}>Tenant-wide continuous</option>
                                <option value="branch" {{ getSelected('branch', 'data.id_scope', null, $values['data']['id_scope']) }}>Separate by branch</option>
                            </select>
                            @error('data.id_scope')<div class="invalid-feedback d-block" id="hospital-patient-id-scope-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="hospital-patient-id-pattern">Tenant ID pattern <span class="text-danger" aria-hidden="true">*</span></label>
                            <input class="{{ inputClass('data.patient_id_pattern') }}" id="hospital-patient-id-pattern" name="data[patient_id_pattern]" type="text" value="{{ getValue('data.patient_id_pattern', null, $values['data']['patient_id_pattern']) }}" maxlength="40" placeholder="P{SEQ:6}" required {{ inputAttrs('data.patient_id_pattern', 'hospital-patient-id-pattern', 'hospital-patient-pattern-help') }}>
                            @error('data.patient_id_pattern')<div class="invalid-feedback d-block" id="hospital-patient-id-pattern-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="hospital-patient-branch-id-pattern">Branch ID pattern <span class="text-danger" aria-hidden="true">*</span></label>
                            <input class="{{ inputClass('data.patient_branch_id_pattern') }}" id="hospital-patient-branch-id-pattern" name="data[patient_branch_id_pattern]" type="text" value="{{ getValue('data.patient_branch_id_pattern', null, $values['data']['patient_branch_id_pattern']) }}" maxlength="40" placeholder="B{BRANCH}-P{SEQ:6}" required {{ inputAttrs('data.patient_branch_id_pattern', 'hospital-patient-branch-id-pattern', 'hospital-patient-pattern-help') }}>
                            @error('data.patient_branch_id_pattern')<div class="invalid-feedback d-block" id="hospital-patient-branch-id-pattern-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-text" id="hospital-patient-id-scope-help">Changes apply only to new Patient IDs. Existing IDs and sequence counters are retained.</div>
                    <div class="form-text" id="hospital-patient-pattern-help">Include exactly one {SEQ} or {SEQ:1} to {SEQ:12} token. You can also use {BRANCH}, {YYYY}, {MM}, and {DD}; branch patterns must include {BRANCH}.</div>
                </section>

            @elseif ($activeHospitalSection === 'doctor')
                <section aria-labelledby="hospital-doctor-heading">
                    <h3 class="h6 mb-3" id="hospital-doctor-heading">Doctor</h3>
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label" for="hospital-doctor-id-scope">Doctor ID numbering <span class="text-danger" aria-hidden="true">*</span></label>
                            <select class="{{ inputClass('data.doctor_id_scope', 'form-select') }}" id="hospital-doctor-id-scope" name="data[doctor_id_scope]" required {{ inputAttrs('data.doctor_id_scope', 'hospital-doctor-id-scope', 'hospital-doctor-id-scope-help') }}>
                                <option value="tenant" {{ getSelected('tenant', 'data.doctor_id_scope', null, $values['data']['doctor_id_scope']) }}>Tenant-wide continuous</option>
                                <option value="branch" {{ getSelected('branch', 'data.doctor_id_scope', null, $values['data']['doctor_id_scope']) }}>Separate by branch</option>
                            </select>
                            @error('data.doctor_id_scope')<div class="invalid-feedback d-block" id="hospital-doctor-id-scope-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="hospital-doctor-id-pattern">Tenant ID pattern <span class="text-danger" aria-hidden="true">*</span></label>
                            <input class="{{ inputClass('data.doctor_id_pattern') }}" id="hospital-doctor-id-pattern" name="data[doctor_id_pattern]" type="text" value="{{ getValue('data.doctor_id_pattern', null, $values['data']['doctor_id_pattern']) }}" maxlength="40" placeholder="D{SEQ:6}" required {{ inputAttrs('data.doctor_id_pattern', 'hospital-doctor-id-pattern', 'hospital-doctor-pattern-help') }}>
                            @error('data.doctor_id_pattern')<div class="invalid-feedback d-block" id="hospital-doctor-id-pattern-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="hospital-doctor-branch-id-pattern">Branch ID pattern <span class="text-danger" aria-hidden="true">*</span></label>
                            <input class="{{ inputClass('data.doctor_branch_id_pattern') }}" id="hospital-doctor-branch-id-pattern" name="data[doctor_branch_id_pattern]" type="text" value="{{ getValue('data.doctor_branch_id_pattern', null, $values['data']['doctor_branch_id_pattern']) }}" maxlength="40" placeholder="B{BRANCH}-D{SEQ:6}" required {{ inputAttrs('data.doctor_branch_id_pattern', 'hospital-doctor-branch-id-pattern', 'hospital-doctor-pattern-help') }}>
                            @error('data.doctor_branch_id_pattern')<div class="invalid-feedback d-block" id="hospital-doctor-branch-id-pattern-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-text" id="hospital-doctor-id-scope-help">Changes apply only to new Doctor IDs. Existing IDs and sequence counters are retained.</div>
                    <div class="form-text" id="hospital-doctor-pattern-help">Include exactly one {SEQ} or {SEQ:1} to {SEQ:12} token. You can also use {BRANCH}, {YYYY}, {MM}, and {DD}; branch patterns must include {BRANCH}.</div>
                </section>

            @elseif ($activeHospitalSection === 'reference')
                <section aria-label="Reference settings">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label" for="hospital-reference-id-scope">ID numbering <span class="text-danger" aria-hidden="true">*</span></label>
                            <select class="{{ inputClass('data.reference_id_scope', 'form-select') }}" id="hospital-reference-id-scope" name="data[reference_id_scope]" required {{ inputAttrs('data.reference_id_scope', 'hospital-reference-id-scope', 'hospital-reference-id-scope-help') }}>
                                <option value="tenant" {{ getSelected('tenant', 'data.reference_id_scope', null, $values['data']['reference_id_scope']) }}>Tenant-wide</option>
                                <option value="branch" {{ getSelected('branch', 'data.reference_id_scope', null, $values['data']['reference_id_scope']) }}>By branch</option>
                            </select>
                            @error('data.reference_id_scope')<div class="invalid-feedback d-block" id="hospital-reference-id-scope-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="hospital-reference-id-pattern">Tenant ID pattern <span class="text-danger" aria-hidden="true">*</span></label>
                            <input class="{{ inputClass('data.reference_id_pattern') }}" id="hospital-reference-id-pattern" name="data[reference_id_pattern]" type="text" value="{{ getValue('data.reference_id_pattern', null, $values['data']['reference_id_pattern']) }}" maxlength="40" placeholder="R{SEQ:6}" required {{ inputAttrs('data.reference_id_pattern', 'hospital-reference-id-pattern', 'hospital-reference-pattern-help') }}>
                            @error('data.reference_id_pattern')<div class="invalid-feedback d-block" id="hospital-reference-id-pattern-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="hospital-reference-branch-id-pattern">Branch ID pattern <span class="text-danger" aria-hidden="true">*</span></label>
                            <input class="{{ inputClass('data.reference_branch_id_pattern') }}" id="hospital-reference-branch-id-pattern" name="data[reference_branch_id_pattern]" type="text" value="{{ getValue('data.reference_branch_id_pattern', null, $values['data']['reference_branch_id_pattern']) }}" maxlength="40" placeholder="B{BRANCH}-R{SEQ:6}" required {{ inputAttrs('data.reference_branch_id_pattern', 'hospital-reference-branch-id-pattern', 'hospital-reference-pattern-help') }}>
                            @error('data.reference_branch_id_pattern')<div class="invalid-feedback d-block" id="hospital-reference-branch-id-pattern-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="d-flex flex-wrap gap-3 my-3">
                        <div class="form-check form-switch">
                            <input name="data[reference_defaults_enabled]" type="hidden" value="0">
                            <input class="{{ inputClass('data.reference_defaults_enabled', 'form-check-input') }}" id="hospital-reference-defaults-enabled" name="data[reference_defaults_enabled]" type="checkbox" role="switch" value="1" {{ getChecked(1, 'data.reference_defaults_enabled', null, $values['data']['reference_defaults_enabled']) }} {{ inputAttrs('data.reference_defaults_enabled', 'hospital-reference-defaults-enabled', 'hospital-reference-defaults-help') }}>
                            <label class="form-check-label" for="hospital-reference-defaults-enabled">Use default rates</label>
                            @error('data.reference_defaults_enabled')<div class="invalid-feedback d-block" id="hospital-reference-defaults-enabled-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-check form-switch">
                            <input name="data[reference_percentages_enabled]" type="hidden" value="0">
                            <input class="{{ inputClass('data.reference_percentages_enabled', 'form-check-input') }}" id="hospital-reference-percentages-enabled" name="data[reference_percentages_enabled]" type="checkbox" role="switch" value="1" {{ getChecked(1, 'data.reference_percentages_enabled', null, $values['data']['reference_percentages_enabled']) }} {{ inputAttrs('data.reference_percentages_enabled', 'hospital-reference-percentages-enabled', 'hospital-reference-percentages-help') }}>
                            <label class="form-check-label" for="hospital-reference-percentages-enabled">Show % in Reference form</label>
                            @error('data.reference_percentages_enabled')<div class="invalid-feedback d-block" id="hospital-reference-percentages-enabled-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="row g-2 align-items-center border-top py-2" role="group" aria-labelledby="hospital-reference-consultation-settings-heading">
                        <div class="col-12 col-xl-2">
                            <h3 class="h6 mb-0" id="hospital-reference-consultation-settings-heading">Consultation</h3>
                        </div>
                        <div class="col-6 col-xl-2">
                            <label class="form-label small mb-1" for="hospital-reference-consultation-percent"><span class="visually-hidden">Consultation </span>Default %</label>
                            <input class="{{ inputClass('data.reference_consultation_percent') }}" id="hospital-reference-consultation-percent" name="data[reference_consultation_percent]" type="number" min="0" max="100" step="0.01" inputmode="decimal" value="{{ getValue('data.reference_consultation_percent', null, $values['data']['reference_consultation_percent']) }}" required {{ inputAttrs('data.reference_consultation_percent', 'hospital-reference-consultation-percent', 'hospital-reference-percent-help') }}>
                            @error('data.reference_consultation_percent')<div class="invalid-feedback d-block" id="hospital-reference-consultation-percent-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-6 col-xl-2">
                            <div class="form-check form-switch">
                                <input name="data[reference_consultation_percent_enabled]" type="hidden" value="0">
                                <input class="{{ inputClass('data.reference_consultation_percent_enabled', 'form-check-input') }}" id="hospital-reference-consultation-percent-enabled" name="data[reference_consultation_percent_enabled]" type="checkbox" role="switch" value="1" {{ getChecked(1, 'data.reference_consultation_percent_enabled', null, $values['data']['reference_consultation_percent_enabled']) }} {{ inputAttrs('data.reference_consultation_percent_enabled', 'hospital-reference-consultation-percent-enabled', 'hospital-reference-percentages-help') }}>
                                <label class="form-check-label" for="hospital-reference-consultation-percent-enabled">Show %<span class="visually-hidden"> for Consultation</span></label>
                                @error('data.reference_consultation_percent_enabled')<div class="invalid-feedback d-block" id="hospital-reference-consultation-percent-enabled-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-xl-3">
                            <label class="form-label small mb-1" for="hospital-reference-consultation-discount-mode"><span class="visually-hidden">Consultation </span>Discount rule</label>
                            <select class="{{ inputClass('data.reference_consultation_discount_mode', 'form-select') }}" id="hospital-reference-consultation-discount-mode" name="data[reference_consultation_discount_mode]" required {{ inputAttrs('data.reference_consultation_discount_mode', 'hospital-reference-consultation-discount-mode', 'hospital-reference-discount-rules-help') }}>
                                @foreach (config('settings.reference_discount_modes') as $mode => $label)
                                    <option value="{{ $mode }}" {{ getSelected($mode, 'data.reference_consultation_discount_mode', null, $values['data']['reference_consultation_discount_mode']) }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('data.reference_consultation_discount_mode')<div class="invalid-feedback d-block" id="hospital-reference-consultation-discount-mode-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6 col-xl-3">
                            <label class="form-label small mb-1" for="hospital-reference-consultation-due-mode"><span class="visually-hidden">Consultation </span>Due rule</label>
                            <select class="{{ inputClass('data.reference_consultation_due_mode', 'form-select') }}" id="hospital-reference-consultation-due-mode" name="data[reference_consultation_due_mode]" required {{ inputAttrs('data.reference_consultation_due_mode', 'hospital-reference-consultation-due-mode', 'hospital-reference-due-rules-help') }}>
                                @foreach (config('settings.reference_due_modes') as $mode => $label)
                                    <option value="{{ $mode }}" {{ getSelected($mode, 'data.reference_consultation_due_mode', null, $values['data']['reference_consultation_due_mode']) }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('data.reference_consultation_due_mode')<div class="invalid-feedback d-block" id="hospital-reference-consultation-due-mode-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="row g-2 align-items-center border-top py-2" role="group" aria-labelledby="hospital-reference-investigation-settings-heading">
                        <div class="col-12 col-xl-2">
                            <h3 class="h6 mb-0" id="hospital-reference-investigation-settings-heading">Investigation</h3>
                        </div>
                        <div class="col-6 col-xl-2">
                            <label class="form-label small mb-1" for="hospital-reference-investigation-percent"><span class="visually-hidden">Investigation </span>Default %</label>
                            <input class="{{ inputClass('data.reference_investigation_percent') }}" id="hospital-reference-investigation-percent" name="data[reference_investigation_percent]" type="number" min="0" max="100" step="0.01" inputmode="decimal" value="{{ getValue('data.reference_investigation_percent', null, $values['data']['reference_investigation_percent']) }}" required {{ inputAttrs('data.reference_investigation_percent', 'hospital-reference-investigation-percent', 'hospital-reference-percent-help') }}>
                            @error('data.reference_investigation_percent')<div class="invalid-feedback d-block" id="hospital-reference-investigation-percent-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-6 col-xl-2">
                            <div class="form-check form-switch">
                                <input name="data[reference_investigation_percent_enabled]" type="hidden" value="0">
                                <input class="{{ inputClass('data.reference_investigation_percent_enabled', 'form-check-input') }}" id="hospital-reference-investigation-percent-enabled" name="data[reference_investigation_percent_enabled]" type="checkbox" role="switch" value="1" {{ getChecked(1, 'data.reference_investigation_percent_enabled', null, $values['data']['reference_investigation_percent_enabled']) }} {{ inputAttrs('data.reference_investigation_percent_enabled', 'hospital-reference-investigation-percent-enabled', 'hospital-reference-percentages-help') }}>
                                <label class="form-check-label" for="hospital-reference-investigation-percent-enabled">Show %<span class="visually-hidden"> for Investigation</span></label>
                                @error('data.reference_investigation_percent_enabled')<div class="invalid-feedback d-block" id="hospital-reference-investigation-percent-enabled-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-xl-3">
                            <label class="form-label small mb-1" for="hospital-reference-investigation-discount-mode"><span class="visually-hidden">Investigation </span>Discount rule</label>
                            <select class="{{ inputClass('data.reference_investigation_discount_mode', 'form-select') }}" id="hospital-reference-investigation-discount-mode" name="data[reference_investigation_discount_mode]" required {{ inputAttrs('data.reference_investigation_discount_mode', 'hospital-reference-investigation-discount-mode', 'hospital-reference-discount-rules-help') }}>
                                @foreach (config('settings.reference_discount_modes') as $mode => $label)
                                    <option value="{{ $mode }}" {{ getSelected($mode, 'data.reference_investigation_discount_mode', null, $values['data']['reference_investigation_discount_mode']) }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('data.reference_investigation_discount_mode')<div class="invalid-feedback d-block" id="hospital-reference-investigation-discount-mode-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6 col-xl-3">
                            <label class="form-label small mb-1" for="hospital-reference-investigation-due-mode"><span class="visually-hidden">Investigation </span>Due rule</label>
                            <select class="{{ inputClass('data.reference_investigation_due_mode', 'form-select') }}" id="hospital-reference-investigation-due-mode" name="data[reference_investigation_due_mode]" required {{ inputAttrs('data.reference_investigation_due_mode', 'hospital-reference-investigation-due-mode', 'hospital-reference-due-rules-help') }}>
                                @foreach (config('settings.reference_due_modes') as $mode => $label)
                                    <option value="{{ $mode }}" {{ getSelected($mode, 'data.reference_investigation_due_mode', null, $values['data']['reference_investigation_due_mode']) }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('data.reference_investigation_due_mode')<div class="invalid-feedback d-block" id="hospital-reference-investigation-due-mode-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="row g-2 align-items-center border-top py-2" role="group" aria-labelledby="hospital-reference-operation-settings-heading">
                        <div class="col-12 col-xl-2">
                            <h3 class="h6 mb-0" id="hospital-reference-operation-settings-heading">Operation</h3>
                        </div>
                        <div class="col-6 col-xl-2">
                            <label class="form-label small mb-1" for="hospital-reference-operation-percent"><span class="visually-hidden">Operation </span>Default %</label>
                            <input class="{{ inputClass('data.reference_operation_percent') }}" id="hospital-reference-operation-percent" name="data[reference_operation_percent]" type="number" min="0" max="100" step="0.01" inputmode="decimal" value="{{ getValue('data.reference_operation_percent', null, $values['data']['reference_operation_percent']) }}" required {{ inputAttrs('data.reference_operation_percent', 'hospital-reference-operation-percent', 'hospital-reference-percent-help') }}>
                            @error('data.reference_operation_percent')<div class="invalid-feedback d-block" id="hospital-reference-operation-percent-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-6 col-xl-2">
                            <div class="form-check form-switch">
                                <input name="data[reference_operation_percent_enabled]" type="hidden" value="0">
                                <input class="{{ inputClass('data.reference_operation_percent_enabled', 'form-check-input') }}" id="hospital-reference-operation-percent-enabled" name="data[reference_operation_percent_enabled]" type="checkbox" role="switch" value="1" {{ getChecked(1, 'data.reference_operation_percent_enabled', null, $values['data']['reference_operation_percent_enabled']) }} {{ inputAttrs('data.reference_operation_percent_enabled', 'hospital-reference-operation-percent-enabled', 'hospital-reference-percentages-help') }}>
                                <label class="form-check-label" for="hospital-reference-operation-percent-enabled">Show %<span class="visually-hidden"> for Operation</span></label>
                                @error('data.reference_operation_percent_enabled')<div class="invalid-feedback d-block" id="hospital-reference-operation-percent-enabled-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-xl-3">
                            <label class="form-label small mb-1" for="hospital-reference-operation-discount-mode"><span class="visually-hidden">Operation </span>Discount rule</label>
                            <select class="{{ inputClass('data.reference_operation_discount_mode', 'form-select') }}" id="hospital-reference-operation-discount-mode" name="data[reference_operation_discount_mode]" required {{ inputAttrs('data.reference_operation_discount_mode', 'hospital-reference-operation-discount-mode', 'hospital-reference-discount-rules-help') }}>
                                @foreach (config('settings.reference_discount_modes') as $mode => $label)
                                    <option value="{{ $mode }}" {{ getSelected($mode, 'data.reference_operation_discount_mode', null, $values['data']['reference_operation_discount_mode']) }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('data.reference_operation_discount_mode')<div class="invalid-feedback d-block" id="hospital-reference-operation-discount-mode-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6 col-xl-3">
                            <label class="form-label small mb-1" for="hospital-reference-operation-due-mode"><span class="visually-hidden">Operation </span>Due rule</label>
                            <select class="{{ inputClass('data.reference_operation_due_mode', 'form-select') }}" id="hospital-reference-operation-due-mode" name="data[reference_operation_due_mode]" required {{ inputAttrs('data.reference_operation_due_mode', 'hospital-reference-operation-due-mode', 'hospital-reference-due-rules-help') }}>
                                @foreach (config('settings.reference_due_modes') as $mode => $label)
                                    <option value="{{ $mode }}" {{ getSelected($mode, 'data.reference_operation_due_mode', null, $values['data']['reference_operation_due_mode']) }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('data.reference_operation_due_mode')<div class="invalid-feedback d-block" id="hospital-reference-operation-due-mode-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <p class="small text-body-secondary mt-2 mb-0">New rules apply to new invoices.</p>
                    <details class="mt-2">
                        <summary class="small fw-semibold">Help</summary>
                        <div class="small text-body-secondary mt-2">
                            <p class="mb-2" id="hospital-reference-id-scope-help">ID changes apply to new References.</p>
                            <p class="mb-2" id="hospital-reference-pattern-help">Use one {SEQ} or {SEQ:1}–{SEQ:12}. Optional: {YYYY}, {MM}, {DD}, {BRANCH}. Branch patterns require {BRANCH}.</p>
                            <p class="mb-2" id="hospital-reference-defaults-help">Defaults prefill new References, including hidden rates. Turning defaults off starts new rates at zero.</p>
                            <p class="mb-2" id="hospital-reference-percent-help">Rates: 0–100%, with up to two decimal places.</p>
                            <p class="mb-2" id="hospital-reference-percentages-help">The main switch enables percentage fields; each service switch shows its own field. Hidden saved rates stay unchanged.</p>
                            <p class="mb-2" id="hospital-reference-discount-rules-help">Before discount: bill × rate. After discount: net bill × rate. Discount from commission: bill × rate − all discounts, minimum zero. Investigation extra discounts are included.</p>
                            <p class="mb-0" id="hospital-reference-due-rules-help">Full payment waits until due is zero. Due from commission subtracts outstanding due. As patient pays releases commission in proportion to receipts. Collections and refunds update availability; saved invoice rates and rules stay fixed.</p>
                        </div>
                    </details>
                </section>

            @elseif ($activeHospitalSection === 'appointment')
                <section aria-labelledby="hospital-appointment-heading">
                    <h3 class="h6 mb-3" id="hospital-appointment-heading">Appointment</h3>
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label class="form-label" for="hospital-appointment-serial-scope">Serial numbering <span class="text-danger" aria-hidden="true">*</span></label>
                            <select class="{{ inputClass('data.appointment_serial_scope', 'form-select') }}" id="hospital-appointment-serial-scope" name="data[appointment_serial_scope]" required {{ inputAttrs('data.appointment_serial_scope', 'hospital-appointment-serial-scope') }}>
                                <option value="tenant" {{ getSelected('tenant', 'data.appointment_serial_scope', null, $values['data']['appointment_serial_scope']) }}>Tenant-wide</option>
                                <option value="branch" {{ getSelected('branch', 'data.appointment_serial_scope', null, $values['data']['appointment_serial_scope']) }}>Separate by branch</option>
                                <option value="doctor" {{ getSelected('doctor', 'data.appointment_serial_scope', null, $values['data']['appointment_serial_scope']) }}>Separate by doctor</option>
                            </select>
                            @error('data.appointment_serial_scope')<div class="invalid-feedback d-block" id="hospital-appointment-serial-scope-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="hospital-appointment-serial-reset">Serial reset <span class="text-danger" aria-hidden="true">*</span></label>
                            <select class="{{ inputClass('data.appointment_serial_reset', 'form-select') }}" id="hospital-appointment-serial-reset" name="data[appointment_serial_reset]" required {{ inputAttrs('data.appointment_serial_reset', 'hospital-appointment-serial-reset') }}>
                                <option value="never" {{ getSelected('never', 'data.appointment_serial_reset', null, $values['data']['appointment_serial_reset']) }}>Never</option>
                                <option value="year" {{ getSelected('year', 'data.appointment_serial_reset', null, $values['data']['appointment_serial_reset']) }}>Yearly</option>
                                <option value="month" {{ getSelected('month', 'data.appointment_serial_reset', null, $values['data']['appointment_serial_reset']) }}>Monthly</option>
                                <option value="day" {{ getSelected('day', 'data.appointment_serial_reset', null, $values['data']['appointment_serial_reset']) }}>Daily</option>
                            </select>
                            @error('data.appointment_serial_reset')<div class="invalid-feedback d-block" id="hospital-appointment-serial-reset-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="hospital-appointment-serial-pattern">Serial pattern <span class="text-danger" aria-hidden="true">*</span></label>
                            <input class="{{ inputClass('data.appointment_serial_pattern') }}" id="hospital-appointment-serial-pattern" name="data[appointment_serial_pattern]" type="text" value="{{ getValue('data.appointment_serial_pattern', null, $values['data']['appointment_serial_pattern']) }}" maxlength="80" placeholder="{SEQ:3}" required {{ inputAttrs('data.appointment_serial_pattern', 'hospital-appointment-serial-pattern', 'hospital-number-pattern-help') }}>
                            @error('data.appointment_serial_pattern')<div class="invalid-feedback d-block" id="hospital-appointment-serial-pattern-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="row g-2 mt-2">
                        <div class="col-md-3">
                            <label class="form-label" for="hospital-appointment-invoice-scope">Invoice numbering <span class="text-danger" aria-hidden="true">*</span></label>
                            <select class="{{ inputClass('data.appointment_invoice_scope', 'form-select') }}" id="hospital-appointment-invoice-scope" name="data[appointment_invoice_scope]" required {{ inputAttrs('data.appointment_invoice_scope', 'hospital-appointment-invoice-scope') }}>
                                <option value="tenant" {{ getSelected('tenant', 'data.appointment_invoice_scope', null, $values['data']['appointment_invoice_scope']) }}>Tenant-wide</option>
                                <option value="branch" {{ getSelected('branch', 'data.appointment_invoice_scope', null, $values['data']['appointment_invoice_scope']) }}>Separate by branch</option>
                            </select>
                            @error('data.appointment_invoice_scope')<div class="invalid-feedback d-block" id="hospital-appointment-invoice-scope-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="hospital-appointment-invoice-reset">Invoice reset <span class="text-danger" aria-hidden="true">*</span></label>
                            <select class="{{ inputClass('data.appointment_invoice_reset', 'form-select') }}" id="hospital-appointment-invoice-reset" name="data[appointment_invoice_reset]" required {{ inputAttrs('data.appointment_invoice_reset', 'hospital-appointment-invoice-reset') }}>
                                <option value="never" {{ getSelected('never', 'data.appointment_invoice_reset', null, $values['data']['appointment_invoice_reset']) }}>Never</option>
                                <option value="year" {{ getSelected('year', 'data.appointment_invoice_reset', null, $values['data']['appointment_invoice_reset']) }}>Yearly</option>
                                <option value="month" {{ getSelected('month', 'data.appointment_invoice_reset', null, $values['data']['appointment_invoice_reset']) }}>Monthly</option>
                                <option value="day" {{ getSelected('day', 'data.appointment_invoice_reset', null, $values['data']['appointment_invoice_reset']) }}>Daily</option>
                            </select>
                            @error('data.appointment_invoice_reset')<div class="invalid-feedback d-block" id="hospital-appointment-invoice-reset-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="hospital-appointment-invoice-pattern">Invoice pattern <span class="text-danger" aria-hidden="true">*</span></label>
                            <input class="{{ inputClass('data.appointment_invoice_pattern') }}" id="hospital-appointment-invoice-pattern" name="data[appointment_invoice_pattern]" type="text" value="{{ getValue('data.appointment_invoice_pattern', null, $values['data']['appointment_invoice_pattern']) }}" maxlength="80" placeholder="APT-B{BRANCH}-{SEQ:6}" required {{ inputAttrs('data.appointment_invoice_pattern', 'hospital-appointment-invoice-pattern', 'hospital-number-pattern-help') }}>
                            @error('data.appointment_invoice_pattern')<div class="invalid-feedback d-block" id="hospital-appointment-invoice-pattern-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </section>

            @elseif ($activeHospitalSection === 'consultation')
                <section aria-labelledby="hospital-consultation-heading">
                    <h3 class="h6 mb-3" id="hospital-consultation-heading">Consultation</h3>
                    <div class="form-check form-switch mb-3">
                        <input name="data[consultation_reference_enabled]" type="hidden" value="0">
                        <input class="{{ inputClass('data.consultation_reference_enabled', 'form-check-input') }}" id="hospital-consultation-reference-enabled" name="data[consultation_reference_enabled]" type="checkbox" role="switch" value="1" {{ getChecked(1, 'data.consultation_reference_enabled', null, $values['data']['consultation_reference_enabled']) }} {{ inputAttrs('data.consultation_reference_enabled', 'hospital-consultation-reference-enabled') }}>
                        <label class="form-check-label" for="hospital-consultation-reference-enabled">Show Reference in the Consultation form</label>
                        @error('data.consultation_reference_enabled')<div class="invalid-feedback d-block" id="hospital-consultation-reference-enabled-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-check mb-2">
                        <input name="data[consultation_serial_enabled]" type="hidden" value="0">
                        <input class="{{ inputClass('data.consultation_serial_enabled', 'form-check-input') }}" id="hospital-consultation-serial-enabled" name="data[consultation_serial_enabled]" type="checkbox" value="1" {{ getChecked(1, 'data.consultation_serial_enabled', null, $values['data']['consultation_serial_enabled']) }} {{ inputAttrs('data.consultation_serial_enabled', 'hospital-consultation-serial-enabled') }}>
                        <label class="form-check-label" for="hospital-consultation-serial-enabled">Generate consultation serial numbers</label>
                        @error('data.consultation_serial_enabled')<div class="invalid-feedback d-block" id="hospital-consultation-serial-enabled-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label class="form-label" for="hospital-consultation-serial-scope">Serial numbering <span class="text-danger" aria-hidden="true">*</span></label>
                            <select class="{{ inputClass('data.consultation_serial_scope', 'form-select') }}" id="hospital-consultation-serial-scope" name="data[consultation_serial_scope]" required {{ inputAttrs('data.consultation_serial_scope', 'hospital-consultation-serial-scope') }}>
                                <option value="tenant" {{ getSelected('tenant', 'data.consultation_serial_scope', null, $values['data']['consultation_serial_scope']) }}>Tenant-wide</option>
                                <option value="branch" {{ getSelected('branch', 'data.consultation_serial_scope', null, $values['data']['consultation_serial_scope']) }}>Separate by branch</option>
                                <option value="doctor" {{ getSelected('doctor', 'data.consultation_serial_scope', null, $values['data']['consultation_serial_scope']) }}>Separate by doctor</option>
                            </select>
                            @error('data.consultation_serial_scope')<div class="invalid-feedback d-block" id="hospital-consultation-serial-scope-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="hospital-consultation-serial-reset">Serial reset <span class="text-danger" aria-hidden="true">*</span></label>
                            <select class="{{ inputClass('data.consultation_serial_reset', 'form-select') }}" id="hospital-consultation-serial-reset" name="data[consultation_serial_reset]" required {{ inputAttrs('data.consultation_serial_reset', 'hospital-consultation-serial-reset') }}>
                                <option value="never" {{ getSelected('never', 'data.consultation_serial_reset', null, $values['data']['consultation_serial_reset']) }}>Never</option>
                                <option value="year" {{ getSelected('year', 'data.consultation_serial_reset', null, $values['data']['consultation_serial_reset']) }}>Yearly</option>
                                <option value="month" {{ getSelected('month', 'data.consultation_serial_reset', null, $values['data']['consultation_serial_reset']) }}>Monthly</option>
                                <option value="day" {{ getSelected('day', 'data.consultation_serial_reset', null, $values['data']['consultation_serial_reset']) }}>Daily</option>
                            </select>
                            @error('data.consultation_serial_reset')<div class="invalid-feedback d-block" id="hospital-consultation-serial-reset-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="hospital-consultation-serial-pattern">Serial pattern <span class="text-danger" aria-hidden="true">*</span></label>
                            <input class="{{ inputClass('data.consultation_serial_pattern') }}" id="hospital-consultation-serial-pattern" name="data[consultation_serial_pattern]" type="text" value="{{ getValue('data.consultation_serial_pattern', null, $values['data']['consultation_serial_pattern']) }}" maxlength="80" placeholder="{SEQ:3}" required {{ inputAttrs('data.consultation_serial_pattern', 'hospital-consultation-serial-pattern', 'hospital-number-pattern-help') }}>
                            @error('data.consultation_serial_pattern')<div class="invalid-feedback d-block" id="hospital-consultation-serial-pattern-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-check mt-3 mb-2">
                        <input name="data[consultation_invoice_enabled]" type="hidden" value="0">
                        <input class="{{ inputClass('data.consultation_invoice_enabled', 'form-check-input') }}" id="hospital-consultation-invoice-enabled" name="data[consultation_invoice_enabled]" type="checkbox" value="1" {{ getChecked(1, 'data.consultation_invoice_enabled', null, $values['data']['consultation_invoice_enabled']) }} {{ inputAttrs('data.consultation_invoice_enabled', 'hospital-consultation-invoice-enabled') }}>
                        <label class="form-check-label" for="hospital-consultation-invoice-enabled">Generate consultation invoice numbers</label>
                        @error('data.consultation_invoice_enabled')<div class="invalid-feedback d-block" id="hospital-consultation-invoice-enabled-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label class="form-label" for="hospital-consultation-invoice-scope">Invoice numbering <span class="text-danger" aria-hidden="true">*</span></label>
                            <select class="{{ inputClass('data.consultation_invoice_scope', 'form-select') }}" id="hospital-consultation-invoice-scope" name="data[consultation_invoice_scope]" required {{ inputAttrs('data.consultation_invoice_scope', 'hospital-consultation-invoice-scope') }}>
                                <option value="tenant" {{ getSelected('tenant', 'data.consultation_invoice_scope', null, $values['data']['consultation_invoice_scope']) }}>Tenant-wide</option>
                                <option value="branch" {{ getSelected('branch', 'data.consultation_invoice_scope', null, $values['data']['consultation_invoice_scope']) }}>Separate by branch</option>
                            </select>
                            @error('data.consultation_invoice_scope')<div class="invalid-feedback d-block" id="hospital-consultation-invoice-scope-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="hospital-consultation-invoice-reset">Invoice reset <span class="text-danger" aria-hidden="true">*</span></label>
                            <select class="{{ inputClass('data.consultation_invoice_reset', 'form-select') }}" id="hospital-consultation-invoice-reset" name="data[consultation_invoice_reset]" required {{ inputAttrs('data.consultation_invoice_reset', 'hospital-consultation-invoice-reset') }}>
                                <option value="never" {{ getSelected('never', 'data.consultation_invoice_reset', null, $values['data']['consultation_invoice_reset']) }}>Never</option>
                                <option value="year" {{ getSelected('year', 'data.consultation_invoice_reset', null, $values['data']['consultation_invoice_reset']) }}>Yearly</option>
                                <option value="month" {{ getSelected('month', 'data.consultation_invoice_reset', null, $values['data']['consultation_invoice_reset']) }}>Monthly</option>
                                <option value="day" {{ getSelected('day', 'data.consultation_invoice_reset', null, $values['data']['consultation_invoice_reset']) }}>Daily</option>
                            </select>
                            @error('data.consultation_invoice_reset')<div class="invalid-feedback d-block" id="hospital-consultation-invoice-reset-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="hospital-consultation-invoice-pattern">Invoice pattern <span class="text-danger" aria-hidden="true">*</span></label>
                            <input class="{{ inputClass('data.consultation_invoice_pattern') }}" id="hospital-consultation-invoice-pattern" name="data[consultation_invoice_pattern]" type="text" value="{{ getValue('data.consultation_invoice_pattern', null, $values['data']['consultation_invoice_pattern']) }}" maxlength="80" placeholder="CON-B{BRANCH}-{SEQ:6}" required {{ inputAttrs('data.consultation_invoice_pattern', 'hospital-consultation-invoice-pattern', 'hospital-number-pattern-help') }}>
                            @error('data.consultation_invoice_pattern')<div class="invalid-feedback d-block" id="hospital-consultation-invoice-pattern-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </section>

            @elseif ($activeHospitalSection === 'investigation')
                <section aria-labelledby="hospital-investigation-heading">
                    <h3 class="h6 mb-3" id="hospital-investigation-heading">Investigation</h3>
                    <div class="form-check form-switch mb-3">
                        <input name="data[investigation_reference_enabled]" type="hidden" value="0">
                        <input class="{{ inputClass('data.investigation_reference_enabled', 'form-check-input') }}" id="hospital-investigation-reference-enabled" name="data[investigation_reference_enabled]" type="checkbox" role="switch" value="1" {{ getChecked(1, 'data.investigation_reference_enabled', null, $values['data']['investigation_reference_enabled']) }} {{ inputAttrs('data.investigation_reference_enabled', 'hospital-investigation-reference-enabled') }}>
                        <label class="form-check-label" for="hospital-investigation-reference-enabled">Show Reference in the Patient Investigation form</label>
                        @error('data.investigation_reference_enabled')<div class="invalid-feedback d-block" id="hospital-investigation-reference-enabled-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label class="form-label" for="hospital-investigation-invoice-scope">Invoice numbering <span class="text-danger" aria-hidden="true">*</span></label>
                            <select class="{{ inputClass('data.investigation_invoice_scope', 'form-select') }}" id="hospital-investigation-invoice-scope" name="data[investigation_invoice_scope]" required {{ inputAttrs('data.investigation_invoice_scope', 'hospital-investigation-invoice-scope') }}>
                                <option value="tenant" {{ getSelected('tenant', 'data.investigation_invoice_scope', null, $values['data']['investigation_invoice_scope']) }}>Tenant-wide</option>
                                <option value="branch" {{ getSelected('branch', 'data.investigation_invoice_scope', null, $values['data']['investigation_invoice_scope']) }}>Separate by branch</option>
                            </select>
                            @error('data.investigation_invoice_scope')<div class="invalid-feedback d-block" id="hospital-investigation-invoice-scope-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="hospital-investigation-invoice-reset">Invoice reset <span class="text-danger" aria-hidden="true">*</span></label>
                            <select class="{{ inputClass('data.investigation_invoice_reset', 'form-select') }}" id="hospital-investigation-invoice-reset" name="data[investigation_invoice_reset]" required {{ inputAttrs('data.investigation_invoice_reset', 'hospital-investigation-invoice-reset') }}>
                                <option value="never" {{ getSelected('never', 'data.investigation_invoice_reset', null, $values['data']['investigation_invoice_reset']) }}>Never</option>
                                <option value="year" {{ getSelected('year', 'data.investigation_invoice_reset', null, $values['data']['investigation_invoice_reset']) }}>Yearly</option>
                                <option value="month" {{ getSelected('month', 'data.investigation_invoice_reset', null, $values['data']['investigation_invoice_reset']) }}>Monthly</option>
                                <option value="day" {{ getSelected('day', 'data.investigation_invoice_reset', null, $values['data']['investigation_invoice_reset']) }}>Daily</option>
                            </select>
                            @error('data.investigation_invoice_reset')<div class="invalid-feedback d-block" id="hospital-investigation-invoice-reset-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="hospital-investigation-invoice-pattern">Invoice pattern <span class="text-danger" aria-hidden="true">*</span></label>
                            <input class="{{ inputClass('data.investigation_invoice_pattern') }}" id="hospital-investigation-invoice-pattern" name="data[investigation_invoice_pattern]" type="text" value="{{ getValue('data.investigation_invoice_pattern', null, $values['data']['investigation_invoice_pattern']) }}" maxlength="80" placeholder="INV-B{BRANCH}-{SEQ:6}" required {{ inputAttrs('data.investigation_invoice_pattern', 'hospital-investigation-invoice-pattern', 'hospital-number-pattern-help') }}>
                            @error('data.investigation_invoice_pattern')<div class="invalid-feedback d-block" id="hospital-investigation-invoice-pattern-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </section>

            @elseif ($activeHospitalSection === 'operation')
                <section aria-labelledby="hospital-operation-heading">
                    <h3 class="h6 mb-3" id="hospital-operation-heading">Operation</h3>
                    <div class="form-check form-switch mb-3">
                        <input name="data[operation_reference_enabled]" type="hidden" value="0">
                        <input class="{{ inputClass('data.operation_reference_enabled', 'form-check-input') }}" id="hospital-operation-reference-enabled" name="data[operation_reference_enabled]" type="checkbox" role="switch" value="1" {{ getChecked(1, 'data.operation_reference_enabled', null, $values['data']['operation_reference_enabled']) }} {{ inputAttrs('data.operation_reference_enabled', 'hospital-operation-reference-enabled') }}>
                        <label class="form-check-label" for="hospital-operation-reference-enabled">Show Reference in the Patient Operation form</label>
                        @error('data.operation_reference_enabled')<div class="invalid-feedback d-block" id="hospital-operation-reference-enabled-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label class="form-label" for="hospital-operation-invoice-scope">Invoice numbering <span class="text-danger" aria-hidden="true">*</span></label>
                            <select class="{{ inputClass('data.operation_invoice_scope', 'form-select') }}" id="hospital-operation-invoice-scope" name="data[operation_invoice_scope]" required {{ inputAttrs('data.operation_invoice_scope', 'hospital-operation-invoice-scope') }}>
                                <option value="tenant" {{ getSelected('tenant', 'data.operation_invoice_scope', null, $values['data']['operation_invoice_scope']) }}>Tenant-wide</option>
                                <option value="branch" {{ getSelected('branch', 'data.operation_invoice_scope', null, $values['data']['operation_invoice_scope']) }}>Separate by branch</option>
                            </select>
                            @error('data.operation_invoice_scope')<div class="invalid-feedback d-block" id="hospital-operation-invoice-scope-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="hospital-operation-invoice-reset">Invoice reset <span class="text-danger" aria-hidden="true">*</span></label>
                            <select class="{{ inputClass('data.operation_invoice_reset', 'form-select') }}" id="hospital-operation-invoice-reset" name="data[operation_invoice_reset]" required {{ inputAttrs('data.operation_invoice_reset', 'hospital-operation-invoice-reset') }}>
                                <option value="never" {{ getSelected('never', 'data.operation_invoice_reset', null, $values['data']['operation_invoice_reset']) }}>Never</option>
                                <option value="year" {{ getSelected('year', 'data.operation_invoice_reset', null, $values['data']['operation_invoice_reset']) }}>Yearly</option>
                                <option value="month" {{ getSelected('month', 'data.operation_invoice_reset', null, $values['data']['operation_invoice_reset']) }}>Monthly</option>
                                <option value="day" {{ getSelected('day', 'data.operation_invoice_reset', null, $values['data']['operation_invoice_reset']) }}>Daily</option>
                            </select>
                            @error('data.operation_invoice_reset')<div class="invalid-feedback d-block" id="hospital-operation-invoice-reset-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="hospital-operation-invoice-pattern">Invoice pattern <span class="text-danger" aria-hidden="true">*</span></label>
                            <input class="{{ inputClass('data.operation_invoice_pattern') }}" id="hospital-operation-invoice-pattern" name="data[operation_invoice_pattern]" type="text" value="{{ getValue('data.operation_invoice_pattern', null, $values['data']['operation_invoice_pattern']) }}" maxlength="80" placeholder="OP-B{BRANCH}-{SEQ:6}" required {{ inputAttrs('data.operation_invoice_pattern', 'hospital-operation-invoice-pattern', 'hospital-number-pattern-help') }}>
                            @error('data.operation_invoice_pattern')<div class="invalid-feedback d-block" id="hospital-operation-invoice-pattern-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </section>
            @elseif ($activeHospitalSection === 'refund')
                <section aria-labelledby="hospital-refund-heading">
                    <h3 class="h6 mb-3" id="hospital-refund-heading">Refund</h3>
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label class="form-label" for="hospital-refund-invoice-scope">Invoice numbering <span class="text-danger" aria-hidden="true">*</span></label>
                            <select class="{{ inputClass('data.refund_invoice_scope', 'form-select') }}" id="hospital-refund-invoice-scope" name="data[refund_invoice_scope]" required {{ inputAttrs('data.refund_invoice_scope', 'hospital-refund-invoice-scope') }}>
                                <option value="tenant" {{ getSelected('tenant', 'data.refund_invoice_scope', null, $values['data']['refund_invoice_scope']) }}>Tenant-wide</option>
                                <option value="branch" {{ getSelected('branch', 'data.refund_invoice_scope', null, $values['data']['refund_invoice_scope']) }}>Separate by branch</option>
                            </select>
                            @error('data.refund_invoice_scope')<div class="invalid-feedback d-block" id="hospital-refund-invoice-scope-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="hospital-refund-invoice-reset">Invoice reset <span class="text-danger" aria-hidden="true">*</span></label>
                            <select class="{{ inputClass('data.refund_invoice_reset', 'form-select') }}" id="hospital-refund-invoice-reset" name="data[refund_invoice_reset]" required {{ inputAttrs('data.refund_invoice_reset', 'hospital-refund-invoice-reset') }}>
                                <option value="never" {{ getSelected('never', 'data.refund_invoice_reset', null, $values['data']['refund_invoice_reset']) }}>Never</option>
                                <option value="year" {{ getSelected('year', 'data.refund_invoice_reset', null, $values['data']['refund_invoice_reset']) }}>Yearly</option>
                                <option value="month" {{ getSelected('month', 'data.refund_invoice_reset', null, $values['data']['refund_invoice_reset']) }}>Monthly</option>
                                <option value="day" {{ getSelected('day', 'data.refund_invoice_reset', null, $values['data']['refund_invoice_reset']) }}>Daily</option>
                            </select>
                            @error('data.refund_invoice_reset')<div class="invalid-feedback d-block" id="hospital-refund-invoice-reset-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="hospital-refund-invoice-pattern">Invoice pattern <span class="text-danger" aria-hidden="true">*</span></label>
                            <input class="{{ inputClass('data.refund_invoice_pattern') }}" id="hospital-refund-invoice-pattern" name="data[refund_invoice_pattern]" type="text" value="{{ getValue('data.refund_invoice_pattern', null, $values['data']['refund_invoice_pattern']) }}" maxlength="80" placeholder="REF-{SEQ:6}" required {{ inputAttrs('data.refund_invoice_pattern', 'hospital-refund-invoice-pattern', 'hospital-number-pattern-help') }}>
                            @error('data.refund_invoice_pattern')<div class="invalid-feedback d-block" id="hospital-refund-invoice-pattern-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </section>
            @elseif ($activeHospitalSection === 'due_collection')
                <section aria-labelledby="hospital-due-collection-heading">
                    <h3 class="h6 mb-3" id="hospital-due-collection-heading">Due Collection</h3>
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label class="form-label" for="hospital-due-collection-invoice-scope">Invoice numbering <span class="text-danger" aria-hidden="true">*</span></label>
                            <select class="{{ inputClass('data.due_collection_invoice_scope', 'form-select') }}" id="hospital-due-collection-invoice-scope" name="data[due_collection_invoice_scope]" required {{ inputAttrs('data.due_collection_invoice_scope', 'hospital-due-collection-invoice-scope') }}>
                                <option value="tenant" {{ getSelected('tenant', 'data.due_collection_invoice_scope', null, $values['data']['due_collection_invoice_scope']) }}>Tenant-wide</option>
                                <option value="branch" {{ getSelected('branch', 'data.due_collection_invoice_scope', null, $values['data']['due_collection_invoice_scope']) }}>Separate by branch</option>
                            </select>
                            @error('data.due_collection_invoice_scope')<div class="invalid-feedback d-block" id="hospital-due-collection-invoice-scope-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="hospital-due-collection-invoice-reset">Invoice reset <span class="text-danger" aria-hidden="true">*</span></label>
                            <select class="{{ inputClass('data.due_collection_invoice_reset', 'form-select') }}" id="hospital-due-collection-invoice-reset" name="data[due_collection_invoice_reset]" required {{ inputAttrs('data.due_collection_invoice_reset', 'hospital-due-collection-invoice-reset') }}>
                                <option value="never" {{ getSelected('never', 'data.due_collection_invoice_reset', null, $values['data']['due_collection_invoice_reset']) }}>Never</option>
                                <option value="year" {{ getSelected('year', 'data.due_collection_invoice_reset', null, $values['data']['due_collection_invoice_reset']) }}>Yearly</option>
                                <option value="month" {{ getSelected('month', 'data.due_collection_invoice_reset', null, $values['data']['due_collection_invoice_reset']) }}>Monthly</option>
                                <option value="day" {{ getSelected('day', 'data.due_collection_invoice_reset', null, $values['data']['due_collection_invoice_reset']) }}>Daily</option>
                            </select>
                            @error('data.due_collection_invoice_reset')<div class="invalid-feedback d-block" id="hospital-due-collection-invoice-reset-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="hospital-due-collection-invoice-pattern">Invoice pattern <span class="text-danger" aria-hidden="true">*</span></label>
                            <input class="{{ inputClass('data.due_collection_invoice_pattern') }}" id="hospital-due-collection-invoice-pattern" name="data[due_collection_invoice_pattern]" type="text" value="{{ getValue('data.due_collection_invoice_pattern', null, $values['data']['due_collection_invoice_pattern']) }}" maxlength="80" placeholder="DC-{SEQ:6}" required {{ inputAttrs('data.due_collection_invoice_pattern', 'hospital-due-collection-invoice-pattern', 'hospital-number-pattern-help') }}>
                            @error('data.due_collection_invoice_pattern')<div class="invalid-feedback d-block" id="hospital-due-collection-invoice-pattern-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </section>
            @endif
        @elseif ($setting->group === 'email')
            <div class="form-check mb-3">
                <input name="data[enabled]" type="hidden" value="0">
                <input class="{{ inputClass('data.enabled', 'form-check-input') }}" id="email-enabled" name="data[enabled]" type="checkbox" value="1" {{ getChecked(1, 'data.enabled', null, $values['data']['enabled'] ?? false) }} {{ inputAttrs('data.enabled', 'email-enabled', 'email-enabled-help') }}>
                <label class="form-check-label" for="email-enabled">Use these email settings</label>
                @error('data.enabled')<div class="invalid-feedback d-block" id="email-enabled-error">{{ $message }}</div>@enderror
            </div>
            <p class="app-form-section-note" id="email-enabled-help">When disabled, emails use the deployment mailer.</p>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="email-host">SMTP host</label>
                    <input class="{{ inputClass('data.host') }}" id="email-host" name="data[host]" type="text" value="{{ getValue('data.host', null, $values['data']['host'] ?? '') }}" maxlength="255" autocomplete="off" {{ inputAttrs('data.host', 'email-host') }}>
                    @error('data.host')<div class="invalid-feedback d-block" id="email-host-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="email-port">Port</label>
                    <input class="{{ inputClass('data.port') }}" id="email-port" name="data[port]" type="text" inputmode="numeric" pattern="[0-9]{1,5}" value="{{ getValue('data.port', null, $values['data']['port'] ?? 587) }}" maxlength="5" {{ inputAttrs('data.port', 'email-port') }}>
                    @error('data.port')<div class="invalid-feedback d-block" id="email-port-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="email-encryption">Encryption</label>
                    <select class="{{ inputClass('data.encryption', 'form-select') }}" id="email-encryption" name="data[encryption]" {{ inputAttrs('data.encryption', 'email-encryption') }}>
                        <option value="tls" {{ getSelected('tls', 'data.encryption', null, $values['data']['encryption'] ?? 'tls') }}>STARTTLS</option>
                        <option value="ssl" {{ getSelected('ssl', 'data.encryption', null, $values['data']['encryption'] ?? 'tls') }}>SSL/TLS</option>
                        <option value="none" {{ getSelected('none', 'data.encryption', null, $values['data']['encryption'] ?? 'tls') }}>None</option>
                    </select>
                    @error('data.encryption')<div class="invalid-feedback d-block" id="email-encryption-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="email-from-address">From email address</label>
                    <input class="{{ inputClass('data.from_address') }}" id="email-from-address" name="data[from_address]" type="email" value="{{ getValue('data.from_address', null, $values['data']['from_address'] ?? '') }}" maxlength="255" autocomplete="email" {{ inputAttrs('data.from_address', 'email-from-address') }}>
                    @error('data.from_address')<div class="invalid-feedback d-block" id="email-from-address-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="email-from-name">From name</label>
                    <input class="{{ inputClass('data.from_name') }}" id="email-from-name" name="data[from_name]" type="text" value="{{ getValue('data.from_name', null, $values['data']['from_name'] ?? '') }}" maxlength="255" autocomplete="organization" {{ inputAttrs('data.from_name', 'email-from-name') }}>
                    @error('data.from_name')<div class="invalid-feedback d-block" id="email-from-name-error">{{ $message }}</div>@enderror
                </div>
            </div>
        @elseif ($setting->group === 'sms')
            <div class="form-check mb-3">
                <input name="data[enabled]" type="hidden" value="0">
                <input class="{{ inputClass('data.enabled', 'form-check-input') }}" id="sms-enabled" name="data[enabled]" type="checkbox" value="1" {{ getChecked(1, 'data.enabled', null, $values['data']['enabled'] ?? false) }} {{ inputAttrs('data.enabled', 'sms-enabled', 'sms-enabled-help') }}>
                <label class="form-check-label" for="sms-enabled">Enable SMS</label>
                @error('data.enabled')<div class="invalid-feedback d-block" id="sms-enabled-error">{{ $message }}</div>@enderror
            </div>
            <p class="app-form-section-note" id="sms-enabled-help">Provider API mapping is required before sending SMS.</p>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="sms-provider">Provider</label>
                    <input class="{{ inputClass('data.provider') }}" id="sms-provider" name="data[provider]" type="text" value="{{ getValue('data.provider', null, $values['data']['provider'] ?? '') }}" maxlength="100" {{ inputAttrs('data.provider', 'sms-provider') }}>
                    @error('data.provider')<div class="invalid-feedback d-block" id="sms-provider-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="sms-sender-id">Sender ID</label>
                    <input class="{{ inputClass('data.sender_id') }}" id="sms-sender-id" name="data[sender_id]" type="text" value="{{ getValue('data.sender_id', null, $values['data']['sender_id'] ?? '') }}" maxlength="100" {{ inputAttrs('data.sender_id', 'sms-sender-id') }}>
                    @error('data.sender_id')<div class="invalid-feedback d-block" id="sms-sender-id-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label" for="sms-api-url">API URL</label>
                    <input class="{{ inputClass('data.api_url') }}" id="sms-api-url" name="data[api_url]" type="url" value="{{ getValue('data.api_url', null, $values['data']['api_url'] ?? '') }}" maxlength="2048" autocomplete="off" {{ inputAttrs('data.api_url', 'sms-api-url') }}>
                    @error('data.api_url')<div class="invalid-feedback d-block" id="sms-api-url-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="sms-method">Request method</label>
                    <select class="{{ inputClass('data.method', 'form-select') }}" id="sms-method" name="data[method]" {{ inputAttrs('data.method', 'sms-method') }}>
                        <option value="POST" {{ getSelected('POST', 'data.method', null, $values['data']['method'] ?? 'POST') }}>POST</option>
                        <option value="GET" {{ getSelected('GET', 'data.method', null, $values['data']['method'] ?? 'POST') }}>GET</option>
                    </select>
                    @error('data.method')<div class="invalid-feedback d-block" id="sms-method-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="sms-body-format">Request format</label>
                    <select class="{{ inputClass('data.body_format', 'form-select') }}" id="sms-body-format" name="data[body_format]" {{ inputAttrs('data.body_format', 'sms-body-format') }}>
                        <option value="json" {{ getSelected('json', 'data.body_format', null, $values['data']['body_format'] ?? 'json') }}>JSON</option>
                        <option value="form" {{ getSelected('form', 'data.body_format', null, $values['data']['body_format'] ?? 'json') }}>Form encoded</option>
                    </select>
                    @error('data.body_format')<div class="invalid-feedback d-block" id="sms-body-format-error">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mt-3">
                <p class="small text-body-secondary">Use your provider's field names in the encrypted Parameters and Headers templates below. Available placeholders: <code>{to}</code>, <code>{message}</code>, <code>{sender_id}</code>, <code>{api_key}</code>, <code>{username}</code>, <code>{password}</code>.</p>
                <details>
                    <summary class="small fw-semibold">Request template examples</summary>
                    <p class="small mb-1 mt-3">Parameters</p>
                    <pre class="small border rounded p-3 mb-3"><code>{"number":"{to}","message":"{message}","api_key":"{api_key}","senderid":"{sender_id}"}</code></pre>
                    <p class="small mb-1">Headers</p>
                    <pre class="small border rounded p-3 mb-0"><code>{"Authorization":"Bearer {api_key}"}</code></pre>
                </details>
            </div>
        @elseif ($setting->group === 'manual')
            <div>
                <label class="form-label" for="manual-instructions">Payment instructions</label>
                <textarea
                    class="{{ inputClass('data.instructions') }}"
                    id="manual-instructions"
                    name="data[instructions]"
                    rows="4"
                    maxlength="4000"
                    {{ inputAttrs('data.instructions', 'manual-instructions', 'manual-instructions-help') }}
                >{{ getValue('data.instructions', null, $values['data']['instructions']) }}</textarea>
                <div class="form-text" id="manual-instructions-help">
                    Tell customers where to send payment and which reference to submit for review.
                </div>
                @error('data.instructions')
                    <div class="invalid-feedback d-block" id="manual-instructions-error">{{ $message }}</div>
                @enderror
            </div>
        @endif

        @if ($credentialFields !== [])
            <p class="small text-body-secondary mt-4">Enter only credentials you want to replace. Existing values stay hidden.</p>
            @error('credentials')<div class="invalid-feedback d-block mb-3">{{ $message }}</div>@enderror
            <div class="row g-3">
                @foreach ($credentialFields as $field => $definition)
                    <div class="{{ ($definition['multiline'] ?? false) ? 'col-12' : 'col-md-6' }}">
                        <label class="form-label" for="credential-{{ $setting->group }}-{{ $field }}">{{ $definition['label'] }}</label>
                        @if ($definition['multiline'] ?? false)
                            <textarea class="{{ inputClass('credentials.'.$field) }}" id="credential-{{ $setting->group }}-{{ $field }}" name="credentials[{{ $field }}]" rows="4" maxlength="{{ $definition['max'] }}" autocomplete="off" spellcheck="false" {{ inputAttrs('credentials.'.$field, 'credential-'.$setting->group.'-'.$field, 'credential-'.$setting->group.'-'.$field.'-help') }}></textarea>
                        @else
                            <div class="app-input-wrap">
                                <input class="{{ inputClass('credentials.'.$field) }} app-password-input" id="credential-{{ $setting->group }}-{{ $field }}" name="credentials[{{ $field }}]" type="password" @if (isset($definition['min'])) minlength="{{ $definition['min'] }}" @endif maxlength="{{ $definition['max'] }}" autocomplete="new-password" {{ inputAttrs('credentials.'.$field, 'credential-'.$setting->group.'-'.$field, 'credential-'.$setting->group.'-'.$field.'-help') }}>
                                <button class="app-password-toggle" type="button" data-password-toggle aria-controls="credential-{{ $setting->group }}-{{ $field }}" aria-label="Show credential" aria-pressed="false" title="Show credential"><i class="fa-solid fa-eye" aria-hidden="true"></i></button>
                            </div>
                        @endif
                        @error('credentials.'.$field)<div class="invalid-feedback d-block" id="credential-{{ $setting->group }}-{{ $field }}-error">{{ $message }}</div>@enderror
                        <div class="form-text" id="credential-{{ $setting->group }}-{{ $field }}-help">{{ ($configuredCredentials[$field] ?? false) ? 'Configured. Leave blank to keep the existing value.' : (($definition['optional'] ?? false) ? 'Leave blank if your provider does not require this field.' : 'Required before enabling this configuration.') }}</div>
                    </div>
                @endforeach
            </div>
        @endif
    </fieldset>
    @if ($canUpdate)
        <div class="d-flex justify-content-end border-top pt-2 mt-3">
            <button class="btn btn-primary" type="submit"><i class="fa-solid fa-check me-2" aria-hidden="true"></i>Save</button>
        </div>
    @else
        <p class="small text-body-secondary mb-0 mt-3">You have read-only access to these settings.</p>
    @endif
</form>
