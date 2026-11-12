@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle])
    <form class="app-card p-3 p-md-4" method="POST" action="{{ $formAction }}">
        @csrf
        @if ($formMethod !== 'POST') @method($formMethod) @endif
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label" for="tenant_id">Tenant</label>
                @if ($account->exists)
                    <input class="form-control" id="tenant_id" value="{{ $account->tenant->name }}" readonly>
                @else
                    <select class="{{ inputClass('tenant_id', 'form-select') }}" id="tenant_id" name="tenant_id" required>
                        <option value="">Select tenant</option>
                        @foreach ($tenants as $tenant)
                            <option value="{{ $tenant->id }}" {{ getSelected($tenant->id, 'tenant_id', null, $selectedTenantId) }}>{{ $tenant->name }}</option>
                        @endforeach
                    </select>
                @endif
                @error('tenant_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label" for="rate">Rate / SMS part (BDT)</label>
                <input class="{{ inputClass('rate') }}" id="rate" name="rate" inputmode="decimal" value="{{ getValue('rate', null, $rate) }}" required>
                @error('rate')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label" for="monthly_limit">Monthly part limit</label>
                <input class="{{ inputClass('monthly_limit') }}" id="monthly_limit" name="monthly_limit" type="number" min="0" value="{{ getValue('monthly_limit', $account) }}" placeholder="Unlimited">
                @error('monthly_limit')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <input name="enabled" type="hidden" value="0">
                <label class="form-check form-switch"><input class="form-check-input" name="enabled" type="checkbox" value="1" {{ getChecked(1, 'enabled', $account, false) }}>Enable SMS</label>
                @error('enabled')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
        </div>
        <fieldset class="mt-4">
            <legend class="h6 mb-3">Allowed features</legend>
            <div class="row g-2">
                @foreach ($features as $key => $label)
                    <div class="col-md-6 col-xl-4">
                        <label class="form-check"><input class="form-check-input" name="allowed_features[]" type="checkbox" value="{{ $key }}" {{ getChecked($key, 'allowed_features', $account, []) }}>{{ $label }}</label>
                    </div>
                @endforeach
            </div>
            @error('allowed_features')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            @error('allowed_features.*')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </fieldset>
        <section class="border rounded p-3 mt-4">
            <input name="gateway_enabled" type="hidden" value="0">
            <label class="form-check form-switch mb-2"><input class="form-check-input" name="gateway_enabled" type="checkbox" value="1" {{ getChecked(1, 'gateway_enabled', null, $hasGateway) }}>Use a private gateway</label>
            <details @if ($hasGateway || $errors->has('gateway.*')) open @endif>
                <summary class="small fw-semibold">Gateway configuration</summary>
                <div class="row g-3 mt-0">
                    <div class="col-md-8">
                        <label class="form-label" for="gateway_api_url">API URL</label>
                        <input class="{{ inputClass('gateway.api_url') }}" id="gateway_api_url" name="gateway[api_url]" type="url" value="{{ getValue('gateway.api_url', null, $gatewayValues['api_url'] ?? '') }}">
                        @error('gateway.api_url')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="gateway_sender_id">Sender ID</label>
                        <input class="{{ inputClass('gateway.sender_id') }}" id="gateway_sender_id" name="gateway[sender_id]" value="{{ getValue('gateway.sender_id', null, $gatewayValues['sender_id'] ?? '') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="gateway_method">Method</label>
                        <select class="form-select" id="gateway_method" name="gateway[method]">
                            <option value="POST" {{ getSelected('POST', 'gateway.method', null, $gatewayValues['method'] ?? 'POST') }}>POST</option>
                            <option value="GET" {{ getSelected('GET', 'gateway.method', null, $gatewayValues['method'] ?? 'POST') }}>GET</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="gateway_body_format">Format</label>
                        <select class="form-select" id="gateway_body_format" name="gateway[body_format]">
                            <option value="form" {{ getSelected('form', 'gateway.body_format', null, $gatewayValues['body_format'] ?? 'form') }}>Form encoded</option>
                            <option value="json" {{ getSelected('json', 'gateway.body_format', null, $gatewayValues['body_format'] ?? 'form') }}>JSON</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="gateway_success_field">Success field</label>
                        <input class="form-control" id="gateway_success_field" name="gateway[success_field]" value="{{ getValue('gateway.success_field', null, $gatewayValues['success_field'] ?? '') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="gateway_success_value">Success value</label>
                        <input class="form-control" id="gateway_success_value" name="gateway[success_value]" value="{{ getValue('gateway.success_value', null, $gatewayValues['success_value'] ?? '') }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="gateway_failure_values">Confirmed failure values (comma separated)</label>
                        <input class="form-control" id="gateway_failure_values" name="gateway[failure_values]" value="{{ getValue('gateway.failure_values', null, $gatewayValues['failure_values'] ?? '') }}" maxlength="1000">
                        @error('gateway.failure_values')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    @foreach ($credentialFields as $field => $definition)
                        <div class="{{ ($definition['multiline'] ?? false) ? 'col-12' : 'col-md-6' }}">
                            <label class="form-label" for="gateway_{{ $field }}">{{ $definition['label'] }}</label>
                            @if ($definition['multiline'] ?? false)
                                <textarea class="{{ inputClass('gateway.'.$field) }}" id="gateway_{{ $field }}" name="gateway[{{ $field }}]" rows="3" autocomplete="off" spellcheck="false">{{ ($gatewayConfigured[$field] ?? false) ? '' : ($definition['default'] ?? '') }}</textarea>
                            @else
                                <input class="{{ inputClass('gateway.'.$field) }}" id="gateway_{{ $field }}" name="gateway[{{ $field }}]" type="password" autocomplete="new-password">
                            @endif
                            @if ($gatewayConfigured[$field] ?? false)<div class="form-text">Configured. Blank keeps existing.</div>@endif
                            @error('gateway.'.$field)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    @endforeach
                </div>
            </details>
        </section>
        <div class="d-flex justify-content-end gap-2 border-top mt-4 pt-3">
            <a class="btn btn-warning" href="{{ route('core.sms-accounts.index') }}">Cancel</a>
            <button class="btn btn-primary" type="submit">Save</button>
        </div>
    </form>
@endsection
