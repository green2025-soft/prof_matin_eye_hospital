<section class="app-prescription-note mb-3">
    <div class="app-prescription-section-heading d-flex align-items-center gap-2">
        <h3 class="mb-0">O/E</h3>
        <button class="btn btn-outline-primary btn-sm" type="button" aria-label="Edit examination"
            data-bs-toggle="modal" data-bs-target="#prescription-examination-modal">
            <i class="fa-solid fa-plus" aria-hidden="true"></i>
        </button>
    </div>
    <div class="app-prescription-section-preview" id="examination-preview">
        <ul id="examination-observation-preview"></ul>
        <section class="app-prescription-preview-group mt-2" hidden>
            <h4>General:</h4>
            <ul id="examination-general-preview"></ul>
        </section>
        <section class="app-prescription-preview-group mt-2" hidden>
            <h4>V/A (Without / With Glasses):</h4>
            <ul id="examination-visual-acuity-preview"></ul>
        </section>
        <section class="app-prescription-preview-group mt-2" hidden>
            <h4>A/S:</h4>
            <ul id="examination-anterior-segment-preview"></ul>
        </section>
        <section class="app-prescription-preview-group mt-2" hidden>
            <h4>IOP:</h4>
            <ul id="examination-iop-preview"></ul>
        </section>
        <section class="app-prescription-preview-group mt-2" hidden>
            <h4>FL Stain:</h4>
            <ul id="examination-fluorescein-preview"></ul>
        </section>
        <section class="app-prescription-preview-group mt-2" hidden>
            <h4>Schirmer's Test:</h4>
            <ul id="examination-schirmer-preview"></ul>
        </section>
        <section class="app-prescription-preview-group mt-2" hidden>
            <h4>Fundus Oculi:</h4>
            <ul id="examination-fundus-preview"></ul>
        </section>
    </div>
</section>

