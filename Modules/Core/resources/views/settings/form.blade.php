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
                        <input class="{{ inputClass('data.enabled', 'form-check-input') }}"
                            id="payment-{{ $setting->group }}-enabled" name="data[enabled]" type="checkbox" value="1"
                            {{ getChecked(1, 'data.enabled', null, $values['data']['enabled']) }}
                            {{ inputAttrs('data.enabled', 'payment-'.$setting->group.'-enabled') }}>
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
                            <input class="{{ inputClass('data.sandbox', 'form-check-input') }}"
                                id="payment-{{ $setting->group }}-sandbox" name="data[sandbox]" type="checkbox" value="1"
                                {{ getChecked(1, 'data.sandbox', null, $values['data']['sandbox']) }}
                                {{ inputAttrs('data.sandbox', 'payment-'.$setting->group.'-sandbox') }}>
                            <label class="form-check-label" for="payment-{{ $setting->group }}-sandbox">Sandbox mode</label>
                            @error('data.sandbox')
                                <div class="invalid-feedback d-block" id="payment-{{ $setting->group }}-sandbox-error">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="payment-{{ $setting->group }}-charge">Extra charge</label>
                    <input class="{{ inputClass('data.extra_charge') }}" id="payment-{{ $setting->group }}-charge"
                        name="data[extra_charge]" type="text" inputmode="decimal"
                        value="{{ getValue('data.extra_charge', null, $values['data']['extra_charge']) }}" maxlength="13"
                        placeholder="0.00" required
                        {{ inputAttrs('data.extra_charge', 'payment-'.$setting->group.'-charge', 'payment-'.$setting->group.'-charge-help') }}>
                    <div class="form-text" id="payment-{{ $setting->group }}-charge-help">
                        Fixed amount for this payment method. Enter 0.00 for no extra charge.
                    </div>
                    @error('data.extra_charge')
                        <div class="invalid-feedback d-block" id="payment-{{ $setting->group }}-charge-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            @if ($credentialFields !== [])
                @error('credentials')<div class="invalid-feedback d-block mb-3">{{ $message }}</div>@enderror
                <div class="row g-3">
                    @include('core::settings.credentials', ['credentialFields' => $credentialFields])
                </div>
            @endif
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
                    <input class="{{ inputClass('logo') }}" id="branding-logo" name="logo" type="file"
                        accept=".png,.jpg,.jpeg,.webp,.gif,image/png,image/jpeg,image/webp,image/gif"
                        {{ inputAttrs('logo', 'branding-logo', 'branding-logo-help') }}>
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
                    <input class="{{ inputClass('favicon') }}" id="branding-favicon" name="favicon" type="file"
                        accept=".png,.ico,image/png,image/x-icon,image/vnd.microsoft.icon"
                        {{ inputAttrs('favicon', 'branding-favicon', 'branding-favicon-help') }}>
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
            @error('credentials')<div class="invalid-feedback d-block mt-3">{{ $message }}</div>@enderror
            <div class="row g-3 mt-0">
                @include('core::settings.credentials', ['credentialFields' => $credentialFields])
            </div>
        @elseif ($setting->group === 'sms')
            @php
                $smsBodyFormat = ($configuredCredentials['parameters'] ?? false) ? $values['data']['body_format'] : 'form';
                $showSmsAdvanced = $errors->hasAny([
                    'data.provider', 'data.method', 'data.body_format',
                    'data.success_field', 'data.success_value', 'data.failure_values',
                    'credentials.username', 'credentials.password', 'credentials.headers', 'credentials.parameters',
                ]);
            @endphp
            <div class="form-check mb-3">
                <input name="data[enabled]" type="hidden" value="0">
                <input class="{{ inputClass('data.enabled', 'form-check-input') }}" id="sms-enabled"
                    name="data[enabled]" type="checkbox" value="1"
                    {{ getChecked(1, 'data.enabled', null, $values['data']['enabled'] ?? false) }}
                    {{ inputAttrs('data.enabled', 'sms-enabled') }}>
                <label class="form-check-label" for="sms-enabled">Enable SMS</label>
                @error('data.enabled')<div class="invalid-feedback d-block" id="sms-enabled-error">{{ $message }}</div>@enderror
            </div>
            @error('credentials')<div class="invalid-feedback d-block mb-3">{{ $message }}</div>@enderror
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label" for="sms-api-url">API URL</label>
                    <input class="{{ inputClass('data.api_url') }}" id="sms-api-url" name="data[api_url]" type="url"
                        value="{{ getValue('data.api_url', null, $values['data']['api_url'] ?? '') }}"
                        maxlength="2048" autocomplete="off" {{ inputAttrs('data.api_url', 'sms-api-url') }}>
                    @error('data.api_url')<div class="invalid-feedback d-block" id="sms-api-url-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="sms-sender-id">Sender ID</label>
                    <input class="{{ inputClass('data.sender_id') }}" id="sms-sender-id" name="data[sender_id]" type="text"
                        value="{{ getValue('data.sender_id', null, $values['data']['sender_id'] ?? '') }}"
                        maxlength="100" {{ inputAttrs('data.sender_id', 'sms-sender-id') }}>
                    @error('data.sender_id')<div class="invalid-feedback d-block" id="sms-sender-id-error">{{ $message }}</div>@enderror
                </div>
                @include('core::settings.credentials', ['credentialFields' => ['api_key' => $credentialFields['api_key']]])
            </div>

            <details class="border rounded p-3 mt-3" @if ($showSmsAdvanced) open @endif>
                <summary class="small fw-semibold">Advanced</summary>
                <div class="row g-3 mt-0">
                    <div class="col-md-6">
                        <label class="form-label" for="sms-provider">Provider name</label>
                        <input class="{{ inputClass('data.provider') }}" id="sms-provider" name="data[provider]" type="text"
                            value="{{ getValue('data.provider', null, $values['data']['provider'] ?? '') }}"
                            maxlength="100" {{ inputAttrs('data.provider', 'sms-provider') }}>
                        @error('data.provider')<div class="invalid-feedback d-block" id="sms-provider-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="sms-method">Method</label>
                        <select class="{{ inputClass('data.method', 'form-select') }}" id="sms-method"
                            name="data[method]" {{ inputAttrs('data.method', 'sms-method') }}>
                            <option value="POST" {{ getSelected('POST', 'data.method', null, $values['data']['method'] ?? 'POST') }}>POST</option>
                            <option value="GET" {{ getSelected('GET', 'data.method', null, $values['data']['method'] ?? 'POST') }}>GET</option>
                        </select>
                        @error('data.method')<div class="invalid-feedback d-block" id="sms-method-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="sms-body-format">Format</label>
                        <select class="{{ inputClass('data.body_format', 'form-select') }}" id="sms-body-format"
                            name="data[body_format]" {{ inputAttrs('data.body_format', 'sms-body-format') }}>
                            <option value="json" {{ getSelected('json', 'data.body_format', null, $smsBodyFormat) }}>JSON</option>
                            <option value="form" {{ getSelected('form', 'data.body_format', null, $smsBodyFormat) }}>Form encoded</option>
                        </select>
                        @error('data.body_format')<div class="invalid-feedback d-block" id="sms-body-format-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="sms-success-field">Success response field</label>
                        <input class="{{ inputClass('data.success_field') }}" id="sms-success-field" name="data[success_field]" type="text"
                            value="{{ getValue('data.success_field', null, $values['data']['success_field'] ?? '') }}"
                            maxlength="100" placeholder="response_code" {{ inputAttrs('data.success_field', 'sms-success-field', 'sms-success-help') }}>
                        @error('data.success_field')<div class="invalid-feedback d-block" id="sms-success-field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="sms-success-value">Success value</label>
                        <input class="{{ inputClass('data.success_value') }}" id="sms-success-value" name="data[success_value]" type="text"
                            value="{{ getValue('data.success_value', null, $values['data']['success_value'] ?? '') }}"
                            maxlength="100" placeholder="202" {{ inputAttrs('data.success_value', 'sms-success-value', 'sms-success-help') }}>
                        @error('data.success_value')<div class="invalid-feedback d-block" id="sms-success-value-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <p class="form-text mb-0" id="sms-success-help">Leave the response field blank to use HTTP success only. Otherwise match the field and value in your provider's JSON response.</p>
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="sms-failure-values">Confirmed rejection values</label>
                        <input class="{{ inputClass('data.failure_values') }}" id="sms-failure-values" name="data[failure_values]" type="text"
                            value="{{ getValue('data.failure_values', null, $values['data']['failure_values'] ?? '') }}"
                            maxlength="1000" placeholder="false" {{ inputAttrs('data.failure_values', 'sms-failure-values', 'sms-failure-help') }}>
                        <div class="form-text" id="sms-failure-help">Comma-separated response values that confirm rejection in your provider's documentation. Leave blank when unsure; other unmatched responses remain uncertain.</div>
                        @error('data.failure_values')<div class="invalid-feedback d-block" id="sms-failure-values-error">{{ $message }}</div>@enderror
                    </div>
                    @include('core::settings.credentials', ['credentialFields' => array_diff_key($credentialFields, ['api_key' => true])])
                </div>
                <p class="form-text mb-0 mt-3">Match your provider's field names; keep <code>{to}</code> and <code>{message}</code>.</p>
            </details>
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

    </fieldset>
    @if ($canUpdate)
        <div class="d-flex justify-content-end border-top pt-2 mt-3">
            <button class="btn btn-primary" type="submit"><i class="fa-solid fa-check me-2" aria-hidden="true"></i>Save</button>
        </div>
    @else
        <p class="small text-body-secondary mb-0 mt-3">You have read-only access to these settings.</p>
    @endif
</form>
