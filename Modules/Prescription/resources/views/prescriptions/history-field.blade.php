<section class="app-prescription-note">
    <div class="app-prescription-section-heading">
        <h3>History</h3>
        <button class="btn btn-outline-primary btn-sm" type="button" aria-label="Edit history"
            data-bs-toggle="modal" data-bs-target="#prescription-history-modal">
            <i class="fa-solid fa-plus" aria-hidden="true"></i>
        </button>
    </div>
    <div class="app-prescription-section-preview" id="history-preview">
        <ul id="history-general-preview"></ul>
        <section class="app-prescription-preview-group" hidden>
            <h4>D/H:</h4>
            <ul id="history-drug-preview"></ul>
        </section>
        <section class="app-prescription-preview-group" hidden>
            <h4>I/H:</h4>
            <ul id="history-investigation-preview"></ul>
        </section>
        <section class="app-prescription-preview-group" hidden>
            <h4>P/H:</h4>
            <ul id="history-personal-preview"></ul>
            <ul id="history-personal-notes-preview"></ul>
        </section>
        <section class="app-prescription-preview-group" hidden>
            <h4>F/H:</h4>
            <ul id="history-family-preview"></ul>
        </section>
        <section class="app-prescription-preview-group" hidden>
            <h4>Surgical:</h4>
            <ul id="history-surgical-preview"></ul>
        </section>
        <section class="app-prescription-preview-group" hidden>
            <h4>Allergy:</h4>
            <ul id="history-allergic-preview"></ul>
        </section>
        <section class="app-prescription-preview-group" hidden>
            <h4>Other:</h4>
            <ul id="history-other-preview"></ul>
        </section>
    </div>
</section>

