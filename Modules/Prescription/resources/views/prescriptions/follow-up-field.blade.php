@php
    $followUpDate = getValue('follow_up_date', null, $record->follow_up_date?->format('Y-m-d'));
    $followUpInterval = getValue('follow_up_interval', $record);
    $followUpUnit = getValue('follow_up_interval_unit', $record) ?? 'days';
@endphp

<section class="app-prescription-note">
    <div class="app-prescription-section-heading">
        <h3>Follow-up</h3>
        <button class="btn btn-outline-primary btn-sm" type="button"
            data-bs-toggle="modal" data-bs-target="#prescription-follow-up-modal"
            aria-label="Add or edit follow-up">
            <i class="fa-solid fa-plus" aria-hidden="true"></i>
        </button>
    </div>
    <ul class="app-prescription-section-preview" id="follow-up-preview"></ul>
</section>

@push('prescription-modals')
    <div class="modal fade app-prescription-section-modal" id="prescription-follow-up-modal"
        tabindex="-1" aria-labelledby="follow-up-modal-heading" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title h5" id="follow-up-modal-heading">Follow-up</h2>
                    @include('prescription::prescriptions.preset-search', ['pickerField' => 'follow_up_notes'])
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label class="visually-hidden" for="prescription-follow-up-interval">Come after</label>
                            <div class="input-group">
                                <input id="prescription-follow-up-interval" name="follow_up_interval"
                                    form="prescription-form" type="number" min="1" max="3650" step="1"
                                    class="{{ inputClass('follow_up_interval') }}"
                                    value="{{ $followUpInterval }}" inputmode="numeric"
                                    {{ inputAttrs('follow_up_interval', 'prescription-follow-up-interval') }}>
                                <label class="visually-hidden" for="prescription-follow-up-unit">Interval unit</label>
                                <select id="prescription-follow-up-unit" name="follow_up_interval_unit"
                                    form="prescription-form" class="{{ inputClass('follow_up_interval_unit', 'form-select') }}"
                                    {{ inputAttrs('follow_up_interval_unit', 'prescription-follow-up-unit') }}>
                                    <option value="days" @selected($followUpUnit === 'days')>দিন</option>
                                    <option value="months" @selected($followUpUnit === 'months')>মাস</option>
                                </select>
                                <span class="input-group-text">পর</span>
                            </div>
                            @error('follow_up_interval')
                                <div class="invalid-feedback d-block" id="prescription-follow-up-interval-error">
                                    {{ $message }}
                                </div>
                            @enderror
                            @error('follow_up_interval_unit')
                                <div class="invalid-feedback d-block" id="prescription-follow-up-unit-error">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="visually-hidden" for="follow_up_date">Follow-up date</label>
                            <div class="input-group" data-datepicker>
                                <input class="{{ inputClass('follow_up_date') }}" id="follow_up_date"
                                    name="follow_up_date" form="prescription-form" value="{{ $followUpDate }}"
                                    inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD"
                                    data-input {{ inputAttrs('follow_up_date') }}>
                                <button class="btn btn-outline-secondary" type="button" data-toggle
                                    aria-controls="follow_up_date" aria-label="Choose follow-up date">
                                    <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
                                </button>
                            </div>
                            @error('follow_up_date')
                                <div class="invalid-feedback d-block" id="follow_up_date-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    @include('prescription::prescriptions.preset-picker', [
                        'pickerField' => 'follow_up_notes',
                        'pickerValue' => getValue('follow_up_notes', $record),
                    ])
                </div>
            </div>
        </div>
    </div>
@endpush
