<section class="app-prescription-note">
    <div class="app-prescription-section-heading">
        <h3>On examination</h3>
        <button class="btn btn-outline-primary btn-sm" type="button" aria-label="Edit examination"
            data-bs-toggle="modal" data-bs-target="#prescription-examination-modal">
            <i class="fa-solid fa-plus" aria-hidden="true"></i>
        </button>
    </div>
    <div class="app-prescription-section-preview" id="examination-preview">
        <ul id="examination-observation-preview"></ul>
        <ul id="examination-measurements-preview"></ul>
    </div>
</section>

@push('prescription-modals')
    <div class="modal fade app-prescription-section-modal prescription-examination-modal"
        id="prescription-examination-modal" tabindex="-1"
        aria-labelledby="prescription-examination-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title h5" id="prescription-examination-title">On examination</h2>
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
                        <button class="nav-link active" id="examination-observation-tab" type="button" role="tab"
                            data-bs-toggle="tab" data-bs-target="#examination-observation-pane"
                            aria-controls="examination-observation-pane" aria-selected="true">Observation</button>
                        <button class="nav-link" id="examination-general-tab" type="button" role="tab"
                            data-bs-toggle="tab" data-bs-target="#examination-general-pane"
                            aria-controls="examination-general-pane" aria-selected="false">General examination</button>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="examination-observation-pane" role="tabpanel"
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
                                    <div class="app-prescription-examination-control app-prescription-examination-bp">
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
                                    <div class="app-prescription-examination-control">
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
                                    <div class="app-prescription-examination-control">
                                        <div class="input-group app-prescription-examination-temperature">
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
                                    <div class="app-prescription-examination-control">
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
                                    <div class="app-prescription-examination-control">
                                        <div class="input-group app-prescription-examination-height">
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
                                    <div class="app-prescription-examination-control">
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
                                <div class="app-prescription-examination-metric-actions">
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
                                    <div class="app-prescription-examination-control">
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
                                    <div class="app-prescription-examination-control">
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
                                    <div class="app-prescription-examination-control">
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
                                    <div class="app-prescription-examination-control">
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
                                    <div class="app-prescription-examination-control">
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
                                    <div class="app-prescription-examination-control">
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
                                    <div class="app-prescription-examination-control">
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
                                    <div class="app-prescription-examination-control">
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
                                    <div class="app-prescription-examination-control">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endpush
