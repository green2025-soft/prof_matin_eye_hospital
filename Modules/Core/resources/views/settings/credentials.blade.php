@foreach ($credentialFields as $field => $definition)
    @php
        $fieldName = 'credentials.'.$field;
        $inputId = 'credential-'.$setting->group.'-'.$field;
        $isConfigured = $configuredCredentials[$field] ?? false;
        $fieldValue = $isConfigured ? '' : ($definition['default'] ?? '');
        $helpId = $isConfigured ? $inputId.'-help' : null;
    @endphp
    <div class="{{ ($definition['multiline'] ?? false) ? 'col-12' : 'col-md-6' }}">
        <label class="form-label" for="{{ $inputId }}">{{ $definition['label'] }}</label>
        @if ($definition['multiline'] ?? false)
            <textarea class="{{ inputClass($fieldName) }}" id="{{ $inputId }}" name="credentials[{{ $field }}]"
                rows="4" maxlength="{{ $definition['max'] }}" autocomplete="off" spellcheck="false"
                {{ inputAttrs($fieldName, $inputId, $helpId) }}>{{ $fieldValue }}</textarea>
        @else
            <div class="app-input-wrap">
                <input class="{{ inputClass($fieldName) }} app-password-input" id="{{ $inputId }}"
                    name="credentials[{{ $field }}]" type="password" value="{{ $fieldValue }}"
                    @if (isset($definition['min'])) minlength="{{ $definition['min'] }}" @endif
                    maxlength="{{ $definition['max'] }}" autocomplete="new-password"
                    {{ inputAttrs($fieldName, $inputId, $helpId) }}>
                <button class="app-password-toggle" type="button" data-password-toggle aria-controls="{{ $inputId }}"
                    aria-label="Show credential" aria-pressed="false" title="Show credential">
                    <i class="fa-solid fa-eye" aria-hidden="true"></i>
                </button>
            </div>
        @endif
        @error($fieldName)
            <div class="invalid-feedback d-block" id="{{ $inputId }}-error">{{ $message }}</div>
        @enderror
        @if ($isConfigured)
            <div class="form-text" id="{{ $helpId }}">Configured. Blank keeps existing.</div>
        @endif
    </div>
@endforeach
