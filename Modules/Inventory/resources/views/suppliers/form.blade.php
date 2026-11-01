<form class="app-card p-3 p-md-4" action="{{ $formAction }}" method="POST">
    @csrf
    <input id="type" name="type" type="hidden" value="{{ $defaultType }}">
    @error('type')<div class="invalid-feedback d-block" id="type-error">{{ $message }}</div>@enderror
    @if ($formMethod !== 'POST')
        @method($formMethod)
    @endif
    <h2 class="h6 mb-3">{{ $pageTitle }}</h2>
    <div class="row g-3">
        <div class="col-12">
            <label class="form-label" for="name">Name <span class="text-danger" aria-hidden="true">*</span></label>
            <input class="{{ inputClass('name') }}" id="name" name="name" type="text" value="{{ getValue('name', $supplier) }}" maxlength="255" required {{ inputAttrs('name') }}>
            @error('name')<div class="invalid-feedback d-block" id="name-error">{{ $message }}</div>@enderror
        </div>
        <div class="col-12">
            <label class="form-label" for="contact_no">Contact no.</label>
            <input class="{{ inputClass('contact_no') }}" id="contact_no" name="contact_no" type="text" value="{{ getValue('contact_no', $supplier, null) }}" maxlength="100" {{ inputAttrs('contact_no') }}>
            @error('contact_no')<div class="invalid-feedback d-block" id="contact_no-error">{{ $message }}</div>@enderror
        </div>
        <div class="col-12">
            <label class="form-label" for="address">Address</label>
            <textarea class="{{ inputClass('address') }}" id="address" name="address" rows="2" maxlength="2000" {{ inputAttrs('address') }}>{{ getValue('address', $supplier, null) }}</textarea>
            @error('address')<div class="invalid-feedback d-block" id="address-error">{{ $message }}</div>@enderror
        </div>
        <div class="col-12">
            <label class="form-label" for="active">Status <span class="text-danger" aria-hidden="true">*</span></label>
            <select class="{{ inputClass('active', 'form-select') }}" id="active" name="active" required {{ inputAttrs('active') }}>
                <option value="1" @selected((int) getValue('active', $supplier, 1) === 1)>Active</option>
                <option value="0" @selected((int) getValue('active', $supplier, 1) === 0)>Inactive</option>
            </select>
            @error('active')<div class="invalid-feedback d-block" id="active-error">{{ $message }}</div>@enderror
        </div>
    </div>

    @include('core::partials.form-actions', ['model' => $supplier, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel])
</form>
