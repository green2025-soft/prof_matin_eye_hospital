<form class="app-card p-3 p-md-4" action="{{ $formAction }}" method="POST">
    @csrf
    @if ($formMethod !== 'POST')
        @method($formMethod)
    @endif

    <h2 class="h6 mb-3">{{ $pageTitle }}</h2>
    <div class="mb-3">
        <label class="form-label" for="section">Section <span class="text-danger" aria-hidden="true">*</span></label>
        <select class="{{ inputClass('section', 'form-select') }}" id="section" name="section" required
            {{ inputAttrs('section') }}>
            <option value="">Select section</option>
            @foreach ($sections as $value => $label)
                <option value="{{ $value }}"
                    @selected(getValue('section', $prescription_option, request()->query('section')) === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('section')<div class="invalid-feedback d-block" id="section-error">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label class="form-label" for="name">Name <span class="text-danger" aria-hidden="true">*</span></label>
        <input class="{{ inputClass('name') }}" id="name" name="name" type="text"
            value="{{ getValue('name', $prescription_option) }}" maxlength="255" autocomplete="off" required
            {{ inputAttrs('name') }}>
        @error('name')<div class="invalid-feedback d-block" id="name-error">{{ $message }}</div>@enderror
    </div>
    <div>
        <label class="form-label" for="ordering">Ordering</label>
        <input class="{{ inputClass('ordering') }}" id="ordering" name="ordering" type="number" inputmode="numeric"
            value="{{ getValue('ordering', $prescription_option, 0) }}" min="0" max="65535" step="1" required
            {{ inputAttrs('ordering') }}>
        @error('ordering')<div class="invalid-feedback d-block" id="ordering-error">{{ $message }}</div>@enderror
    </div>

    @include('core::partials.form-actions', [
        'model' => $prescription_option,
        'routePrefix' => $routePrefix,
        'submitLabel' => $submitLabel,
    ])
</form>
