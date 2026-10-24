<form class="app-card p-3 p-md-4" action="{{ $formAction }}" method="POST">
    @csrf
    @method('PUT')
    <fieldset class="app-form-section border-0 p-0 mb-0" aria-labelledby="settings-{{ $setting->group }}-heading" @disabled(! $canUpdate)>
        <h2 class="app-form-section-title" id="settings-{{ $setting->group }}-heading"><span class="app-form-section-icon"><i class="fa-solid fa-{{ $groupIcon }}" aria-hidden="true"></i></span>{{ $groupLabel }}</h2>
        <p class="app-form-section-note">Changes apply to this workspace.</p>
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
                    <input class="{{ inputClass('data.name') }}" id="company-name" minlength="5" name="data[name]" type="text" value="{{ getValue('data.name', null, $values['data']['name']) }}" maxlength="255" autocomplete="organization" required {{ inputAttrs('data.name', 'company-name') }}>
                    @error('data.name')<div class="invalid-feedback d-block" id="company-name-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="company-email">Email address</label>
                    <input class="{{ inputClass('data.email') }}" id="company-email" name="data[email]" type="email" value="{{ getValue('data.email', null, $values['data']['email']) }}" maxlength="255" autocomplete="email" {{ inputAttrs('data.email', 'company-email') }}>
                    @error('data.email')<div class="invalid-feedback d-block" id="company-email-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="company-phone">Phone</label>
                    <input class="{{ inputClass('data.phone') }}" id="company-phone" minlength="5" name="data[phone]" type="tel" inputmode="numeric" pattern="[0-9]{5,30}" value="{{ getValue('data.phone', null, $values['data']['phone']) }}" maxlength="30" autocomplete="tel" {{ inputAttrs('data.phone', 'company-phone') }}>
                    @error('data.phone')<div class="invalid-feedback d-block" id="company-phone-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="company-tax-id">Tax identification</label>
                    <input class="{{ inputClass('data.tax_id') }}" id="company-tax-id" name="data[tax_id]" type="text" value="{{ getValue('data.tax_id', null, $values['data']['tax_id']) }}" maxlength="100" autocomplete="off" {{ inputAttrs('data.tax_id', 'company-tax-id') }}>
                    @error('data.tax_id')<div class="invalid-feedback d-block" id="company-tax-id-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label" for="company-address">Address</label>
                    <textarea class="{{ inputClass('data.address') }}" id="company-address" minlength="5" name="data[address]" rows="3" maxlength="2000" autocomplete="street-address" {{ inputAttrs('data.address', 'company-address') }}>{{ getValue('data.address', null, $values['data']['address']) }}</textarea>
                    @error('data.address')<div class="invalid-feedback d-block" id="company-address-error">{{ $message }}</div>@enderror
                </div>
            </div>
        @elseif ($setting->group === 'branding')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="branding-name">Application name <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="{{ inputClass('data.app_name') }}" id="branding-name" minlength="5" name="data[app_name]" type="text" value="{{ getValue('data.app_name', null, $values['data']['app_name']) }}" maxlength="120" required {{ inputAttrs('data.app_name', 'branding-name') }}>
                    @error('data.app_name')<div class="invalid-feedback d-block" id="branding-name-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="branding-tagline">Tagline</label>
                    <input class="{{ inputClass('data.tagline') }}" id="branding-tagline" minlength="5" name="data[tagline]" type="text" value="{{ getValue('data.tagline', null, $values['data']['tagline']) }}" maxlength="255" {{ inputAttrs('data.tagline', 'branding-tagline') }}>
                    @error('data.tagline')<div class="invalid-feedback d-block" id="branding-tagline-error">{{ $message }}</div>@enderror
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
                    <textarea class="{{ inputClass('data.payment_instructions') }}" id="billing-instructions" minlength="5" name="data[payment_instructions]" rows="4" maxlength="4000" {{ inputAttrs('data.payment_instructions', 'billing-instructions') }}>{{ getValue('data.payment_instructions', null, $values['data']['payment_instructions']) }}</textarea>
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
        @elseif ($setting->group === 'manual')
            <div>
                <label class="form-label" for="manual-instructions">Payment instructions</label>
                <textarea
                    class="{{ inputClass('data.instructions') }}"
                    id="manual-instructions"
                    name="data[instructions]"
                    rows="4"
                    minlength="5"
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
        @else
            <p class="small text-body-secondary">Enter only credentials you want to replace. Existing values stay hidden.</p>
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
                        <div class="form-text" id="credential-{{ $setting->group }}-{{ $field }}-help">{{ $configuredCredentials[$field] ? 'Configured. Leave blank to keep the existing value.' : 'Not configured. Required before enabling payments.' }}</div>
                    </div>
                @endforeach
            </div>
        @endif
    </fieldset>
    @if ($canUpdate)
        <div class="d-flex justify-content-end border-top pt-3 mt-4">
            <button class="btn btn-primary" type="submit"><i class="fa-solid fa-check me-2" aria-hidden="true"></i>Save {{ $groupLabel }}</button>
        </div>
    @else
        <p class="small text-body-secondary mb-0 mt-3">You have read-only access to these settings.</p>
    @endif
</form>