@push('prescription-modals')
    <div class="modal fade app-prescription-section-modal prescription-history-modal"
        id="prescription-history-modal" tabindex="-1" aria-labelledby="prescription-history-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title h5" id="prescription-history-title">History</h2>
                    <div class="prescription-history-header-search">
                        @include('prescription::prescriptions.preset-search', ['pickerField' => 'history'])
                    </div>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @error('history_details')
                        <div class="invalid-feedback d-block mb-3">{{ $message }}</div>
                    @enderror
                    <div class="nav nav-tabs mb-3" role="tablist" aria-label="History sections">
                        <button class="nav-link active" id="history-general-tab" type="button" role="tab"
                            data-bs-toggle="tab" data-bs-target="#history-general-pane"
                            aria-controls="history-general-pane" aria-selected="true">General</button>
                        <button class="nav-link" id="history-drug-tab" type="button" role="tab"
                            data-bs-toggle="tab" data-bs-target="#history-drug-pane"
                            aria-controls="history-drug-pane" aria-selected="false">Drug</button>
                        <button class="nav-link" id="history-investigation-tab" type="button" role="tab"
                            data-bs-toggle="tab" data-bs-target="#history-investigation-pane"
                            aria-controls="history-investigation-pane" aria-selected="false">Investigation</button>
                        <button class="nav-link" id="history-personal-tab" type="button" role="tab"
                            data-bs-toggle="tab" data-bs-target="#history-personal-pane"
                            aria-controls="history-personal-pane" aria-selected="false">Personal</button>
                        <button class="nav-link" id="history-family-tab" type="button" role="tab"
                            data-bs-toggle="tab" data-bs-target="#history-family-pane"
                            aria-controls="history-family-pane" aria-selected="false">Family</button>
                        <button class="nav-link" id="history-others-tab" type="button" role="tab"
                            data-bs-toggle="tab" data-bs-target="#history-others-pane"
                            aria-controls="history-others-pane" aria-selected="false">Others</button>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="history-general-pane" role="tabpanel"
                            aria-labelledby="history-general-tab" tabindex="0">
                            @include('prescription::prescriptions.preset-picker', [
                                'pickerField' => 'history',
                                'pickerValue' => getValue('history', $record),
                            ])
                            @include('prescription::prescriptions.clinical-items', ['pickerField' => 'history'])
                        </div>
                        <div class="tab-pane fade" id="history-drug-pane" role="tabpanel"
                            aria-labelledby="history-drug-tab" tabindex="0">
                            <label class="form-label" for="prescription-history-medicine-search">Add medicine</label>
                            <select class="form-select" id="prescription-history-medicine-search"
                                data-url="{{ route('pharmacy.products.options', ['type' => 'medicine']) }}">
                                <option value=""></option>
                            </select>
                            <div class="app-prescription-history-selection">
                                <ul class="app-prescription-section-preview" id="history-drug-selection"></ul>
                            </div>
                            <textarea id="history_drug" name="history_details[drug]" hidden
                                form="prescription-form" class="{{ inputClass('history_details.drug') }}"
                                {{ inputAttrs('history_details.drug', 'history_drug') }}
                            >{{ getValue('history_details.drug', $record) }}</textarea>
                            @error('history_details.drug')
                                <div class="invalid-feedback d-block" id="history_drug-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="tab-pane fade" id="history-investigation-pane" role="tabpanel"
                            aria-labelledby="history-investigation-tab" tabindex="0">
                            <label class="form-label" for="prescription-history-investigation-search">
                                Add investigation
                            </label>
                            <select class="form-select" id="prescription-history-investigation-search"
                                data-url="{{ route('hospital.investigations.options') }}">
                                <option value=""></option>
                            </select>
                            <div class="app-prescription-history-selection">
                                <ul class="app-prescription-section-preview" id="history-investigation-selection"></ul>
                            </div>
                            <textarea id="history_investigation" name="history_details[investigation]" hidden
                                form="prescription-form"
                                class="{{ inputClass('history_details.investigation') }}"
                                {{ inputAttrs('history_details.investigation', 'history_investigation') }}
                            >{{ getValue('history_details.investigation', $record) }}</textarea>
                            @error('history_details.investigation')
                                <div class="invalid-feedback d-block" id="history_investigation-error">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="tab-pane fade" id="history-personal-pane" role="tabpanel"
                            aria-labelledby="history-personal-tab" tabindex="0">
                            <span class="form-label">P/H</span>
                            <div class="d-flex flex-wrap gap-3 mb-3">
                                <label class="form-check">
                                    <input id="history_smoker" type="checkbox" value="1" form="prescription-form"
                                        class="{{ inputClass('history_details.personal.smoker', 'form-check-input') }}"
                                        name="history_details[personal][smoker]"
                                        {{ inputAttrs('history_details.personal.smoker', 'history_smoker') }}
                                        {{ getChecked(1, 'history_details.personal.smoker', $record) }}>
                                    <span class="form-check-label">Smoker</span>
                                    @error('history_details.personal.smoker')
                                        <span class="invalid-feedback" id="history_smoker-error">{{ $message }}</span>
                                    @enderror
                                </label>
                                <label class="form-check">
                                    <input id="history_ex_smoker" type="checkbox" value="1" form="prescription-form"
                                        class="{{ inputClass('history_details.personal.ex_smoker', 'form-check-input') }}"
                                        name="history_details[personal][ex_smoker]"
                                        {{ inputAttrs('history_details.personal.ex_smoker', 'history_ex_smoker') }}
                                        {{ getChecked(1, 'history_details.personal.ex_smoker', $record) }}>
                                    <span class="form-check-label">Ex-smoker</span>
                                    @error('history_details.personal.ex_smoker')
                                        <span class="invalid-feedback" id="history_ex_smoker-error">{{ $message }}</span>
                                    @enderror
                                </label>
                                <label class="form-check">
                                    <input id="history_alcoholic" type="checkbox" value="1" form="prescription-form"
                                        class="{{ inputClass('history_details.personal.alcoholic', 'form-check-input') }}"
                                        name="history_details[personal][alcoholic]"
                                        {{ inputAttrs('history_details.personal.alcoholic', 'history_alcoholic') }}
                                        {{ getChecked(1, 'history_details.personal.alcoholic', $record) }}>
                                    <span class="form-check-label">Alcoholic</span>
                                    @error('history_details.personal.alcoholic')
                                        <span class="invalid-feedback" id="history_alcoholic-error">{{ $message }}</span>
                                    @enderror
                                </label>
                                <label class="form-check">
                                    <input id="history_tobacco" type="checkbox" value="1" form="prescription-form"
                                        class="{{ inputClass('history_details.personal.tobacco', 'form-check-input') }}"
                                        name="history_details[personal][tobacco]"
                                        {{ inputAttrs('history_details.personal.tobacco', 'history_tobacco') }}
                                        {{ getChecked(1, 'history_details.personal.tobacco', $record) }}>
                                    <span class="form-check-label">Tobacco</span>
                                    @error('history_details.personal.tobacco')
                                        <span class="invalid-feedback" id="history_tobacco-error">{{ $message }}</span>
                                    @enderror
                                </label>
                            </div>
                            @error('history_details.personal')
                                <div class="invalid-feedback d-block mb-2">{{ $message }}</div>
                            @enderror
                            <label class="form-label" for="history_personal_notes">Notes</label>
                            <textarea id="history_personal_notes" name="history_details[personal_notes]"
                                rows="3" maxlength="5000" form="prescription-form"
                                class="{{ inputClass('history_details.personal_notes') }}"
                                {{ inputAttrs('history_details.personal_notes', 'history_personal_notes') }}
                            >{{ getValue('history_details.personal_notes', $record) }}</textarea>
                            @error('history_details.personal_notes')
                                <div class="invalid-feedback d-block" id="history_personal_notes-error">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="tab-pane fade" id="history-family-pane" role="tabpanel"
                            aria-labelledby="history-family-tab" tabindex="0">
                            <label class="form-label" for="history_family">F/H</label>
                            <textarea id="history_family" name="history_details[family]" rows="3" maxlength="5000"
                                form="prescription-form" class="{{ inputClass('history_details.family') }}"
                                {{ inputAttrs('history_details.family', 'history_family') }}
                            >{{ getValue('history_details.family', $record) }}</textarea>
                            @error('history_details.family')
                                <div class="invalid-feedback d-block" id="history_family-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="tab-pane fade" id="history-others-pane" role="tabpanel"
                            aria-labelledby="history-others-tab" tabindex="0">
                            <div class="mb-3">
                                <label class="form-label" for="history_surgical">Surgical history</label>
                                <textarea id="history_surgical" name="history_details[surgical]" rows="2"
                                    maxlength="5000" form="prescription-form"
                                    class="{{ inputClass('history_details.surgical') }}"
                                    {{ inputAttrs('history_details.surgical', 'history_surgical') }}
                                >{{ getValue('history_details.surgical', $record) }}</textarea>
                                @error('history_details.surgical')
                                    <div class="invalid-feedback d-block" id="history_surgical-error">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="history_allergic">Allergic history</label>
                                <textarea id="history_allergic" name="history_details[allergic]" rows="2"
                                    maxlength="5000" form="prescription-form"
                                    class="{{ inputClass('history_details.allergic') }}"
                                    {{ inputAttrs('history_details.allergic', 'history_allergic') }}
                                >{{ getValue('history_details.allergic', $record) }}</textarea>
                                @error('history_details.allergic')
                                    <div class="invalid-feedback d-block" id="history_allergic-error">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <textarea id="history_other" name="history_details[other]" hidden
                                form="prescription-form" class="{{ inputClass('history_details.other') }}"
                                {{ inputAttrs('history_details.other', 'history_other') }}
                            >{{ getValue('history_details.other', $record) }}</textarea>
                            @error('history_details.other')
                                <div class="invalid-feedback d-block" id="history_other-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endpush
