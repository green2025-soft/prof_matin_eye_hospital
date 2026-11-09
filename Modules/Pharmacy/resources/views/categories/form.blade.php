<form class="app-card p-3 p-md-4" action="{{ $formAction }}" method="POST">
    @csrf
    <input id="type" name="type" type="hidden" value="{{ $defaultType }}">
    @error('type')<div class="invalid-feedback d-block" id="type-error">{{ $message }}</div>@enderror
    @if ($formMethod !== 'POST')
        @method($formMethod)
    @endif
    {{-- <h2 class="h6 mb-3">{{ $pageTitle }}</h2> --}}
    <div class="row g-3">
        <div class="col-12">
            <label class="form-label" for="name">Generic/Category Name <span class="text-danger" aria-hidden="true">*</span></label>
            <input class="{{ inputClass('name') }}" id="name" name="name" type="text" value="{{ getValue('name', $product_category) }}" maxlength="255" required {{ inputAttrs('name') }}>
            @error('name')<div class="invalid-feedback d-block" id="name-error">{{ $message }}</div>@enderror
        </div>
        <div class="col-12">
            <label class="form-label" for="ordering">Ordering <span class="text-danger" aria-hidden="true">*</span></label>
            <input class="{{ inputClass('ordering') }}" id="ordering" name="ordering" type="number" value="{{ getValue('ordering', $product_category, 0) }}" min="0" step="1" inputmode="numeric" required {{ inputAttrs('ordering') }}>
            @error('ordering')<div class="invalid-feedback d-block" id="ordering-error">{{ $message }}</div>@enderror
        </div>
    </div>

    @include('core::partials.form-actions', ['model' => $product_category, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel])
</form>