@push('prescription-modals')
    <div class="modal fade app-prescription-section-modal prescription-examination-modal"
        id="prescription-examination-modal" tabindex="-1"
        aria-labelledby="prescription-examination-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title h5" id="prescription-examination-title">On Examination</h2>
                    <div class="prescription-examination-header-search">
                        @include('prescription::prescriptions.preset-search', ['pickerField' => 'examination'])
                    </div>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @error('examination_details')
                        <div class="invalid-feedback d-block mb-3">{{ $message }}</div>
                    @enderror
                    <div class="nav nav-tabs mb-3" role="tablist" aria-label="Examination sections">
                        <button class="nav-link active" id="visual-acuity-tab" type="button" role="tab"
                            data-bs-toggle="tab" data-bs-target="#visual-acuity-pane"
                            aria-controls="visual-acuity-pane" aria-selected="true">Visual Acuity (VA)</button>
                        <button class="nav-link" id="eye-examination-tab" type="button" role="tab"
                            data-bs-toggle="tab" data-bs-target="#eye-examination-pane"
                            aria-controls="eye-examination-pane" aria-selected="false">Eye Examination</button>
                        <button class="nav-link" id="examination-observation-tab" type="button" role="tab"
                            data-bs-toggle="tab" data-bs-target="#examination-observation-pane"
                            aria-controls="examination-observation-pane" aria-selected="false">Observation</button>
                        <button class="nav-link" id="examination-general-tab" type="button" role="tab"
                            data-bs-toggle="tab" data-bs-target="#examination-general-pane"
                            aria-controls="examination-general-pane" aria-selected="false">General examination</button>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane fade " id="examination-observation-pane" role="tabpanel"
                            aria-labelledby="examination-observation-tab" tabindex="0">
                            @include('prescription::prescriptions.preset-picker', [
                                'pickerField' => 'examination',
                                'pickerValue' => getValue('examination', $record),
                            ])
                            @include('prescription::prescriptions.clinical-items', ['pickerField' => 'examination'])
                        </div>
                        <div class="tab-pane fade" id="examination-general-pane" role="tabpanel"
                            aria-labelledby="examination-general-tab" tabindex="0">
                            @php
                                $defaultTemperatureUnit = filled(data_get($record, 'examination_details.temperature')) ? 'c' : 'f';
                                $temperatureUnit = getValue('examination_details.temperature_unit', $record, $defaultTemperatureUnit);
                                $activityOptions = ['Sedentary', 'Light', 'Moderate', 'Active', 'Very active'];
                                $selectedActivity = getValue('examination_details.activity', $record);
                                $bmiExpanded = filled(getValue('examination_details.bmi', $record))
                                    || $errors->has('examination_details.bmi');
                                $bsaExpanded = filled(getValue('examination_details.bsa', $record))
                                    || $errors->has('examination_details.bsa');
                                $bmrExpanded = filled(getValue('examination_details.bmr', $record))
                                    || $errors->has('examination_details.bmr');
                                $tdeeExpanded = filled(getValue('examination_details.tdee', $record))
                                    || $errors->has('examination_details.tdee');
                            @endphp

                            <div class="app-prescription-examination">
                                <div class="app-prescription-examination-row">
                                    <label class="app-prescription-examination-label" for="exam_bp_systolic">Blood pressure:</label>
                                    <div class="app-prescription-examination-control app-prescription-examination-bp d-flex flex-wrap align-items-center">
                                        <input id="exam_bp_systolic" name="examination_details[bp_systolic]" form="prescription-form"
                                            maxlength="50" inputmode="decimal" class="{{ inputClass('examination_details.bp_systolic') }}"
                                            value="{{ getValue('examination_details.bp_systolic', $record) }}" placeholder="Systolic"
                                            {{ inputAttrs('examination_details.bp_systolic', 'exam_bp_systolic') }}>
                                        <span aria-hidden="true">/</span>
                                        <label class="visually-hidden" for="exam_bp_diastolic">Diastolic blood pressure</label>
                                        <input id="exam_bp_diastolic" name="examination_details[bp_diastolic]" form="prescription-form"
                                            maxlength="50" inputmode="decimal" class="{{ inputClass('examination_details.bp_diastolic') }}"
                                            value="{{ getValue('examination_details.bp_diastolic', $record) }}" placeholder="Diastolic"
                                            {{ inputAttrs('examination_details.bp_diastolic', 'exam_bp_diastolic') }}>
                                        <span>mm Hg</span>
                                        @error('examination_details.bp_systolic')
                                            <div class="invalid-feedback d-block" id="exam_bp_systolic-error">{{ $message }}</div>
                                        @enderror
                                        @error('examination_details.bp_diastolic')
                                            <div class="invalid-feedback d-block" id="exam_bp_diastolic-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="app-prescription-examination-row">
                                    <label class="app-prescription-examination-label" for="exam_pulse">Pulse:</label>
                                    <div class="app-prescription-examination-control d-flex flex-wrap align-items-center">
                                        <div class="input-group app-prescription-examination-input">
                                            <input id="exam_pulse" name="examination_details[pulse]" form="prescription-form"
                                                maxlength="50" inputmode="decimal" class="{{ inputClass('examination_details.pulse') }}"
                                                value="{{ getValue('examination_details.pulse', $record) }}" placeholder="Pulse"
                                                {{ inputAttrs('examination_details.pulse', 'exam_pulse') }}>
                                            <span class="input-group-text">BPM</span>
                                        </div>
                                        <div class="btn-group" role="group" aria-label="Pulse rhythm">
                                            <input class="{{ inputClass('examination_details.pulse_rhythm', 'btn-check') }}"
                                                id="exam_pulse_regular" type="radio"
                                                name="examination_details[pulse_rhythm]" value="regular" form="prescription-form"
                                                @checked(getValue('examination_details.pulse_rhythm', $record) === 'regular')
                                                {{ inputAttrs('examination_details.pulse_rhythm', 'exam_pulse_rhythm') }}>
                                            <label class="btn btn-light" for="exam_pulse_regular">Regular</label>
                                            <input class="{{ inputClass('examination_details.pulse_rhythm', 'btn-check') }}"
                                                id="exam_pulse_irregular" type="radio"
                                                name="examination_details[pulse_rhythm]" value="irregular" form="prescription-form"
                                                @checked(getValue('examination_details.pulse_rhythm', $record) === 'irregular')
                                                {{ inputAttrs('examination_details.pulse_rhythm', 'exam_pulse_rhythm') }}>
                                            <label class="btn btn-light" for="exam_pulse_irregular">Irregular</label>
                                            <input class="{{ inputClass('examination_details.pulse_rhythm', 'btn-check') }}"
                                                id="exam_pulse_drop_beat" type="radio"
                                                name="examination_details[pulse_rhythm]" value="drop_beat" form="prescription-form"
                                                @checked(getValue('examination_details.pulse_rhythm', $record) === 'drop_beat')
                                                {{ inputAttrs('examination_details.pulse_rhythm', 'exam_pulse_rhythm') }}>
                                            <label class="btn btn-light" for="exam_pulse_drop_beat">Drop-Beat</label>
                                        </div>
                                        @error('examination_details.pulse')
                                            <div class="invalid-feedback d-block" id="exam_pulse-error">{{ $message }}</div>
                                        @enderror
                                        @error('examination_details.pulse_rhythm')
                                            <div class="invalid-feedback d-block" id="exam_pulse_rhythm-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="app-prescription-examination-row">
                                    <label class="app-prescription-examination-label" for="exam_temperature">Temperature:</label>
                                    <div class="app-prescription-examination-control d-flex flex-wrap align-items-center">
                                        <div class="input-group app-prescription-examination-temperature d-flex align-items-center">
                                            <input id="exam_temperature" name="examination_details[temperature]" form="prescription-form"
                                                maxlength="50" inputmode="decimal" class="{{ inputClass('examination_details.temperature') }}"
                                                value="{{ getValue('examination_details.temperature', $record) }}" placeholder="Temperature"
                                                {{ inputAttrs('examination_details.temperature', 'exam_temperature') }}>
                                            <label class="visually-hidden" for="exam_temperature_unit">Temperature unit</label>
                                            <select id="exam_temperature_unit" name="examination_details[temperature_unit]" form="prescription-form"
                                                class="{{ inputClass('examination_details.temperature_unit', 'form-select') }}"
                                                {{ inputAttrs('examination_details.temperature_unit', 'exam_temperature_unit') }}>
                                                <option value="f" @selected($temperatureUnit === 'f')>&deg;F</option>
                                                <option value="c" @selected($temperatureUnit === 'c')>&deg;C</option>
                                            </select>
                                        </div>
                                        <div class="btn-group">
                                            <input id="exam_temperature_normal" type="checkbox" name="examination_details[temperature_normal]" value="1"
                                                form="prescription-form" class="{{ inputClass('examination_details.temperature_normal', 'btn-check') }}"
                                                @checked((bool) getValue('examination_details.temperature_normal', $record, false))
                                                {{ inputAttrs('examination_details.temperature_normal', 'exam_temperature_normal') }}>
                                            <label class="btn btn-light" for="exam_temperature_normal">Normal</label>
                                        </div>
                                        @error('examination_details.temperature')
                                            <div class="invalid-feedback d-block" id="exam_temperature-error">{{ $message }}</div>
                                        @enderror
                                        @error('examination_details.temperature_unit')
                                            <div class="invalid-feedback d-block" id="exam_temperature_unit-error">{{ $message }}</div>
                                        @enderror
                                        @error('examination_details.temperature_normal')
                                            <div class="invalid-feedback d-block" id="exam_temperature_normal-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="app-prescription-examination-row">
                                    <label class="app-prescription-examination-label" for="exam_weight">Weight:</label>
                                    <div class="app-prescription-examination-control d-flex flex-wrap align-items-center">
                                        <div class="input-group app-prescription-examination-input">
                                            <input id="exam_weight" name="examination_details[weight]" form="prescription-form"
                                                maxlength="50" inputmode="decimal" class="{{ inputClass('examination_details.weight') }}"
                                                value="{{ getValue('examination_details.weight', $record) }}" placeholder="Weight"
                                                {{ inputAttrs('examination_details.weight', 'exam_weight') }}>
                                            <span class="input-group-text">kg</span>
                                        </div>
                                        <div class="form-check">
                                            <input id="exam_weight_visible" type="checkbox" name="examination_details[weight_visible]" value="1"
                                                form="prescription-form" class="{{ inputClass('examination_details.weight_visible', 'form-check-input') }}"
                                                @checked((bool) getValue('examination_details.weight_visible', $record, true))
                                                {{ inputAttrs('examination_details.weight_visible', 'exam_weight_visible') }}>
                                            <label class="form-check-label" for="exam_weight_visible">Show in prescription</label>
                                        </div>
                                        @error('examination_details.weight')
                                            <div class="invalid-feedback d-block" id="exam_weight-error">{{ $message }}</div>
                                        @enderror
                                        @error('examination_details.weight_visible')
                                            <div class="invalid-feedback d-block" id="exam_weight_visible-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="app-prescription-examination-row">
                                    <label class="app-prescription-examination-label" for="exam_height_feet">Height:</label>
                                    <div class="app-prescription-examination-control d-flex flex-wrap align-items-center">
                                        <div class="input-group app-prescription-examination-height d-flex align-items-center">
                                            <input id="exam_height_feet" name="examination_details[height_feet]" form="prescription-form"
                                                maxlength="50" inputmode="decimal" class="{{ inputClass('examination_details.height_feet') }}"
                                                value="{{ getValue('examination_details.height_feet', $record) }}" placeholder="Feet"
                                                {{ inputAttrs('examination_details.height_feet', 'exam_height_feet') }}>
                                            <span class="input-group-text">ft</span>
                                            <label class="visually-hidden" for="exam_height_inches">Height in inches</label>
                                            <input id="exam_height_inches" name="examination_details[height_inches]" form="prescription-form"
                                                maxlength="50" inputmode="decimal" class="{{ inputClass('examination_details.height_inches') }}"
                                                value="{{ getValue('examination_details.height_inches', $record) }}" placeholder="Inches"
                                                {{ inputAttrs('examination_details.height_inches', 'exam_height_inches') }}>
                                            <span class="input-group-text">in</span>
                                        </div>
                                        <div class="form-check">
                                            <input id="exam_height_visible" type="checkbox" name="examination_details[height_visible]" value="1"
                                                form="prescription-form" class="{{ inputClass('examination_details.height_visible', 'form-check-input') }}"
                                                @checked((bool) getValue('examination_details.height_visible', $record, true))
                                                {{ inputAttrs('examination_details.height_visible', 'exam_height_visible') }}>
                                            <label class="form-check-label" for="exam_height_visible">Show in prescription</label>
                                        </div>
                                        @error('examination_details.height_feet')
                                            <div class="invalid-feedback d-block" id="exam_height_feet-error">{{ $message }}</div>
                                        @enderror
                                        @error('examination_details.height_inches')
                                            <div class="invalid-feedback d-block" id="exam_height_inches-error">{{ $message }}</div>
                                        @enderror
                                        @error('examination_details.height_visible')
                                            <div class="invalid-feedback d-block" id="exam_height_visible-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="app-prescription-examination-row">
                                    <label class="app-prescription-examination-label" for="exam_activity">Activity level:</label>
                                    <div class="app-prescription-examination-control d-flex flex-wrap align-items-center">
                                        <select id="exam_activity" name="examination_details[activity]" form="prescription-form"
                                            class="{{ inputClass('examination_details.activity', 'form-select app-prescription-examination-input') }}"
                                            {{ inputAttrs('examination_details.activity', 'exam_activity') }}>
                                            <option value="">Select</option>
                                            @if (filled($selectedActivity) && ! in_array($selectedActivity, $activityOptions, true))
                                                <option value="{{ $selectedActivity }}" selected>{{ $selectedActivity }}</option>
                                            @endif
                                            @foreach ($activityOptions as $activity)
                                                <option value="{{ $activity }}" @selected($selectedActivity === $activity)>{{ $activity }}</option>
                                            @endforeach
                                        </select>
                                        <span class="small">(For calculate TDEE)</span>
                                        @error('examination_details.activity')
                                            <div class="invalid-feedback d-block" id="exam_activity-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="app-prescription-examination-metric-actions d-flex flex-wrap gap-2">
                                    <button class="btn btn-light btn-sm prescription-examination-metric-toggle{{ $bmiExpanded ? ' active' : '' }}"
                                        type="button" aria-controls="exam-bmi-row" aria-expanded="{{ $bmiExpanded ? 'true' : 'false' }}">Show BMI</button>
                                    <button class="btn btn-light btn-sm prescription-examination-metric-toggle{{ $bsaExpanded ? ' active' : '' }}"
                                        type="button" aria-controls="exam-bsa-row" aria-expanded="{{ $bsaExpanded ? 'true' : 'false' }}">Show BSA</button>
                                    <button class="btn btn-light btn-sm prescription-examination-metric-toggle{{ $bmrExpanded ? ' active' : '' }}"
                                        type="button" aria-controls="exam-bmr-row" aria-expanded="{{ $bmrExpanded ? 'true' : 'false' }}">Show BMR</button>
                                    <button class="btn btn-light btn-sm prescription-examination-metric-toggle{{ $tdeeExpanded ? ' active' : '' }}"
                                        type="button" aria-controls="exam-tdee-row" aria-expanded="{{ $tdeeExpanded ? 'true' : 'false' }}">Show TDEE</button>
                                </div>
                                <div class="app-prescription-examination-row" id="exam-bmi-row" @if (! $bmiExpanded) hidden @endif>
                                    <label class="app-prescription-examination-label" for="exam_bmi">BMI:</label>
                                    <div class="app-prescription-examination-control d-flex flex-wrap align-items-center">
                                        <div class="input-group app-prescription-examination-input">
                                            <input id="exam_bmi" name="examination_details[bmi]" form="prescription-form"
                                                maxlength="50" inputmode="decimal" class="{{ inputClass('examination_details.bmi') }}"
                                                value="{{ getValue('examination_details.bmi', $record) }}"
                                                {{ inputAttrs('examination_details.bmi', 'exam_bmi') }}>
                                        </div>
                                        @error('examination_details.bmi')
                                            <div class="invalid-feedback d-block" id="exam_bmi-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="app-prescription-examination-row" id="exam-bsa-row" @if (! $bsaExpanded) hidden @endif>
                                    <label class="app-prescription-examination-label" for="exam_bsa">BSA:</label>
                                    <div class="app-prescription-examination-control d-flex flex-wrap align-items-center">
                                        <div class="input-group app-prescription-examination-input">
                                            <input id="exam_bsa" name="examination_details[bsa]" form="prescription-form"
                                                maxlength="50" inputmode="decimal" class="{{ inputClass('examination_details.bsa') }}"
                                                value="{{ getValue('examination_details.bsa', $record) }}"
                                                {{ inputAttrs('examination_details.bsa', 'exam_bsa') }}>
                                        </div>
                                        @error('examination_details.bsa')
                                            <div class="invalid-feedback d-block" id="exam_bsa-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="app-prescription-examination-row" id="exam-bmr-row" @if (! $bmrExpanded) hidden @endif>
                                    <label class="app-prescription-examination-label" for="exam_bmr">BMR:</label>
                                    <div class="app-prescription-examination-control d-flex flex-wrap align-items-center">
                                        <div class="input-group app-prescription-examination-input">
                                            <input id="exam_bmr" name="examination_details[bmr]" form="prescription-form"
                                                maxlength="50" inputmode="decimal" class="{{ inputClass('examination_details.bmr') }}"
                                                value="{{ getValue('examination_details.bmr', $record) }}"
                                                {{ inputAttrs('examination_details.bmr', 'exam_bmr') }}>
                                        </div>
                                        @error('examination_details.bmr')
                                            <div class="invalid-feedback d-block" id="exam_bmr-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="app-prescription-examination-row" id="exam-tdee-row" @if (! $tdeeExpanded) hidden @endif>
                                    <label class="app-prescription-examination-label" for="exam_tdee">TDEE:</label>
                                    <div class="app-prescription-examination-control d-flex flex-wrap align-items-center">
                                        <div class="input-group app-prescription-examination-input">
                                            <input id="exam_tdee" name="examination_details[tdee]" form="prescription-form"
                                                maxlength="50" inputmode="decimal" class="{{ inputClass('examination_details.tdee') }}"
                                                value="{{ getValue('examination_details.tdee', $record) }}"
                                                {{ inputAttrs('examination_details.tdee', 'exam_tdee') }}>
                                        </div>
                                        @error('examination_details.tdee')
                                            <div class="invalid-feedback d-block" id="exam_tdee-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="app-prescription-examination-row">
                                    <label class="app-prescription-examination-label" for="exam_ideal_weight">Ideal weight:</label>
                                    <div class="app-prescription-examination-control d-flex flex-wrap align-items-center">
                                        <div class="input-group app-prescription-examination-input">
                                            <input id="exam_ideal_weight" name="examination_details[ideal_weight]" form="prescription-form"
                                                maxlength="50" inputmode="decimal" class="{{ inputClass('examination_details.ideal_weight') }}"
                                                value="{{ getValue('examination_details.ideal_weight', $record) }}"
                                                {{ inputAttrs('examination_details.ideal_weight', 'exam_ideal_weight') }}>
                                            <span class="input-group-text">kg</span>
                                        </div>
                                        @error('examination_details.ideal_weight')
                                            <div class="invalid-feedback d-block" id="exam_ideal_weight-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="app-prescription-examination-row">
                                    <label class="app-prescription-examination-label" for="exam_ideal_bmi">Ideal BMI:</label>
                                    <div class="app-prescription-examination-control d-flex flex-wrap align-items-center">
                                        <div class="input-group app-prescription-examination-input">
                                            <input id="exam_ideal_bmi" name="examination_details[ideal_bmi]" form="prescription-form"
                                                maxlength="50" inputmode="decimal" class="{{ inputClass('examination_details.ideal_bmi') }}"
                                                value="{{ getValue('examination_details.ideal_bmi', $record) }}"
                                                {{ inputAttrs('examination_details.ideal_bmi', 'exam_ideal_bmi') }}>
                                        </div>
                                        @error('examination_details.ideal_bmi')
                                            <div class="invalid-feedback d-block" id="exam_ideal_bmi-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="app-prescription-examination-row">
                                    <label class="app-prescription-examination-label" for="exam_extra_calories">Extra Cal:</label>
                                    <div class="app-prescription-examination-control d-flex flex-wrap align-items-center">
                                        <div class="input-group app-prescription-examination-input app-prescription-examination-short">
                                            <input id="exam_extra_calories" name="examination_details[extra_calories]" form="prescription-form"
                                                maxlength="50" inputmode="decimal" class="{{ inputClass('examination_details.extra_calories') }}"
                                                value="{{ getValue('examination_details.extra_calories', $record) }}"
                                                {{ inputAttrs('examination_details.extra_calories', 'exam_extra_calories') }}>
                                            <span class="input-group-text">Cal</span>
                                        </div>
                                        @error('examination_details.extra_calories')
                                            <div class="invalid-feedback d-block" id="exam_extra_calories-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="app-prescription-examination-row">
                                    <label class="app-prescription-examination-label" for="exam_target_calories">Target daily Cal:</label>
                                    <div class="app-prescription-examination-control d-flex flex-wrap align-items-center">
                                        <div class="input-group app-prescription-examination-input app-prescription-examination-short">
                                            <input id="exam_target_calories" name="examination_details[target_calories]" form="prescription-form"
                                                maxlength="50" inputmode="decimal" class="{{ inputClass('examination_details.target_calories') }}"
                                                value="{{ getValue('examination_details.target_calories', $record) }}"
                                                {{ inputAttrs('examination_details.target_calories', 'exam_target_calories') }}>
                                            <span class="input-group-text">Cal</span>
                                        </div>
                                        @error('examination_details.target_calories')
                                            <div class="invalid-feedback d-block" id="exam_target_calories-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="app-prescription-examination-row">
                                    <label class="app-prescription-examination-label" for="exam_spo2">SpO<sub>2</sub>:</label>
                                    <div class="app-prescription-examination-control d-flex flex-wrap align-items-center">
                                        <div class="input-group app-prescription-examination-input app-prescription-examination-short">
                                            <input id="exam_spo2" name="examination_details[spo2]" form="prescription-form"
                                                maxlength="50" inputmode="decimal" class="{{ inputClass('examination_details.spo2') }}"
                                                value="{{ getValue('examination_details.spo2', $record) }}"
                                                {{ inputAttrs('examination_details.spo2', 'exam_spo2') }}>
                                            <span class="input-group-text">%</span>
                                        </div>
                                        @error('examination_details.spo2')
                                            <div class="invalid-feedback d-block" id="exam_spo2-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade show active" id="visual-acuity-pane" role="tabpanel"
                            aria-labelledby="visual-acuity-tab" tabindex="0">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered app-glass-eye-table align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-nowrap" scope="col">Eye</th>
                                            <th class="text-center" scope="col">Without glasses</th>
                                            <th class="text-center" scope="col">With glasses</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th class="text-nowrap" scope="row">
                                                OD <span class="small text-body-secondary">(Right)</span>
                                            </th>
                                            <td>
                                                <label class="visually-hidden" for="exam_va_od_without_glasses">
                                                    OD visual acuity without glasses
                                                </label>
                                                <input class="{{ inputClass('examination_details.visual_acuity.od_without_glasses', 'form-control form-control-sm') }}"
                                                    id="exam_va_od_without_glasses" type="text"
                                                    name="examination_details[visual_acuity][od_without_glasses]"
                                                    form="prescription-form" maxlength="50" placeholder="6/6"
                                                    value="{{ getValue('examination_details.visual_acuity.od_without_glasses', $record) }}"
                                                    {{ inputAttrs('examination_details.visual_acuity.od_without_glasses', 'exam_va_od_without_glasses') }}>
                                                @error('examination_details.visual_acuity.od_without_glasses')
                                                        <div class="invalid-feedback d-block" id="exam_va_od_without_glasses-error">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <label class="visually-hidden" for="exam_va_od_with_glasses">
                                                    OD visual acuity with glasses
                                                </label>
                                                <input class="{{ inputClass('examination_details.visual_acuity.od_with_glasses', 'form-control form-control-sm') }}"
                                                    id="exam_va_od_with_glasses" type="text"
                                                    name="examination_details[visual_acuity][od_with_glasses]"
                                                    form="prescription-form" maxlength="50" placeholder="6/6"
                                                    value="{{ getValue('examination_details.visual_acuity.od_with_glasses', $record) }}"
                                                    {{ inputAttrs('examination_details.visual_acuity.od_with_glasses', 'exam_va_od_with_glasses') }}>
                                                @error('examination_details.visual_acuity.od_with_glasses')
                                                        <div class="invalid-feedback d-block" id="exam_va_od_with_glasses-error">{{ $message }}</div>
                                                @enderror
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-nowrap" scope="row">
                                                OS <span class="small text-body-secondary">(Left)</span>
                                            </th>
                                            <td>
                                                <label class="visually-hidden" for="exam_va_os_without_glasses">
                                                    OS visual acuity without glasses
                                                </label>
                                                <input class="{{ inputClass('examination_details.visual_acuity.os_without_glasses', 'form-control form-control-sm') }}"
                                                    id="exam_va_os_without_glasses" type="text"
                                                    name="examination_details[visual_acuity][os_without_glasses]"
                                                    form="prescription-form" maxlength="50" placeholder="6/6"
                                                    value="{{ getValue('examination_details.visual_acuity.os_without_glasses', $record) }}"
                                                    {{ inputAttrs('examination_details.visual_acuity.os_without_glasses', 'exam_va_os_without_glasses') }}>
                                                @error('examination_details.visual_acuity.os_without_glasses')
                                                        <div class="invalid-feedback d-block" id="exam_va_os_without_glasses-error">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <label class="visually-hidden" for="exam_va_os_with_glasses">
                                                    OS visual acuity with glasses
                                                </label>
                                                <input class="{{ inputClass('examination_details.visual_acuity.os_with_glasses', 'form-control form-control-sm') }}"
                                                    id="exam_va_os_with_glasses" type="text"
                                                    name="examination_details[visual_acuity][os_with_glasses]"
                                                    form="prescription-form" maxlength="50" placeholder="6/6"
                                                    value="{{ getValue('examination_details.visual_acuity.os_with_glasses', $record) }}"
                                                    {{ inputAttrs('examination_details.visual_acuity.os_with_glasses', 'exam_va_os_with_glasses') }}>
                                                @error('examination_details.visual_acuity.os_with_glasses')
                                                        <div class="invalid-feedback d-block" id="exam_va_os_with_glasses-error">{{ $message }}</div>
                                                @enderror
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="eye-examination-pane" role="tabpanel"
                            aria-labelledby="eye-examination-tab" tabindex="0">
                            <div class="row g-3">
                                <div class="col-12">
                                    <h3 class="h6 mb-2">Anterior Segment</h3>
                                    <div class="row g-2">
                                        <div class="col-12 col-md-6">
                                            <label class="form-label" for="exam_anterior_segment_od">OD (Right Eye)</label>
                                            <textarea class="{{ inputClass('examination_details.anterior_segment.od') }}"
                                                id="exam_anterior_segment_od" name="examination_details[anterior_segment][od]"
                                                form="prescription-form" rows="2" maxlength="500"
                                                {{ inputAttrs('examination_details.anterior_segment.od', 'exam_anterior_segment_od') }}>{{ getValue('examination_details.anterior_segment.od', $record) }}</textarea>
                                            @error('examination_details.anterior_segment.od')
                                                <div class="invalid-feedback d-block" id="exam_anterior_segment_od-error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label" for="exam_anterior_segment_os">OS (Left Eye)</label>
                                            <textarea class="{{ inputClass('examination_details.anterior_segment.os') }}"
                                                id="exam_anterior_segment_os" name="examination_details[anterior_segment][os]"
                                                form="prescription-form" rows="2" maxlength="500"
                                                {{ inputAttrs('examination_details.anterior_segment.os', 'exam_anterior_segment_os') }}>{{ getValue('examination_details.anterior_segment.os', $record) }}</textarea>
                                            @error('examination_details.anterior_segment.os')
                                                <div class="invalid-feedback d-block" id="exam_anterior_segment_os-error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <h3 class="h6 mb-2">IOP (Intraocular Pressure)</h3>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <label class="form-label" for="exam_iop_od">OD (mmHg)</label>
                                            <input class="{{ inputClass('examination_details.iop.od') }}"
                                                id="exam_iop_od" name="examination_details[iop][od]" type="text"
                                                form="prescription-form" maxlength="50" inputmode="decimal"
                                                value="{{ getValue('examination_details.iop.od', $record) }}"
                                                {{ inputAttrs('examination_details.iop.od', 'exam_iop_od') }}>
                                            @error('examination_details.iop.od')
                                                <div class="invalid-feedback d-block" id="exam_iop_od-error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label" for="exam_iop_os">OS (mmHg)</label>
                                            <input class="{{ inputClass('examination_details.iop.os') }}"
                                                id="exam_iop_os" name="examination_details[iop][os]" type="text"
                                                form="prescription-form" maxlength="50" inputmode="decimal"
                                                value="{{ getValue('examination_details.iop.os', $record) }}"
                                                {{ inputAttrs('examination_details.iop.os', 'exam_iop_os') }}>
                                            @error('examination_details.iop.os')
                                                <div class="invalid-feedback d-block" id="exam_iop_os-error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <h3 class="h6 mb-2">Fluorescein (FL) Stain</h3>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <label class="form-label" for="exam_fluorescein_od">OD</label>
                                            <input class="{{ inputClass('examination_details.fluorescein_stain.od') }}"
                                                id="exam_fluorescein_od"
                                                name="examination_details[fluorescein_stain][od]" type="text"
                                                form="prescription-form" maxlength="500"
                                                value="{{ getValue('examination_details.fluorescein_stain.od', $record) }}"
                                                {{ inputAttrs('examination_details.fluorescein_stain.od', 'exam_fluorescein_od') }}>
                                            @error('examination_details.fluorescein_stain.od')
                                                <div class="invalid-feedback d-block" id="exam_fluorescein_od-error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label" for="exam_fluorescein_os">OS</label>
                                            <input class="{{ inputClass('examination_details.fluorescein_stain.os') }}"
                                                id="exam_fluorescein_os"
                                                name="examination_details[fluorescein_stain][os]" type="text"
                                                form="prescription-form" maxlength="500"
                                                value="{{ getValue('examination_details.fluorescein_stain.os', $record) }}"
                                                {{ inputAttrs('examination_details.fluorescein_stain.os', 'exam_fluorescein_os') }}>
                                            @error('examination_details.fluorescein_stain.os')
                                                <div class="invalid-feedback d-block" id="exam_fluorescein_os-error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <h3 class="h6 mb-2">Schirmer's Test</h3>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <label class="form-label" for="exam_schirmer_od">OD (mm)</label>
                                            <input class="{{ inputClass('examination_details.schirmer_test.od') }}"
                                                id="exam_schirmer_od" name="examination_details[schirmer_test][od]"
                                                type="text" form="prescription-form" maxlength="50" inputmode="decimal"
                                                value="{{ getValue('examination_details.schirmer_test.od', $record) }}"
                                                {{ inputAttrs('examination_details.schirmer_test.od', 'exam_schirmer_od') }}>
                                            @error('examination_details.schirmer_test.od')
                                                <div class="invalid-feedback d-block" id="exam_schirmer_od-error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label" for="exam_schirmer_os">OS (mm)</label>
                                            <input class="{{ inputClass('examination_details.schirmer_test.os') }}"
                                                id="exam_schirmer_os" name="examination_details[schirmer_test][os]"
                                                type="text" form="prescription-form" maxlength="50" inputmode="decimal"
                                                value="{{ getValue('examination_details.schirmer_test.os', $record) }}"
                                                {{ inputAttrs('examination_details.schirmer_test.os', 'exam_schirmer_os') }}>
                                            @error('examination_details.schirmer_test.os')
                                                <div class="invalid-feedback d-block" id="exam_schirmer_os-error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <h3 class="h6 mb-2">Fundus Oculi</h3>
                                    <div class="row g-2">
                                        <div class="col-12">
                                            <label class="form-label" for="exam_fundus_od">OD</label>
                                            <textarea class="{{ inputClass('examination_details.fundus_oculi.od') }}"
                                                id="exam_fundus_od" name="examination_details[fundus_oculi][od]"
                                                form="prescription-form" rows="2" maxlength="500"
                                                {{ inputAttrs('examination_details.fundus_oculi.od', 'exam_fundus_od') }}>{{ getValue('examination_details.fundus_oculi.od', $record) }}</textarea>
                                            @error('examination_details.fundus_oculi.od')
                                                <div class="invalid-feedback d-block" id="exam_fundus_od-error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label" for="exam_fundus_os">OS</label>
                                            <textarea class="{{ inputClass('examination_details.fundus_oculi.os') }}"
                                                id="exam_fundus_os" name="examination_details[fundus_oculi][os]"
                                                form="prescription-form" rows="2" maxlength="500"
                                                {{ inputAttrs('examination_details.fundus_oculi.os', 'exam_fundus_os') }}>{{ getValue('examination_details.fundus_oculi.os', $record) }}</textarea>
                                            @error('examination_details.fundus_oculi.os')
                                                <div class="invalid-feedback d-block" id="exam_fundus_os-error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endpush
