<form class="app-card p-3 p-md-4" action="{{ $formAction }}" method="POST">
    @csrf
    @if ($formMethod !== 'POST')
        @method($formMethod)
    @endif

    <h2 class="h6 mb-3">{{ $pageTitle }}</h2>
    <div class="row g-3">
        <div class="col-12">
            <label class="form-label" for="name">Dose Name <span class="text-danger" aria-hidden="true">*</span></label>
            <input class="{{ inputClass('name') }}" id="name" name="name" type="text" value="{{ getValue('name', $prescription_dose) }}" maxlength="255" required {{ inputAttrs('name') }}>
            @error('name')<div class="invalid-feedback d-block" id="name-error">{{ $message }}</div>@enderror
        </div>
    </div>

    @include('core::partials.form-actions', [
        'model' => $prescription_dose,
        'routePrefix' => $routePrefix,
        'submitLabel' => $submitLabel,
    ])
</form>
