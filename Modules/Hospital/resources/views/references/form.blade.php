@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle, 'description' => 'Register the reference and their contact details at the selected branch.'])

    <form class="app-card p-3 p-md-4" action="{{ $formAction }}" method="POST">
        @csrf
        @if ($formMethod !== 'POST')
            @method($formMethod)
        @endif

        <section class="app-form-section" aria-labelledby="reference-details-heading">
            <h2 class="app-form-section-title" id="reference-details-heading">
                <span class="app-form-section-icon"><i class="fa-solid fa-address-card" aria-hidden="true"></i></span>Reference details
            </h2>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="name">Name <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="{{ inputClass('name') }}" id="name" name="name" type="text" value="{{ getValue('name', $reference) }}" maxlength="255" autocomplete="name" required autofocus {{ inputAttrs('name') }}>
                    @error('name')<div class="invalid-feedback d-block" id="name-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="designation">Designation</label>
                    <input class="{{ inputClass('designation') }}" id="designation" name="designation" type="text" value="{{ getValue('designation', $reference) }}" maxlength="255" autocomplete="organization-title" {{ inputAttrs('designation') }}>
                    @error('designation')<div class="invalid-feedback d-block" id="designation-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="reference_id">Reference ID</label>
                    <input class="form-control" id="reference_id" type="text" value="{{ $reference->reference_id }}" placeholder="Auto generated" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="contact_no">Contact no. <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="{{ inputClass('contact_no') }}" id="contact_no" name="contact_no" type="tel" value="{{ getValue('contact_no', $reference) }}" maxlength="30" autocomplete="tel" required {{ inputAttrs('contact_no') }}>
                    @error('contact_no')<div class="invalid-feedback d-block" id="contact_no-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label" for="address">Address</label>
                    <textarea class="{{ inputClass('address') }}" id="address" name="address" rows="2" maxlength="2000" autocomplete="street-address" {{ inputAttrs('address') }}>{{ getValue('address', $reference) }}</textarea>
                    @error('address')<div class="invalid-feedback d-block" id="address-error">{{ $message }}</div>@enderror
                </div>
            </div>
        </section>

        @if (in_array(true, $percentageFields, true))
            <section class="app-form-section" aria-labelledby="reference-percentages-heading">
                <h2 class="app-form-section-title" id="reference-percentages-heading">Commission percentages</h2>
                <div class="row g-3">
                    @if ($percentageFields['consultation_percent'])
                        <div class="col-md-4">
                            <label class="form-label" for="consultation_percent">Consultation % <span class="text-danger" aria-hidden="true">*</span></label>
                            <input class="{{ inputClass('consultation_percent') }}" id="consultation_percent" name="consultation_percent" type="number" inputmode="decimal" value="{{ getValue('consultation_percent', $reference, $percentageDefaults['consultation_percent']) }}" min="0" max="100" step="0.01" required {{ inputAttrs('consultation_percent') }}>
                            @error('consultation_percent')<div class="invalid-feedback d-block" id="consultation_percent-error">{{ $message }}</div>@enderror
                        </div>
                    @endif
                    @if ($percentageFields['investigation_percent'])
                        <div class="col-md-4">
                            <label class="form-label" for="investigation_percent">Investigation % <span class="text-danger" aria-hidden="true">*</span></label>
                            <input class="{{ inputClass('investigation_percent') }}" id="investigation_percent" name="investigation_percent" type="number" inputmode="decimal" value="{{ getValue('investigation_percent', $reference, $percentageDefaults['investigation_percent']) }}" min="0" max="100" step="0.01" required {{ inputAttrs('investigation_percent') }}>
                            @error('investigation_percent')<div class="invalid-feedback d-block" id="investigation_percent-error">{{ $message }}</div>@enderror
                        </div>
                    @endif
                    @if ($percentageFields['operation_percent'])
                        <div class="col-md-4">
                            <label class="form-label" for="operation_percent">Operation % <span class="text-danger" aria-hidden="true">*</span></label>
                            <input class="{{ inputClass('operation_percent') }}" id="operation_percent" name="operation_percent" type="number" inputmode="decimal" value="{{ getValue('operation_percent', $reference, $percentageDefaults['operation_percent']) }}" min="0" max="100" step="0.01" required {{ inputAttrs('operation_percent') }}>
                            @error('operation_percent')<div class="invalid-feedback d-block" id="operation_percent-error">{{ $message }}</div>@enderror
                        </div>
                    @endif
                </div>
            </section>
        @endif

        @include('core::partials.form-actions', ['model' => $reference, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel])
    </form>
@endsection
