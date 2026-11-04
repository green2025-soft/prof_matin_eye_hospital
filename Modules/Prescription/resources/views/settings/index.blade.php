@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle])
    <form class="app-card p-3 p-md-4" action="{{ route('prescription.settings.update', $setting) }}" method="POST">
        @csrf
        @method('PUT')
        <fieldset @disabled(! $canUpdate)>
            <legend class="visually-hidden">Prescription form and print settings</legend>
            <div class="row g-4">
                <section class="col-lg-6" aria-labelledby="settings-general-heading">
                    <h2 class="h6 mb-3" id="settings-general-heading">General prescription</h2>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <input name="data[general_enabled]" type="hidden" value="0">
                            <div class="form-check form-switch">
                                <input class="{{ inputClass('data.general_enabled', 'form-check-input') }}" id="general_enabled" name="data[general_enabled]" type="checkbox" value="1" {{ getChecked(1, 'data.general_enabled', null, $values['general_enabled']) }} {{ inputAttrs('data.general_enabled', 'general_enabled') }}>
                                <label class="form-check-label" for="general_enabled">Enable general prescription</label>
                            </div>
                            @error('data.general_enabled')<div class="invalid-feedback d-block" id="general_enabled-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <input name="data[general_chief_complaints_enabled]" type="hidden" value="0">
                            <div class="form-check form-switch">
                                <input class="{{ inputClass('data.general_chief_complaints_enabled', 'form-check-input') }}" id="general_chief_complaints_enabled" name="data[general_chief_complaints_enabled]" type="checkbox" value="1" {{ getChecked(1, 'data.general_chief_complaints_enabled', null, $values['general_chief_complaints_enabled']) }} {{ inputAttrs('data.general_chief_complaints_enabled', 'general_chief_complaints_enabled') }}>
                                <label class="form-check-label" for="general_chief_complaints_enabled">Chief complaints</label>
                            </div>
                            @error('data.general_chief_complaints_enabled')<div class="invalid-feedback d-block" id="general_chief_complaints_enabled-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <input name="data[general_history_enabled]" type="hidden" value="0">
                            <div class="form-check form-switch">
                                <input class="{{ inputClass('data.general_history_enabled', 'form-check-input') }}" id="general_history_enabled" name="data[general_history_enabled]" type="checkbox" value="1" {{ getChecked(1, 'data.general_history_enabled', null, $values['general_history_enabled']) }} {{ inputAttrs('data.general_history_enabled', 'general_history_enabled') }}>
                                <label class="form-check-label" for="general_history_enabled">History</label>
                            </div>
                            @error('data.general_history_enabled')<div class="invalid-feedback d-block" id="general_history_enabled-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <input name="data[general_examination_enabled]" type="hidden" value="0">
                            <div class="form-check form-switch">
                                <input class="{{ inputClass('data.general_examination_enabled', 'form-check-input') }}" id="general_examination_enabled" name="data[general_examination_enabled]" type="checkbox" value="1" {{ getChecked(1, 'data.general_examination_enabled', null, $values['general_examination_enabled']) }} {{ inputAttrs('data.general_examination_enabled', 'general_examination_enabled') }}>
                                <label class="form-check-label" for="general_examination_enabled">Examination</label>
                            </div>
                            @error('data.general_examination_enabled')<div class="invalid-feedback d-block" id="general_examination_enabled-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <input name="data[general_diagnosis_enabled]" type="hidden" value="0">
                            <div class="form-check form-switch">
                                <input class="{{ inputClass('data.general_diagnosis_enabled', 'form-check-input') }}" id="general_diagnosis_enabled" name="data[general_diagnosis_enabled]" type="checkbox" value="1" {{ getChecked(1, 'data.general_diagnosis_enabled', null, $values['general_diagnosis_enabled']) }} {{ inputAttrs('data.general_diagnosis_enabled', 'general_diagnosis_enabled') }}>
                                <label class="form-check-label" for="general_diagnosis_enabled">Diagnosis</label>
                            </div>
                            @error('data.general_diagnosis_enabled')<div class="invalid-feedback d-block" id="general_diagnosis_enabled-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <input name="data[general_investigations_enabled]" type="hidden" value="0">
                            <div class="form-check form-switch">
                                <input class="{{ inputClass('data.general_investigations_enabled', 'form-check-input') }}" id="general_investigations_enabled" name="data[general_investigations_enabled]" type="checkbox" value="1" {{ getChecked(1, 'data.general_investigations_enabled', null, $values['general_investigations_enabled']) }} {{ inputAttrs('data.general_investigations_enabled', 'general_investigations_enabled') }}>
                                <label class="form-check-label" for="general_investigations_enabled">Investigations</label>
                            </div>
                            @error('data.general_investigations_enabled')<div class="invalid-feedback d-block" id="general_investigations_enabled-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <input name="data[general_advice_enabled]" type="hidden" value="0">
                            <div class="form-check form-switch">
                                <input class="{{ inputClass('data.general_advice_enabled', 'form-check-input') }}" id="general_advice_enabled" name="data[general_advice_enabled]" type="checkbox" value="1" {{ getChecked(1, 'data.general_advice_enabled', null, $values['general_advice_enabled']) }} {{ inputAttrs('data.general_advice_enabled', 'general_advice_enabled') }}>
                                <label class="form-check-label" for="general_advice_enabled">Advice</label>
                            </div>
                            @error('data.general_advice_enabled')<div class="invalid-feedback d-block" id="general_advice_enabled-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <input name="data[general_follow_up_enabled]" type="hidden" value="0">
                            <div class="form-check form-switch">
                                <input class="{{ inputClass('data.general_follow_up_enabled', 'form-check-input') }}" id="general_follow_up_enabled" name="data[general_follow_up_enabled]" type="checkbox" value="1" {{ getChecked(1, 'data.general_follow_up_enabled', null, $values['general_follow_up_enabled']) }} {{ inputAttrs('data.general_follow_up_enabled', 'general_follow_up_enabled') }}>
                                <label class="form-check-label" for="general_follow_up_enabled">Follow-up date</label>
                            </div>
                            @error('data.general_follow_up_enabled')<div class="invalid-feedback d-block" id="general_follow_up_enabled-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <input name="data[general_header_enabled]" type="hidden" value="0">
                            <div class="form-check form-switch">
                                <input class="{{ inputClass('data.general_header_enabled', 'form-check-input') }}" id="general_header_enabled" name="data[general_header_enabled]" type="checkbox" value="1" {{ getChecked(1, 'data.general_header_enabled', null, $values['general_header_enabled']) }} {{ inputAttrs('data.general_header_enabled', 'general_header_enabled') }}>
                                <label class="form-check-label" for="general_header_enabled">Print hospital header</label>
                            </div>
                            @error('data.general_header_enabled')<div class="invalid-feedback d-block" id="general_header_enabled-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="general_paper">Print paper</label>
                            <select class="{{ inputClass('data.general_paper', 'form-select') }}" id="general_paper" name="data[general_paper]" required {{ inputAttrs('data.general_paper', 'general_paper') }}>
                                <option value="a4" {{ getSelected('a4', 'data.general_paper', null, $values['general_paper']) }}>A4</option>
                                <option value="a5" {{ getSelected('a5', 'data.general_paper', null, $values['general_paper']) }}>A5</option>
                            </select>
                            @error('data.general_paper')<div class="invalid-feedback d-block" id="general_paper-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </section>
                <section class="col-lg-6" aria-labelledby="settings-glass-heading">
                    <h2 class="h6 mb-3" id="settings-glass-heading">Glass prescription</h2>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <input name="data[glass_enabled]" type="hidden" value="0">
                            <div class="form-check form-switch">
                                <input class="{{ inputClass('data.glass_enabled', 'form-check-input') }}" id="glass_enabled" name="data[glass_enabled]" type="checkbox" value="1" {{ getChecked(1, 'data.glass_enabled', null, $values['glass_enabled']) }} {{ inputAttrs('data.glass_enabled', 'glass_enabled') }}>
                                <label class="form-check-label" for="glass_enabled">Enable glass prescription</label>
                            </div>
                            @error('data.glass_enabled')<div class="invalid-feedback d-block" id="glass_enabled-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <input name="data[glass_pd_enabled]" type="hidden" value="0">
                            <div class="form-check form-switch">
                                <input class="{{ inputClass('data.glass_pd_enabled', 'form-check-input') }}" id="glass_pd_enabled" name="data[glass_pd_enabled]" type="checkbox" value="1" {{ getChecked(1, 'data.glass_pd_enabled', null, $values['glass_pd_enabled']) }} {{ inputAttrs('data.glass_pd_enabled', 'glass_pd_enabled') }}>
                                <label class="form-check-label" for="glass_pd_enabled">PD</label>
                            </div>
                            @error('data.glass_pd_enabled')<div class="invalid-feedback d-block" id="glass_pd_enabled-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <input name="data[glass_disclaimer_enabled]" type="hidden" value="0">
                            <div class="form-check form-switch">
                                <input class="{{ inputClass('data.glass_disclaimer_enabled', 'form-check-input') }}" id="glass_disclaimer_enabled" name="data[glass_disclaimer_enabled]" type="checkbox" value="1" {{ getChecked(1, 'data.glass_disclaimer_enabled', null, $values['glass_disclaimer_enabled']) }} {{ inputAttrs('data.glass_disclaimer_enabled', 'glass_disclaimer_enabled') }}>
                                <label class="form-check-label" for="glass_disclaimer_enabled">Disclaimer</label>
                            </div>
                            @error('data.glass_disclaimer_enabled')<div class="invalid-feedback d-block" id="glass_disclaimer_enabled-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <input name="data[glass_lens_options_enabled]" type="hidden" value="0">
                            <div class="form-check form-switch">
                                <input class="{{ inputClass('data.glass_lens_options_enabled', 'form-check-input') }}" id="glass_lens_options_enabled" name="data[glass_lens_options_enabled]" type="checkbox" value="1" {{ getChecked(1, 'data.glass_lens_options_enabled', null, $values['glass_lens_options_enabled']) }} {{ inputAttrs('data.glass_lens_options_enabled', 'glass_lens_options_enabled') }}>
                                <label class="form-check-label" for="glass_lens_options_enabled">Lens / usage options</label>
                            </div>
                            @error('data.glass_lens_options_enabled')<div class="invalid-feedback d-block" id="glass_lens_options_enabled-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <input name="data[glass_header_enabled]" type="hidden" value="0">
                            <div class="form-check form-switch">
                                <input class="{{ inputClass('data.glass_header_enabled', 'form-check-input') }}" id="glass_header_enabled" name="data[glass_header_enabled]" type="checkbox" value="1" {{ getChecked(1, 'data.glass_header_enabled', null, $values['glass_header_enabled']) }} {{ inputAttrs('data.glass_header_enabled', 'glass_header_enabled') }}>
                                <label class="form-check-label" for="glass_header_enabled">Print hospital header</label>
                            </div>
                            @error('data.glass_header_enabled')<div class="invalid-feedback d-block" id="glass_header_enabled-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="glass_paper">Print paper</label>
                            <select class="{{ inputClass('data.glass_paper', 'form-select') }}" id="glass_paper" name="data[glass_paper]" required {{ inputAttrs('data.glass_paper', 'glass_paper') }}>
                                <option value="a4" {{ getSelected('a4', 'data.glass_paper', null, $values['glass_paper']) }}>A4</option>
                                <option value="a5" {{ getSelected('a5', 'data.glass_paper', null, $values['glass_paper']) }}>A5</option>
                            </select>
                            @error('data.glass_paper')<div class="invalid-feedback d-block" id="glass_paper-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </section>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button class="btn btn-primary" type="submit">Save settings</button>
                <a class="btn btn-warning" href="{{ route('prescription.dashboard') }}">Cancel</a>
            </div>
        </fieldset>
    </form>
@endsection
