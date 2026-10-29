<form class="app-card p-3 p-md-4" action="{{ $formAction }}" method="POST">
    @csrf
    @if ($formMethod !== 'POST')
        @method($formMethod)
    @endif

    <section class="app-form-section" aria-labelledby="district-form-heading">
        <h2 class="app-form-section-title" id="district-form-heading"><span class="app-form-section-icon"><i class="fa-solid fa-map" aria-hidden="true"></i></span>{{ $pageTitle }}</h2>
        <p class="app-form-section-note">Set the district name in English and Bangla, and choose its division.</p>
        <div class="mb-3">
            <label class="form-label" for="name">Name (English) <span class="text-danger" aria-hidden="true">*</span></label>
            <input class="{{ inputClass('name') }}" id="name" name="name" type="text" value="{{ getValue('name', $district) }}" maxlength="255" autocomplete="off" required autofocus {{ inputAttrs('name') }}>
            @error('name')<div class="invalid-feedback d-block" id="name-error">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label" for="bn_name">Name (Bangla) <span class="text-danger" aria-hidden="true">*</span></label>
            <input class="{{ inputClass('bn_name') }}" id="bn_name" name="bn_name" type="text" value="{{ getValue('bn_name', $district) }}" maxlength="255" lang="bn" autocomplete="off" required {{ inputAttrs('bn_name') }}>
            @error('bn_name')<div class="invalid-feedback d-block" id="bn_name-error">{{ $message }}</div>@enderror
        </div>
        <div>
            <label class="form-label" for="division_id">Division <span class="text-danger" aria-hidden="true">*</span></label>
            <select class="{{ inputClass('division_id', 'form-select') }}" id="division_id" name="division_id" data-ajax-select data-url="{{ route('tenantcore.divisions.options') }}" data-value-field="id" data-label-field="name" data-placeholder="Select a division" required {{ inputAttrs('division_id') }}>
                <option value=""></option>
                @if ($selectedDivision !== null)
                    <option value="{{ $selectedDivision->id }}" {{ getSelected($selectedDivision->id, 'division_id', $district) }}>{{ $selectedDivision->name }}</option>
                @endif
            </select>
            @error('division_id')<div class="invalid-feedback d-block" id="division_id-error">{{ $message }}</div>@enderror
        </div>
    </section>

    @include('core::partials.form-actions', ['model' => $district, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel])
</form>
