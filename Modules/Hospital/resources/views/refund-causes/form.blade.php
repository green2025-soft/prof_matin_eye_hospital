<form class="app-card p-3 p-md-4" action="{{ $formAction }}" method="POST">
    @csrf
    @if ($formMethod !== 'POST')
        @method($formMethod)
    @endif

    <section class="app-form-section" aria-labelledby="refund-cause-form-heading">
        <h2 class="app-form-section-title" id="refund-cause-form-heading"><span class="app-form-section-icon"><i class="fa-solid fa-rotate-left" aria-hidden="true"></i></span>{{ $pageTitle }}</h2>
        <p class="app-form-section-note">Set the refund cause name and display order.</p>
        <div class="mb-3">
            <label class="form-label" for="name">Name <span class="text-danger" aria-hidden="true">*</span></label>
            <input class="{{ inputClass('name') }}" id="name" name="name" type="text" value="{{ getValue('name', $refund_cause) }}" maxlength="255" autocomplete="off" required autofocus {{ inputAttrs('name') }}>
            @error('name')<div class="invalid-feedback d-block" id="name-error">{{ $message }}</div>@enderror
        </div>
        <div>
            <label class="form-label" for="ordering">Ordering <span class="text-danger" aria-hidden="true">*</span></label>
            <input class="{{ inputClass('ordering') }}" id="ordering" name="ordering" type="number" inputmode="numeric" value="{{ getValue('ordering', $refund_cause, 0) }}" min="0" max="65535" step="1" required {{ inputAttrs('ordering') }}>
            @error('ordering')<div class="invalid-feedback d-block" id="ordering-error">{{ $message }}</div>@enderror
        </div>
    </section>

    @include('core::partials.form-actions', ['model' => $refund_cause, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel])
</form>
