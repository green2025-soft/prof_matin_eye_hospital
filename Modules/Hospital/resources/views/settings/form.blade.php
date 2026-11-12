<form class="app-card p-3 p-md-4" action="{{ $formAction }}" method="POST">
    @csrf
    @method('PUT')
    <fieldset class="app-form-section border-0 p-0 mb-0" aria-labelledby="settings-hospital-heading" @disabled(! $canUpdate)>
        <h2 class="app-form-section-title" id="settings-hospital-heading"><span class="app-form-section-icon"><i class="fa-solid fa-{{ $groupIcon }}" aria-hidden="true"></i></span>{{ $groupLabel }}</h2>
        @error('data')<div class="invalid-feedback d-block mb-3">{{ $message }}</div>@enderror

        <input name="section" type="hidden" value="{{ $activeHospitalSection }}">
        @if (! in_array($activeHospitalSection, ['patient', 'doctor', 'reference', 'sms'], true))
            <p class="small text-body-secondary mb-2">Numbers are generated automatically. Settings changes apply to future records.</p>
            <details class="mb-3">
                <summary class="small text-body-secondary">Pattern guide</summary>
                <p class="small text-body-secondary mt-2 mb-0" id="hospital-number-pattern-help">Patterns support {YYYY}, {MM}, {DD}, {BRANCH}, {DOCTOR}, and exactly one {SEQ} or {SEQ:1} to {SEQ:12} token. {SEQ} uses at least six digits. The number after SEQ sets its minimum digits. Use letters, numbers, spaces, . _ / - between tokens. Branch invoice patterns need {BRANCH}; yearly, monthly, and daily invoice resets also need the matching date tokens. Invoice numbers stay fixed; changing a doctor or date regenerates the serial only when its numbering series changes.</p>
            </details>
        @endif

        @if ($activeHospitalSection === 'patient')
            <section aria-labelledby="hospital-patient-heading">
                <h3 class="h6 mb-3" id="hospital-patient-heading">Patient</h3>
                <div class="row g-2">
                    <div class="col-md-4">
                        <label class="form-label" for="hospital-patient-id-scope">Patient ID numbering <span class="text-danger" aria-hidden="true">*</span></label>
                        <select class="{{ inputClass('data.id_scope', 'form-select') }}" id="hospital-patient-id-scope" name="data[id_scope]" required {{ inputAttrs('data.id_scope', 'hospital-patient-id-scope', 'hospital-patient-id-scope-help') }}>
                            <option value="tenant" {{ getSelected('tenant', 'data.id_scope', null, $values['data']['id_scope']) }}>Tenant-wide continuous</option>
                            <option value="branch" {{ getSelected('branch', 'data.id_scope', null, $values['data']['id_scope']) }}>Separate by branch</option>
                        </select>
                        @error('data.id_scope')<div class="invalid-feedback d-block" id="hospital-patient-id-scope-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="hospital-patient-id-pattern">Tenant ID pattern <span class="text-danger" aria-hidden="true">*</span></label>
                        <input class="{{ inputClass('data.patient_id_pattern') }}" id="hospital-patient-id-pattern" name="data[patient_id_pattern]" type="text" value="{{ getValue('data.patient_id_pattern', null, $values['data']['patient_id_pattern']) }}" maxlength="40" placeholder="P{SEQ:6}" required {{ inputAttrs('data.patient_id_pattern', 'hospital-patient-id-pattern', 'hospital-patient-pattern-help') }}>
                        @error('data.patient_id_pattern')<div class="invalid-feedback d-block" id="hospital-patient-id-pattern-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="hospital-patient-branch-id-pattern">Branch ID pattern <span class="text-danger" aria-hidden="true">*</span></label>
                        <input class="{{ inputClass('data.patient_branch_id_pattern') }}" id="hospital-patient-branch-id-pattern" name="data[patient_branch_id_pattern]" type="text" value="{{ getValue('data.patient_branch_id_pattern', null, $values['data']['patient_branch_id_pattern']) }}" maxlength="40" placeholder="B{BRANCH}-P{SEQ:6}" required {{ inputAttrs('data.patient_branch_id_pattern', 'hospital-patient-branch-id-pattern', 'hospital-patient-pattern-help') }}>
                        @error('data.patient_branch_id_pattern')<div class="invalid-feedback d-block" id="hospital-patient-branch-id-pattern-error">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="form-text" id="hospital-patient-id-scope-help">Changes apply only to new Patient IDs. Existing IDs and sequence counters are retained.</div>
                <div class="form-text" id="hospital-patient-pattern-help">Include exactly one {SEQ} or {SEQ:1} to {SEQ:12} token. You can also use {BRANCH}, {YYYY}, {MM}, and {DD}; branch patterns must include {BRANCH}.</div>
            </section>

        @elseif ($activeHospitalSection === 'doctor')
            <section aria-labelledby="hospital-doctor-heading">
                <h3 class="h6 mb-3" id="hospital-doctor-heading">Doctor</h3>
                <div class="row g-2">
                    <div class="col-md-4">
                        <label class="form-label" for="hospital-doctor-id-scope">Doctor ID numbering <span class="text-danger" aria-hidden="true">*</span></label>
                        <select class="{{ inputClass('data.doctor_id_scope', 'form-select') }}" id="hospital-doctor-id-scope" name="data[doctor_id_scope]" required {{ inputAttrs('data.doctor_id_scope', 'hospital-doctor-id-scope', 'hospital-doctor-id-scope-help') }}>
                            <option value="tenant" {{ getSelected('tenant', 'data.doctor_id_scope', null, $values['data']['doctor_id_scope']) }}>Tenant-wide continuous</option>
                            <option value="branch" {{ getSelected('branch', 'data.doctor_id_scope', null, $values['data']['doctor_id_scope']) }}>Separate by branch</option>
                        </select>
                        @error('data.doctor_id_scope')<div class="invalid-feedback d-block" id="hospital-doctor-id-scope-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="hospital-doctor-id-pattern">Tenant ID pattern <span class="text-danger" aria-hidden="true">*</span></label>
                        <input class="{{ inputClass('data.doctor_id_pattern') }}" id="hospital-doctor-id-pattern" name="data[doctor_id_pattern]" type="text" value="{{ getValue('data.doctor_id_pattern', null, $values['data']['doctor_id_pattern']) }}" maxlength="40" placeholder="D{SEQ:6}" required {{ inputAttrs('data.doctor_id_pattern', 'hospital-doctor-id-pattern', 'hospital-doctor-pattern-help') }}>
                        @error('data.doctor_id_pattern')<div class="invalid-feedback d-block" id="hospital-doctor-id-pattern-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="hospital-doctor-branch-id-pattern">Branch ID pattern <span class="text-danger" aria-hidden="true">*</span></label>
                        <input class="{{ inputClass('data.doctor_branch_id_pattern') }}" id="hospital-doctor-branch-id-pattern" name="data[doctor_branch_id_pattern]" type="text" value="{{ getValue('data.doctor_branch_id_pattern', null, $values['data']['doctor_branch_id_pattern']) }}" maxlength="40" placeholder="B{BRANCH}-D{SEQ:6}" required {{ inputAttrs('data.doctor_branch_id_pattern', 'hospital-doctor-branch-id-pattern', 'hospital-doctor-pattern-help') }}>
                        @error('data.doctor_branch_id_pattern')<div class="invalid-feedback d-block" id="hospital-doctor-branch-id-pattern-error">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="form-text" id="hospital-doctor-id-scope-help">Changes apply only to new Doctor IDs. Existing IDs and sequence counters are retained.</div>
                <div class="form-text" id="hospital-doctor-pattern-help">Include exactly one {SEQ} or {SEQ:1} to {SEQ:12} token. You can also use {BRANCH}, {YYYY}, {MM}, and {DD}; branch patterns must include {BRANCH}.</div>
            </section>

        @elseif ($activeHospitalSection === 'reference')
            @php
                $referenceDiscountModes = config('settings.reference_discount_modes');
                $referenceDueModes = config('settings.reference_due_modes');
            @endphp
            <section aria-label="Reference settings">
                <div class="row g-2">
                    <div class="col-md-4">
                        <label class="form-label" for="hospital-reference-id-scope">ID numbering <span class="text-danger" aria-hidden="true">*</span></label>
                        <select class="{{ inputClass('data.reference_id_scope', 'form-select') }}" id="hospital-reference-id-scope" name="data[reference_id_scope]" required {{ inputAttrs('data.reference_id_scope', 'hospital-reference-id-scope', 'hospital-reference-id-scope-help') }}>
                            <option value="tenant" {{ getSelected('tenant', 'data.reference_id_scope', null, $values['data']['reference_id_scope']) }}>Tenant-wide</option>
                            <option value="branch" {{ getSelected('branch', 'data.reference_id_scope', null, $values['data']['reference_id_scope']) }}>By branch</option>
                        </select>
                        @error('data.reference_id_scope')<div class="invalid-feedback d-block" id="hospital-reference-id-scope-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="hospital-reference-id-pattern">Tenant ID pattern <span class="text-danger" aria-hidden="true">*</span></label>
                        <input class="{{ inputClass('data.reference_id_pattern') }}" id="hospital-reference-id-pattern" name="data[reference_id_pattern]" type="text" value="{{ getValue('data.reference_id_pattern', null, $values['data']['reference_id_pattern']) }}" maxlength="40" placeholder="R{SEQ:6}" required {{ inputAttrs('data.reference_id_pattern', 'hospital-reference-id-pattern', 'hospital-reference-pattern-help') }}>
                        @error('data.reference_id_pattern')<div class="invalid-feedback d-block" id="hospital-reference-id-pattern-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="hospital-reference-branch-id-pattern">Branch ID pattern <span class="text-danger" aria-hidden="true">*</span></label>
                        <input class="{{ inputClass('data.reference_branch_id_pattern') }}" id="hospital-reference-branch-id-pattern" name="data[reference_branch_id_pattern]" type="text" value="{{ getValue('data.reference_branch_id_pattern', null, $values['data']['reference_branch_id_pattern']) }}" maxlength="40" placeholder="B{BRANCH}-R{SEQ:6}" required {{ inputAttrs('data.reference_branch_id_pattern', 'hospital-reference-branch-id-pattern', 'hospital-reference-pattern-help') }}>
                        @error('data.reference_branch_id_pattern')<div class="invalid-feedback d-block" id="hospital-reference-branch-id-pattern-error">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-3 my-3">
                    <div class="form-check form-switch">
                        <input name="data[reference_defaults_enabled]" type="hidden" value="0">
                        <input class="{{ inputClass('data.reference_defaults_enabled', 'form-check-input') }}" id="hospital-reference-defaults-enabled" name="data[reference_defaults_enabled]" type="checkbox" role="switch" value="1" {{ getChecked(1, 'data.reference_defaults_enabled', null, $values['data']['reference_defaults_enabled']) }} {{ inputAttrs('data.reference_defaults_enabled', 'hospital-reference-defaults-enabled', 'hospital-reference-defaults-help') }}>
                        <label class="form-check-label" for="hospital-reference-defaults-enabled">Use default rates</label>
                        @error('data.reference_defaults_enabled')<div class="invalid-feedback d-block" id="hospital-reference-defaults-enabled-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-check form-switch">
                        <input name="data[reference_percentages_enabled]" type="hidden" value="0">
                        <input class="{{ inputClass('data.reference_percentages_enabled', 'form-check-input') }}" id="hospital-reference-percentages-enabled" name="data[reference_percentages_enabled]" type="checkbox" role="switch" value="1" {{ getChecked(1, 'data.reference_percentages_enabled', null, $values['data']['reference_percentages_enabled']) }} {{ inputAttrs('data.reference_percentages_enabled', 'hospital-reference-percentages-enabled', 'hospital-reference-percentages-help') }}>
                        <label class="form-check-label" for="hospital-reference-percentages-enabled">Show % in Reference form</label>
                        @error('data.reference_percentages_enabled')<div class="invalid-feedback d-block" id="hospital-reference-percentages-enabled-error">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="row g-2 align-items-center border-top py-2" role="group" aria-labelledby="hospital-reference-consultation-settings-heading">
                    <div class="col-12 col-xl-2">
                        <h3 class="h6 mb-0" id="hospital-reference-consultation-settings-heading">Consultation</h3>
                    </div>
                    <div class="col-6 col-xl-2">
                        <label class="form-label small mb-1" for="hospital-reference-consultation-percent"><span class="visually-hidden">Consultation </span>Default %</label>
                        <input class="{{ inputClass('data.reference_consultation_percent') }}" id="hospital-reference-consultation-percent" name="data[reference_consultation_percent]" type="number" min="0" max="100" step="0.01" inputmode="decimal" value="{{ getValue('data.reference_consultation_percent', null, $values['data']['reference_consultation_percent']) }}" required {{ inputAttrs('data.reference_consultation_percent', 'hospital-reference-consultation-percent', 'hospital-reference-percent-help') }}>
                        @error('data.reference_consultation_percent')<div class="invalid-feedback d-block" id="hospital-reference-consultation-percent-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-6 col-xl-2">
                        <div class="form-check form-switch">
                            <input name="data[reference_consultation_percent_enabled]" type="hidden" value="0">
                            <input class="{{ inputClass('data.reference_consultation_percent_enabled', 'form-check-input') }}" id="hospital-reference-consultation-percent-enabled" name="data[reference_consultation_percent_enabled]" type="checkbox" role="switch" value="1" {{ getChecked(1, 'data.reference_consultation_percent_enabled', null, $values['data']['reference_consultation_percent_enabled']) }} {{ inputAttrs('data.reference_consultation_percent_enabled', 'hospital-reference-consultation-percent-enabled', 'hospital-reference-percentages-help') }}>
                            <label class="form-check-label" for="hospital-reference-consultation-percent-enabled">Show %<span class="visually-hidden"> for Consultation</span></label>
                            @error('data.reference_consultation_percent_enabled')<div class="invalid-feedback d-block" id="hospital-reference-consultation-percent-enabled-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-xl-3">
                        <label class="form-label small mb-1" for="hospital-reference-consultation-discount-mode"><span class="visually-hidden">Consultation </span>Discount rule</label>
                        <select class="{{ inputClass('data.reference_consultation_discount_mode', 'form-select') }}" id="hospital-reference-consultation-discount-mode" name="data[reference_consultation_discount_mode]" required {{ inputAttrs('data.reference_consultation_discount_mode', 'hospital-reference-consultation-discount-mode', 'hospital-reference-discount-rules-help') }}>
                            @foreach ($referenceDiscountModes as $mode => $label)
                                <option value="{{ $mode }}" {{ getSelected($mode, 'data.reference_consultation_discount_mode', null, $values['data']['reference_consultation_discount_mode']) }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('data.reference_consultation_discount_mode')<div class="invalid-feedback d-block" id="hospital-reference-consultation-discount-mode-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6 col-xl-3">
                        <label class="form-label small mb-1" for="hospital-reference-consultation-due-mode"><span class="visually-hidden">Consultation </span>Due rule</label>
                        <select class="{{ inputClass('data.reference_consultation_due_mode', 'form-select') }}" id="hospital-reference-consultation-due-mode" name="data[reference_consultation_due_mode]" required {{ inputAttrs('data.reference_consultation_due_mode', 'hospital-reference-consultation-due-mode', 'hospital-reference-due-rules-help') }}>
                            @foreach ($referenceDueModes as $mode => $label)
                                <option value="{{ $mode }}" {{ getSelected($mode, 'data.reference_consultation_due_mode', null, $values['data']['reference_consultation_due_mode']) }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('data.reference_consultation_due_mode')<div class="invalid-feedback d-block" id="hospital-reference-consultation-due-mode-error">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="row g-2 align-items-center border-top py-2" role="group" aria-labelledby="hospital-reference-investigation-settings-heading">
                    <div class="col-12 col-xl-2">
                        <h3 class="h6 mb-0" id="hospital-reference-investigation-settings-heading">Investigation</h3>
                    </div>
                    <div class="col-6 col-xl-2">
                        <label class="form-label small mb-1" for="hospital-reference-investigation-percent"><span class="visually-hidden">Investigation </span>Default %</label>
                        <input class="{{ inputClass('data.reference_investigation_percent') }}" id="hospital-reference-investigation-percent" name="data[reference_investigation_percent]" type="number" min="0" max="100" step="0.01" inputmode="decimal" value="{{ getValue('data.reference_investigation_percent', null, $values['data']['reference_investigation_percent']) }}" required {{ inputAttrs('data.reference_investigation_percent', 'hospital-reference-investigation-percent', 'hospital-reference-percent-help') }}>
                        @error('data.reference_investigation_percent')<div class="invalid-feedback d-block" id="hospital-reference-investigation-percent-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-6 col-xl-2">
                        <div class="form-check form-switch">
                            <input name="data[reference_investigation_percent_enabled]" type="hidden" value="0">
                            <input class="{{ inputClass('data.reference_investigation_percent_enabled', 'form-check-input') }}" id="hospital-reference-investigation-percent-enabled" name="data[reference_investigation_percent_enabled]" type="checkbox" role="switch" value="1" {{ getChecked(1, 'data.reference_investigation_percent_enabled', null, $values['data']['reference_investigation_percent_enabled']) }} {{ inputAttrs('data.reference_investigation_percent_enabled', 'hospital-reference-investigation-percent-enabled', 'hospital-reference-percentages-help') }}>
                            <label class="form-check-label" for="hospital-reference-investigation-percent-enabled">Show %<span class="visually-hidden"> for Investigation</span></label>
                            @error('data.reference_investigation_percent_enabled')<div class="invalid-feedback d-block" id="hospital-reference-investigation-percent-enabled-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-xl-3">
                        <label class="form-label small mb-1" for="hospital-reference-investigation-discount-mode"><span class="visually-hidden">Investigation </span>Discount rule</label>
                        <select class="{{ inputClass('data.reference_investigation_discount_mode', 'form-select') }}" id="hospital-reference-investigation-discount-mode" name="data[reference_investigation_discount_mode]" required {{ inputAttrs('data.reference_investigation_discount_mode', 'hospital-reference-investigation-discount-mode', 'hospital-reference-discount-rules-help') }}>
                            @foreach ($referenceDiscountModes as $mode => $label)
                                <option value="{{ $mode }}" {{ getSelected($mode, 'data.reference_investigation_discount_mode', null, $values['data']['reference_investigation_discount_mode']) }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('data.reference_investigation_discount_mode')<div class="invalid-feedback d-block" id="hospital-reference-investigation-discount-mode-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6 col-xl-3">
                        <label class="form-label small mb-1" for="hospital-reference-investigation-due-mode"><span class="visually-hidden">Investigation </span>Due rule</label>
                        <select class="{{ inputClass('data.reference_investigation_due_mode', 'form-select') }}" id="hospital-reference-investigation-due-mode" name="data[reference_investigation_due_mode]" required {{ inputAttrs('data.reference_investigation_due_mode', 'hospital-reference-investigation-due-mode', 'hospital-reference-due-rules-help') }}>
                            @foreach ($referenceDueModes as $mode => $label)
                                <option value="{{ $mode }}" {{ getSelected($mode, 'data.reference_investigation_due_mode', null, $values['data']['reference_investigation_due_mode']) }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('data.reference_investigation_due_mode')<div class="invalid-feedback d-block" id="hospital-reference-investigation-due-mode-error">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="row g-2 align-items-center border-top py-2" role="group" aria-labelledby="hospital-reference-operation-settings-heading">
                    <div class="col-12 col-xl-2">
                        <h3 class="h6 mb-0" id="hospital-reference-operation-settings-heading">Operation</h3>
                    </div>
                    <div class="col-6 col-xl-2">
                        <label class="form-label small mb-1" for="hospital-reference-operation-percent"><span class="visually-hidden">Operation </span>Default %</label>
                        <input class="{{ inputClass('data.reference_operation_percent') }}" id="hospital-reference-operation-percent" name="data[reference_operation_percent]" type="number" min="0" max="100" step="0.01" inputmode="decimal" value="{{ getValue('data.reference_operation_percent', null, $values['data']['reference_operation_percent']) }}" required {{ inputAttrs('data.reference_operation_percent', 'hospital-reference-operation-percent', 'hospital-reference-percent-help') }}>
                        @error('data.reference_operation_percent')<div class="invalid-feedback d-block" id="hospital-reference-operation-percent-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-6 col-xl-2">
                        <div class="form-check form-switch">
                            <input name="data[reference_operation_percent_enabled]" type="hidden" value="0">
                            <input class="{{ inputClass('data.reference_operation_percent_enabled', 'form-check-input') }}" id="hospital-reference-operation-percent-enabled" name="data[reference_operation_percent_enabled]" type="checkbox" role="switch" value="1" {{ getChecked(1, 'data.reference_operation_percent_enabled', null, $values['data']['reference_operation_percent_enabled']) }} {{ inputAttrs('data.reference_operation_percent_enabled', 'hospital-reference-operation-percent-enabled', 'hospital-reference-percentages-help') }}>
                            <label class="form-check-label" for="hospital-reference-operation-percent-enabled">Show %<span class="visually-hidden"> for Operation</span></label>
                            @error('data.reference_operation_percent_enabled')<div class="invalid-feedback d-block" id="hospital-reference-operation-percent-enabled-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-xl-3">
                        <label class="form-label small mb-1" for="hospital-reference-operation-discount-mode"><span class="visually-hidden">Operation </span>Discount rule</label>
                        <select class="{{ inputClass('data.reference_operation_discount_mode', 'form-select') }}" id="hospital-reference-operation-discount-mode" name="data[reference_operation_discount_mode]" required {{ inputAttrs('data.reference_operation_discount_mode', 'hospital-reference-operation-discount-mode', 'hospital-reference-discount-rules-help') }}>
                            @foreach ($referenceDiscountModes as $mode => $label)
                                <option value="{{ $mode }}" {{ getSelected($mode, 'data.reference_operation_discount_mode', null, $values['data']['reference_operation_discount_mode']) }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('data.reference_operation_discount_mode')<div class="invalid-feedback d-block" id="hospital-reference-operation-discount-mode-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6 col-xl-3">
                        <label class="form-label small mb-1" for="hospital-reference-operation-due-mode"><span class="visually-hidden">Operation </span>Due rule</label>
                        <select class="{{ inputClass('data.reference_operation_due_mode', 'form-select') }}" id="hospital-reference-operation-due-mode" name="data[reference_operation_due_mode]" required {{ inputAttrs('data.reference_operation_due_mode', 'hospital-reference-operation-due-mode', 'hospital-reference-due-rules-help') }}>
                            @foreach ($referenceDueModes as $mode => $label)
                                <option value="{{ $mode }}" {{ getSelected($mode, 'data.reference_operation_due_mode', null, $values['data']['reference_operation_due_mode']) }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('data.reference_operation_due_mode')<div class="invalid-feedback d-block" id="hospital-reference-operation-due-mode-error">{{ $message }}</div>@enderror
                    </div>
                </div>
                <p class="small text-body-secondary mt-2 mb-0">New rules apply to new invoices.</p>
                <details class="mt-2">
                    <summary class="small fw-semibold">Help</summary>
                    <div class="small text-body-secondary mt-2">
                        <p class="mb-2" id="hospital-reference-id-scope-help">ID changes apply to new References.</p>
                        <p class="mb-2" id="hospital-reference-pattern-help">Use one {SEQ} or {SEQ:1}–{SEQ:12}. Optional: {YYYY}, {MM}, {DD}, {BRANCH}. Branch patterns require {BRANCH}.</p>
                        <p class="mb-2" id="hospital-reference-defaults-help">Defaults prefill new References, including hidden rates. Turning defaults off starts new rates at zero.</p>
                        <p class="mb-2" id="hospital-reference-percent-help">Rates: 0–100%, with up to two decimal places.</p>
                        <p class="mb-2" id="hospital-reference-percentages-help">The main switch enables percentage fields; each service switch shows its own field. Hidden saved rates stay unchanged.</p>
                        <p class="mb-2" id="hospital-reference-discount-rules-help">Before discount: bill × rate. After discount: net bill × rate. Discount from commission: bill × rate − all discounts, minimum zero. Investigation extra discounts are included.</p>
                        <p class="mb-0" id="hospital-reference-due-rules-help">Full payment waits until due is zero. Due from commission subtracts outstanding due. As patient pays releases commission in proportion to receipts. Collections and refunds update availability; saved invoice rates and rules stay fixed.</p>
                    </div>
                </details>
            </section>

        @elseif ($activeHospitalSection === 'appointment')
            <section aria-labelledby="hospital-appointment-heading">
                <h3 class="h6 mb-3" id="hospital-appointment-heading">Appointment</h3>
                <div class="row g-2">
                    <div class="col-md-3">
                        <label class="form-label" for="hospital-appointment-serial-scope">Serial numbering <span class="text-danger" aria-hidden="true">*</span></label>
                        <select class="{{ inputClass('data.appointment_serial_scope', 'form-select') }}" id="hospital-appointment-serial-scope" name="data[appointment_serial_scope]" required {{ inputAttrs('data.appointment_serial_scope', 'hospital-appointment-serial-scope') }}>
                            <option value="tenant" {{ getSelected('tenant', 'data.appointment_serial_scope', null, $values['data']['appointment_serial_scope']) }}>Tenant-wide</option>
                            <option value="branch" {{ getSelected('branch', 'data.appointment_serial_scope', null, $values['data']['appointment_serial_scope']) }}>Separate by branch</option>
                            <option value="doctor" {{ getSelected('doctor', 'data.appointment_serial_scope', null, $values['data']['appointment_serial_scope']) }}>Separate by doctor</option>
                        </select>
                        @error('data.appointment_serial_scope')<div class="invalid-feedback d-block" id="hospital-appointment-serial-scope-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="hospital-appointment-serial-reset">Serial reset <span class="text-danger" aria-hidden="true">*</span></label>
                        <select class="{{ inputClass('data.appointment_serial_reset', 'form-select') }}" id="hospital-appointment-serial-reset" name="data[appointment_serial_reset]" required {{ inputAttrs('data.appointment_serial_reset', 'hospital-appointment-serial-reset') }}>
                            <option value="never" {{ getSelected('never', 'data.appointment_serial_reset', null, $values['data']['appointment_serial_reset']) }}>Never</option>
                            <option value="year" {{ getSelected('year', 'data.appointment_serial_reset', null, $values['data']['appointment_serial_reset']) }}>Yearly</option>
                            <option value="month" {{ getSelected('month', 'data.appointment_serial_reset', null, $values['data']['appointment_serial_reset']) }}>Monthly</option>
                            <option value="day" {{ getSelected('day', 'data.appointment_serial_reset', null, $values['data']['appointment_serial_reset']) }}>Daily</option>
                        </select>
                        @error('data.appointment_serial_reset')<div class="invalid-feedback d-block" id="hospital-appointment-serial-reset-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="hospital-appointment-serial-pattern">Serial pattern <span class="text-danger" aria-hidden="true">*</span></label>
                        <input class="{{ inputClass('data.appointment_serial_pattern') }}" id="hospital-appointment-serial-pattern" name="data[appointment_serial_pattern]" type="text" value="{{ getValue('data.appointment_serial_pattern', null, $values['data']['appointment_serial_pattern']) }}" maxlength="80" placeholder="{SEQ:3}" required {{ inputAttrs('data.appointment_serial_pattern', 'hospital-appointment-serial-pattern', 'hospital-number-pattern-help') }}>
                        @error('data.appointment_serial_pattern')<div class="invalid-feedback d-block" id="hospital-appointment-serial-pattern-error">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="row g-2 mt-2">
                    <div class="col-md-3">
                        <label class="form-label" for="hospital-appointment-invoice-scope">Invoice numbering <span class="text-danger" aria-hidden="true">*</span></label>
                        <select class="{{ inputClass('data.appointment_invoice_scope', 'form-select') }}" id="hospital-appointment-invoice-scope" name="data[appointment_invoice_scope]" required {{ inputAttrs('data.appointment_invoice_scope', 'hospital-appointment-invoice-scope') }}>
                            <option value="tenant" {{ getSelected('tenant', 'data.appointment_invoice_scope', null, $values['data']['appointment_invoice_scope']) }}>Tenant-wide</option>
                            <option value="branch" {{ getSelected('branch', 'data.appointment_invoice_scope', null, $values['data']['appointment_invoice_scope']) }}>Separate by branch</option>
                        </select>
                        @error('data.appointment_invoice_scope')<div class="invalid-feedback d-block" id="hospital-appointment-invoice-scope-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="hospital-appointment-invoice-reset">Invoice reset <span class="text-danger" aria-hidden="true">*</span></label>
                        <select class="{{ inputClass('data.appointment_invoice_reset', 'form-select') }}" id="hospital-appointment-invoice-reset" name="data[appointment_invoice_reset]" required {{ inputAttrs('data.appointment_invoice_reset', 'hospital-appointment-invoice-reset') }}>
                            <option value="never" {{ getSelected('never', 'data.appointment_invoice_reset', null, $values['data']['appointment_invoice_reset']) }}>Never</option>
                            <option value="year" {{ getSelected('year', 'data.appointment_invoice_reset', null, $values['data']['appointment_invoice_reset']) }}>Yearly</option>
                            <option value="month" {{ getSelected('month', 'data.appointment_invoice_reset', null, $values['data']['appointment_invoice_reset']) }}>Monthly</option>
                            <option value="day" {{ getSelected('day', 'data.appointment_invoice_reset', null, $values['data']['appointment_invoice_reset']) }}>Daily</option>
                        </select>
                        @error('data.appointment_invoice_reset')<div class="invalid-feedback d-block" id="hospital-appointment-invoice-reset-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="hospital-appointment-invoice-pattern">Invoice pattern <span class="text-danger" aria-hidden="true">*</span></label>
                        <input class="{{ inputClass('data.appointment_invoice_pattern') }}" id="hospital-appointment-invoice-pattern" name="data[appointment_invoice_pattern]" type="text" value="{{ getValue('data.appointment_invoice_pattern', null, $values['data']['appointment_invoice_pattern']) }}" maxlength="80" placeholder="APT-B{BRANCH}-{SEQ:6}" required {{ inputAttrs('data.appointment_invoice_pattern', 'hospital-appointment-invoice-pattern', 'hospital-number-pattern-help') }}>
                        @error('data.appointment_invoice_pattern')<div class="invalid-feedback d-block" id="hospital-appointment-invoice-pattern-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </section>

        @elseif ($activeHospitalSection === 'consultation')
            <section aria-labelledby="hospital-consultation-heading">
                <h3 class="h6 mb-3" id="hospital-consultation-heading">Consultation</h3>
                <div class="row mb-3">
                    <div class="col-sm-6 col-lg-4">
                        <label class="form-label" for="hospital-consultation-payment-default">New entry payment</label>
                        <select class="{{ inputClass('data.consultation_payment_default', 'form-select') }}"
                            id="hospital-consultation-payment-default" name="data[consultation_payment_default]" required
                            {{ inputAttrs('data.consultation_payment_default', 'hospital-consultation-payment-default') }}>
                            <option value="due" {{ getSelected('due', 'data.consultation_payment_default', null, $values['data']['consultation_payment_default']) }}>Due</option>
                            <option value="paid" {{ getSelected('paid', 'data.consultation_payment_default', null, $values['data']['consultation_payment_default']) }}>Paid</option>
                        </select>
                        @error('data.consultation_payment_default')<div class="invalid-feedback d-block" id="hospital-consultation-payment-default-error">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="form-check form-switch mb-3">
                    <input name="data[consultation_reference_enabled]" type="hidden" value="0">
                    <input class="{{ inputClass('data.consultation_reference_enabled', 'form-check-input') }}" id="hospital-consultation-reference-enabled" name="data[consultation_reference_enabled]" type="checkbox" role="switch" value="1" {{ getChecked(1, 'data.consultation_reference_enabled', null, $values['data']['consultation_reference_enabled']) }} {{ inputAttrs('data.consultation_reference_enabled', 'hospital-consultation-reference-enabled') }}>
                    <label class="form-check-label" for="hospital-consultation-reference-enabled">Show Reference in the Consultation form</label>
                    @error('data.consultation_reference_enabled')<div class="invalid-feedback d-block" id="hospital-consultation-reference-enabled-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-check mb-2">
                    <input name="data[consultation_serial_enabled]" type="hidden" value="0">
                    <input class="{{ inputClass('data.consultation_serial_enabled', 'form-check-input') }}" id="hospital-consultation-serial-enabled" name="data[consultation_serial_enabled]" type="checkbox" value="1" {{ getChecked(1, 'data.consultation_serial_enabled', null, $values['data']['consultation_serial_enabled']) }} {{ inputAttrs('data.consultation_serial_enabled', 'hospital-consultation-serial-enabled') }}>
                    <label class="form-check-label" for="hospital-consultation-serial-enabled">Generate consultation serial numbers</label>
                    @error('data.consultation_serial_enabled')<div class="invalid-feedback d-block" id="hospital-consultation-serial-enabled-error">{{ $message }}</div>@enderror
                </div>
                <div class="row g-2">
                    <div class="col-md-3">
                        <label class="form-label" for="hospital-consultation-serial-scope">Serial numbering <span class="text-danger" aria-hidden="true">*</span></label>
                        <select class="{{ inputClass('data.consultation_serial_scope', 'form-select') }}" id="hospital-consultation-serial-scope" name="data[consultation_serial_scope]" required {{ inputAttrs('data.consultation_serial_scope', 'hospital-consultation-serial-scope') }}>
                            <option value="tenant" {{ getSelected('tenant', 'data.consultation_serial_scope', null, $values['data']['consultation_serial_scope']) }}>Tenant-wide</option>
                            <option value="branch" {{ getSelected('branch', 'data.consultation_serial_scope', null, $values['data']['consultation_serial_scope']) }}>Separate by branch</option>
                            <option value="doctor" {{ getSelected('doctor', 'data.consultation_serial_scope', null, $values['data']['consultation_serial_scope']) }}>Separate by doctor</option>
                        </select>
                        @error('data.consultation_serial_scope')<div class="invalid-feedback d-block" id="hospital-consultation-serial-scope-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="hospital-consultation-serial-reset">Serial reset <span class="text-danger" aria-hidden="true">*</span></label>
                        <select class="{{ inputClass('data.consultation_serial_reset', 'form-select') }}" id="hospital-consultation-serial-reset" name="data[consultation_serial_reset]" required {{ inputAttrs('data.consultation_serial_reset', 'hospital-consultation-serial-reset') }}>
                            <option value="never" {{ getSelected('never', 'data.consultation_serial_reset', null, $values['data']['consultation_serial_reset']) }}>Never</option>
                            <option value="year" {{ getSelected('year', 'data.consultation_serial_reset', null, $values['data']['consultation_serial_reset']) }}>Yearly</option>
                            <option value="month" {{ getSelected('month', 'data.consultation_serial_reset', null, $values['data']['consultation_serial_reset']) }}>Monthly</option>
                            <option value="day" {{ getSelected('day', 'data.consultation_serial_reset', null, $values['data']['consultation_serial_reset']) }}>Daily</option>
                        </select>
                        @error('data.consultation_serial_reset')<div class="invalid-feedback d-block" id="hospital-consultation-serial-reset-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="hospital-consultation-serial-pattern">Serial pattern <span class="text-danger" aria-hidden="true">*</span></label>
                        <input class="{{ inputClass('data.consultation_serial_pattern') }}" id="hospital-consultation-serial-pattern" name="data[consultation_serial_pattern]" type="text" value="{{ getValue('data.consultation_serial_pattern', null, $values['data']['consultation_serial_pattern']) }}" maxlength="80" placeholder="{SEQ:3}" required {{ inputAttrs('data.consultation_serial_pattern', 'hospital-consultation-serial-pattern', 'hospital-number-pattern-help') }}>
                        @error('data.consultation_serial_pattern')<div class="invalid-feedback d-block" id="hospital-consultation-serial-pattern-error">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="form-check mt-3 mb-2">
                    <input name="data[consultation_invoice_enabled]" type="hidden" value="0">
                    <input class="{{ inputClass('data.consultation_invoice_enabled', 'form-check-input') }}" id="hospital-consultation-invoice-enabled" name="data[consultation_invoice_enabled]" type="checkbox" value="1" {{ getChecked(1, 'data.consultation_invoice_enabled', null, $values['data']['consultation_invoice_enabled']) }} {{ inputAttrs('data.consultation_invoice_enabled', 'hospital-consultation-invoice-enabled') }}>
                    <label class="form-check-label" for="hospital-consultation-invoice-enabled">Generate consultation invoice numbers</label>
                    @error('data.consultation_invoice_enabled')<div class="invalid-feedback d-block" id="hospital-consultation-invoice-enabled-error">{{ $message }}</div>@enderror
                </div>
                <div class="row g-2">
                    <div class="col-md-3">
                        <label class="form-label" for="hospital-consultation-invoice-scope">Invoice numbering <span class="text-danger" aria-hidden="true">*</span></label>
                        <select class="{{ inputClass('data.consultation_invoice_scope', 'form-select') }}" id="hospital-consultation-invoice-scope" name="data[consultation_invoice_scope]" required {{ inputAttrs('data.consultation_invoice_scope', 'hospital-consultation-invoice-scope') }}>
                            <option value="tenant" {{ getSelected('tenant', 'data.consultation_invoice_scope', null, $values['data']['consultation_invoice_scope']) }}>Tenant-wide</option>
                            <option value="branch" {{ getSelected('branch', 'data.consultation_invoice_scope', null, $values['data']['consultation_invoice_scope']) }}>Separate by branch</option>
                        </select>
                        @error('data.consultation_invoice_scope')<div class="invalid-feedback d-block" id="hospital-consultation-invoice-scope-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="hospital-consultation-invoice-reset">Invoice reset <span class="text-danger" aria-hidden="true">*</span></label>
                        <select class="{{ inputClass('data.consultation_invoice_reset', 'form-select') }}" id="hospital-consultation-invoice-reset" name="data[consultation_invoice_reset]" required {{ inputAttrs('data.consultation_invoice_reset', 'hospital-consultation-invoice-reset') }}>
                            <option value="never" {{ getSelected('never', 'data.consultation_invoice_reset', null, $values['data']['consultation_invoice_reset']) }}>Never</option>
                            <option value="year" {{ getSelected('year', 'data.consultation_invoice_reset', null, $values['data']['consultation_invoice_reset']) }}>Yearly</option>
                            <option value="month" {{ getSelected('month', 'data.consultation_invoice_reset', null, $values['data']['consultation_invoice_reset']) }}>Monthly</option>
                            <option value="day" {{ getSelected('day', 'data.consultation_invoice_reset', null, $values['data']['consultation_invoice_reset']) }}>Daily</option>
                        </select>
                        @error('data.consultation_invoice_reset')<div class="invalid-feedback d-block" id="hospital-consultation-invoice-reset-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="hospital-consultation-invoice-pattern">Invoice pattern <span class="text-danger" aria-hidden="true">*</span></label>
                        <input class="{{ inputClass('data.consultation_invoice_pattern') }}" id="hospital-consultation-invoice-pattern" name="data[consultation_invoice_pattern]" type="text" value="{{ getValue('data.consultation_invoice_pattern', null, $values['data']['consultation_invoice_pattern']) }}" maxlength="80" placeholder="CON-B{BRANCH}-{SEQ:6}" required {{ inputAttrs('data.consultation_invoice_pattern', 'hospital-consultation-invoice-pattern', 'hospital-number-pattern-help') }}>
                        @error('data.consultation_invoice_pattern')<div class="invalid-feedback d-block" id="hospital-consultation-invoice-pattern-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </section>

        @elseif ($activeHospitalSection === 'investigation')
            <section aria-labelledby="hospital-investigation-heading">
                <h3 class="h6 mb-3" id="hospital-investigation-heading">Investigation</h3>
                <div class="row mb-3">
                    <div class="col-sm-6 col-lg-4">
                        <label class="form-label" for="hospital-investigation-payment-default">New entry payment</label>
                        <select class="{{ inputClass('data.investigation_payment_default', 'form-select') }}"
                            id="hospital-investigation-payment-default" name="data[investigation_payment_default]" required
                            {{ inputAttrs('data.investigation_payment_default', 'hospital-investigation-payment-default') }}>
                            <option value="due" {{ getSelected('due', 'data.investigation_payment_default', null, $values['data']['investigation_payment_default']) }}>Due</option>
                            <option value="paid" {{ getSelected('paid', 'data.investigation_payment_default', null, $values['data']['investigation_payment_default']) }}>Paid</option>
                        </select>
                        @error('data.investigation_payment_default')<div class="invalid-feedback d-block" id="hospital-investigation-payment-default-error">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="form-check form-switch mb-3">
                    <input name="data[investigation_reference_enabled]" type="hidden" value="0">
                    <input class="{{ inputClass('data.investigation_reference_enabled', 'form-check-input') }}" id="hospital-investigation-reference-enabled" name="data[investigation_reference_enabled]" type="checkbox" role="switch" value="1" {{ getChecked(1, 'data.investigation_reference_enabled', null, $values['data']['investigation_reference_enabled']) }} {{ inputAttrs('data.investigation_reference_enabled', 'hospital-investigation-reference-enabled') }}>
                    <label class="form-check-label" for="hospital-investigation-reference-enabled">Show Reference in the Patient Investigation form</label>
                    @error('data.investigation_reference_enabled')<div class="invalid-feedback d-block" id="hospital-investigation-reference-enabled-error">{{ $message }}</div>@enderror
                </div>
                <div class="row g-2">
                    <div class="col-md-3">
                        <label class="form-label" for="hospital-investigation-invoice-scope">Invoice numbering <span class="text-danger" aria-hidden="true">*</span></label>
                        <select class="{{ inputClass('data.investigation_invoice_scope', 'form-select') }}" id="hospital-investigation-invoice-scope" name="data[investigation_invoice_scope]" required {{ inputAttrs('data.investigation_invoice_scope', 'hospital-investigation-invoice-scope') }}>
                            <option value="tenant" {{ getSelected('tenant', 'data.investigation_invoice_scope', null, $values['data']['investigation_invoice_scope']) }}>Tenant-wide</option>
                            <option value="branch" {{ getSelected('branch', 'data.investigation_invoice_scope', null, $values['data']['investigation_invoice_scope']) }}>Separate by branch</option>
                        </select>
                        @error('data.investigation_invoice_scope')<div class="invalid-feedback d-block" id="hospital-investigation-invoice-scope-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="hospital-investigation-invoice-reset">Invoice reset <span class="text-danger" aria-hidden="true">*</span></label>
                        <select class="{{ inputClass('data.investigation_invoice_reset', 'form-select') }}" id="hospital-investigation-invoice-reset" name="data[investigation_invoice_reset]" required {{ inputAttrs('data.investigation_invoice_reset', 'hospital-investigation-invoice-reset') }}>
                            <option value="never" {{ getSelected('never', 'data.investigation_invoice_reset', null, $values['data']['investigation_invoice_reset']) }}>Never</option>
                            <option value="year" {{ getSelected('year', 'data.investigation_invoice_reset', null, $values['data']['investigation_invoice_reset']) }}>Yearly</option>
                            <option value="month" {{ getSelected('month', 'data.investigation_invoice_reset', null, $values['data']['investigation_invoice_reset']) }}>Monthly</option>
                            <option value="day" {{ getSelected('day', 'data.investigation_invoice_reset', null, $values['data']['investigation_invoice_reset']) }}>Daily</option>
                        </select>
                        @error('data.investigation_invoice_reset')<div class="invalid-feedback d-block" id="hospital-investigation-invoice-reset-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="hospital-investigation-invoice-pattern">Invoice pattern <span class="text-danger" aria-hidden="true">*</span></label>
                        <input class="{{ inputClass('data.investigation_invoice_pattern') }}" id="hospital-investigation-invoice-pattern" name="data[investigation_invoice_pattern]" type="text" value="{{ getValue('data.investigation_invoice_pattern', null, $values['data']['investigation_invoice_pattern']) }}" maxlength="80" placeholder="INV-B{BRANCH}-{SEQ:6}" required {{ inputAttrs('data.investigation_invoice_pattern', 'hospital-investigation-invoice-pattern', 'hospital-number-pattern-help') }}>
                        @error('data.investigation_invoice_pattern')<div class="invalid-feedback d-block" id="hospital-investigation-invoice-pattern-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </section>

        @elseif ($activeHospitalSection === 'operation')
            <section aria-labelledby="hospital-operation-heading">
                <h3 class="h6 mb-3" id="hospital-operation-heading">Operation</h3>
                <div class="row mb-3">
                    <div class="col-sm-6 col-lg-4">
                        <label class="form-label" for="hospital-operation-payment-default">New entry payment</label>
                        <select class="{{ inputClass('data.operation_payment_default', 'form-select') }}"
                            id="hospital-operation-payment-default" name="data[operation_payment_default]" required
                            {{ inputAttrs('data.operation_payment_default', 'hospital-operation-payment-default') }}>
                            <option value="due" {{ getSelected('due', 'data.operation_payment_default', null, $values['data']['operation_payment_default']) }}>Due</option>
                            <option value="paid" {{ getSelected('paid', 'data.operation_payment_default', null, $values['data']['operation_payment_default']) }}>Paid</option>
                        </select>
                        @error('data.operation_payment_default')<div class="invalid-feedback d-block" id="hospital-operation-payment-default-error">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="form-check form-switch mb-3">
                    <input name="data[operation_reference_enabled]" type="hidden" value="0">
                    <input class="{{ inputClass('data.operation_reference_enabled', 'form-check-input') }}" id="hospital-operation-reference-enabled" name="data[operation_reference_enabled]" type="checkbox" role="switch" value="1" {{ getChecked(1, 'data.operation_reference_enabled', null, $values['data']['operation_reference_enabled']) }} {{ inputAttrs('data.operation_reference_enabled', 'hospital-operation-reference-enabled') }}>
                    <label class="form-check-label" for="hospital-operation-reference-enabled">Show Reference in the Patient Operation form</label>
                    @error('data.operation_reference_enabled')<div class="invalid-feedback d-block" id="hospital-operation-reference-enabled-error">{{ $message }}</div>@enderror
                </div>
                <div class="row g-2">
                    <div class="col-md-3">
                        <label class="form-label" for="hospital-operation-invoice-scope">Invoice numbering <span class="text-danger" aria-hidden="true">*</span></label>
                        <select class="{{ inputClass('data.operation_invoice_scope', 'form-select') }}" id="hospital-operation-invoice-scope" name="data[operation_invoice_scope]" required {{ inputAttrs('data.operation_invoice_scope', 'hospital-operation-invoice-scope') }}>
                            <option value="tenant" {{ getSelected('tenant', 'data.operation_invoice_scope', null, $values['data']['operation_invoice_scope']) }}>Tenant-wide</option>
                            <option value="branch" {{ getSelected('branch', 'data.operation_invoice_scope', null, $values['data']['operation_invoice_scope']) }}>Separate by branch</option>
                        </select>
                        @error('data.operation_invoice_scope')<div class="invalid-feedback d-block" id="hospital-operation-invoice-scope-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="hospital-operation-invoice-reset">Invoice reset <span class="text-danger" aria-hidden="true">*</span></label>
                        <select class="{{ inputClass('data.operation_invoice_reset', 'form-select') }}" id="hospital-operation-invoice-reset" name="data[operation_invoice_reset]" required {{ inputAttrs('data.operation_invoice_reset', 'hospital-operation-invoice-reset') }}>
                            <option value="never" {{ getSelected('never', 'data.operation_invoice_reset', null, $values['data']['operation_invoice_reset']) }}>Never</option>
                            <option value="year" {{ getSelected('year', 'data.operation_invoice_reset', null, $values['data']['operation_invoice_reset']) }}>Yearly</option>
                            <option value="month" {{ getSelected('month', 'data.operation_invoice_reset', null, $values['data']['operation_invoice_reset']) }}>Monthly</option>
                            <option value="day" {{ getSelected('day', 'data.operation_invoice_reset', null, $values['data']['operation_invoice_reset']) }}>Daily</option>
                        </select>
                        @error('data.operation_invoice_reset')<div class="invalid-feedback d-block" id="hospital-operation-invoice-reset-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="hospital-operation-invoice-pattern">Invoice pattern <span class="text-danger" aria-hidden="true">*</span></label>
                        <input class="{{ inputClass('data.operation_invoice_pattern') }}" id="hospital-operation-invoice-pattern" name="data[operation_invoice_pattern]" type="text" value="{{ getValue('data.operation_invoice_pattern', null, $values['data']['operation_invoice_pattern']) }}" maxlength="80" placeholder="OP-B{BRANCH}-{SEQ:6}" required {{ inputAttrs('data.operation_invoice_pattern', 'hospital-operation-invoice-pattern', 'hospital-number-pattern-help') }}>
                        @error('data.operation_invoice_pattern')<div class="invalid-feedback d-block" id="hospital-operation-invoice-pattern-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </section>
        @elseif ($activeHospitalSection === 'refund')
            <section aria-labelledby="hospital-refund-heading">
                <h3 class="h6 mb-3" id="hospital-refund-heading">Refund</h3>
                <div class="row g-2">
                    <div class="col-md-3">
                        <label class="form-label" for="hospital-refund-invoice-scope">Invoice numbering <span class="text-danger" aria-hidden="true">*</span></label>
                        <select class="{{ inputClass('data.refund_invoice_scope', 'form-select') }}" id="hospital-refund-invoice-scope" name="data[refund_invoice_scope]" required {{ inputAttrs('data.refund_invoice_scope', 'hospital-refund-invoice-scope') }}>
                            <option value="tenant" {{ getSelected('tenant', 'data.refund_invoice_scope', null, $values['data']['refund_invoice_scope']) }}>Tenant-wide</option>
                            <option value="branch" {{ getSelected('branch', 'data.refund_invoice_scope', null, $values['data']['refund_invoice_scope']) }}>Separate by branch</option>
                        </select>
                        @error('data.refund_invoice_scope')<div class="invalid-feedback d-block" id="hospital-refund-invoice-scope-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="hospital-refund-invoice-reset">Invoice reset <span class="text-danger" aria-hidden="true">*</span></label>
                        <select class="{{ inputClass('data.refund_invoice_reset', 'form-select') }}" id="hospital-refund-invoice-reset" name="data[refund_invoice_reset]" required {{ inputAttrs('data.refund_invoice_reset', 'hospital-refund-invoice-reset') }}>
                            <option value="never" {{ getSelected('never', 'data.refund_invoice_reset', null, $values['data']['refund_invoice_reset']) }}>Never</option>
                            <option value="year" {{ getSelected('year', 'data.refund_invoice_reset', null, $values['data']['refund_invoice_reset']) }}>Yearly</option>
                            <option value="month" {{ getSelected('month', 'data.refund_invoice_reset', null, $values['data']['refund_invoice_reset']) }}>Monthly</option>
                            <option value="day" {{ getSelected('day', 'data.refund_invoice_reset', null, $values['data']['refund_invoice_reset']) }}>Daily</option>
                        </select>
                        @error('data.refund_invoice_reset')<div class="invalid-feedback d-block" id="hospital-refund-invoice-reset-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="hospital-refund-invoice-pattern">Invoice pattern <span class="text-danger" aria-hidden="true">*</span></label>
                        <input class="{{ inputClass('data.refund_invoice_pattern') }}" id="hospital-refund-invoice-pattern" name="data[refund_invoice_pattern]" type="text" value="{{ getValue('data.refund_invoice_pattern', null, $values['data']['refund_invoice_pattern']) }}" maxlength="80" placeholder="REF-{SEQ:6}" required {{ inputAttrs('data.refund_invoice_pattern', 'hospital-refund-invoice-pattern', 'hospital-number-pattern-help') }}>
                        @error('data.refund_invoice_pattern')<div class="invalid-feedback d-block" id="hospital-refund-invoice-pattern-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </section>
        @elseif ($activeHospitalSection === 'due_collection')
            <section aria-labelledby="hospital-due-collection-heading">
                <h3 class="h6 mb-3" id="hospital-due-collection-heading">Due Collection</h3>
                <div class="row g-2">
                    <div class="col-md-3">
                        <label class="form-label" for="hospital-due-collection-invoice-scope">Invoice numbering <span class="text-danger" aria-hidden="true">*</span></label>
                        <select class="{{ inputClass('data.due_collection_invoice_scope', 'form-select') }}" id="hospital-due-collection-invoice-scope" name="data[due_collection_invoice_scope]" required {{ inputAttrs('data.due_collection_invoice_scope', 'hospital-due-collection-invoice-scope') }}>
                            <option value="tenant" {{ getSelected('tenant', 'data.due_collection_invoice_scope', null, $values['data']['due_collection_invoice_scope']) }}>Tenant-wide</option>
                            <option value="branch" {{ getSelected('branch', 'data.due_collection_invoice_scope', null, $values['data']['due_collection_invoice_scope']) }}>Separate by branch</option>
                        </select>
                        @error('data.due_collection_invoice_scope')<div class="invalid-feedback d-block" id="hospital-due-collection-invoice-scope-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="hospital-due-collection-invoice-reset">Invoice reset <span class="text-danger" aria-hidden="true">*</span></label>
                        <select class="{{ inputClass('data.due_collection_invoice_reset', 'form-select') }}" id="hospital-due-collection-invoice-reset" name="data[due_collection_invoice_reset]" required {{ inputAttrs('data.due_collection_invoice_reset', 'hospital-due-collection-invoice-reset') }}>
                            <option value="never" {{ getSelected('never', 'data.due_collection_invoice_reset', null, $values['data']['due_collection_invoice_reset']) }}>Never</option>
                            <option value="year" {{ getSelected('year', 'data.due_collection_invoice_reset', null, $values['data']['due_collection_invoice_reset']) }}>Yearly</option>
                            <option value="month" {{ getSelected('month', 'data.due_collection_invoice_reset', null, $values['data']['due_collection_invoice_reset']) }}>Monthly</option>
                            <option value="day" {{ getSelected('day', 'data.due_collection_invoice_reset', null, $values['data']['due_collection_invoice_reset']) }}>Daily</option>
                        </select>
                        @error('data.due_collection_invoice_reset')<div class="invalid-feedback d-block" id="hospital-due-collection-invoice-reset-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="hospital-due-collection-invoice-pattern">Invoice pattern <span class="text-danger" aria-hidden="true">*</span></label>
                        <input class="{{ inputClass('data.due_collection_invoice_pattern') }}" id="hospital-due-collection-invoice-pattern" name="data[due_collection_invoice_pattern]" type="text" value="{{ getValue('data.due_collection_invoice_pattern', null, $values['data']['due_collection_invoice_pattern']) }}" maxlength="80" placeholder="DC-{SEQ:6}" required {{ inputAttrs('data.due_collection_invoice_pattern', 'hospital-due-collection-invoice-pattern', 'hospital-number-pattern-help') }}>
                        @error('data.due_collection_invoice_pattern')<div class="invalid-feedback d-block" id="hospital-due-collection-invoice-pattern-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </section>
        @elseif ($activeHospitalSection === 'sms')
            <section aria-labelledby="hospital-sms-heading">
                <h3 class="h6 mb-2" id="hospital-sms-heading">SMS notifications</h3>
                <p class="small text-body-secondary mb-3">Messages go to the patient's mobile. Editing a saved invoice does not send another message.</p>
                <p class="small text-body-secondary mb-3">
                    Organization: <code>{organization}</code>, <code>{organization_phone}</code>, <code>{organization_address}</code>.
                    Variables: <code>{name}</code>, <code>{invoice}</code>, <code>{date}</code>,
                    <code>{total}</code>, <code>{paid}</code>, <code>{due}</code>, <code>{amount}</code>.
                    Delivery also supports <code>{delivery_date}</code>.
                </p>
                <p class="small text-body-secondary mb-3">
                    Optional patient details: <code>{patient_name}</code>, <code>{patient_mobile}</code>,
                    <code>{patient_address}</code>, <code>{patient_id}</code>, <code>{patient_age}</code>,
                    <code>{father_husband_name}</code>. Add only the details you want in each message.
                </p>
                <p class="small text-body-secondary mb-3">For collections, Amount is the payment received. For refunds, Amount is the cash returned; Total and Due show the remaining invoice balance.</p>
                <div class="row g-3">
                    @foreach ($smsNotifications as $notification)
                        @php
                            $enabledField = 'data.'.$notification['enabled'];
                            $templateField = 'data.'.$notification['template'];
                            $enabledId = 'hospital-'.$notification['enabled'];
                            $templateId = 'hospital-'.$notification['template'];
                        @endphp
                        <div class="col-lg-6">
                            <section class="border rounded p-3 h-100">
                                <div class="form-check form-switch mb-1">
                                    <input name="data[{{ $notification['enabled'] }}]" type="hidden" value="0">
                                    <input class="{{ inputClass($enabledField, 'form-check-input') }}" id="{{ $enabledId }}"
                                        name="data[{{ $notification['enabled'] }}]" type="checkbox" role="switch" value="1"
                                        {{ getChecked(1, $enabledField, null, $values['data'][$notification['enabled']]) }}
                                        {{ inputAttrs($enabledField, $enabledId) }}>
                                    <label class="form-check-label fw-semibold" for="{{ $enabledId }}">{{ $notification['label'] }}</label>
                                    @error($enabledField)<div class="invalid-feedback d-block" id="{{ $enabledId }}-error">{{ $message }}</div>@enderror
                                </div>
                                <p class="small text-body-secondary mb-3">{{ $notification['description'] }}</p>
                                <label class="form-label" for="{{ $templateId }}">Message</label>
                                <textarea class="{{ inputClass($templateField) }}" id="{{ $templateId }}"
                                    name="data[{{ $notification['template'] }}]" rows="3" maxlength="1600" required
                                    {{ inputAttrs($templateField, $templateId) }}>{{ getValue($templateField, null, $values['data'][$notification['template']]) }}</textarea>
                                @error($templateField)<div class="invalid-feedback d-block" id="{{ $templateId }}-error">{{ $message }}</div>@enderror
                            </section>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </fieldset>
    @if ($canUpdate)
        <div class="d-flex justify-content-end border-top pt-2 mt-3">
            <button class="btn btn-primary" type="submit"><i class="fa-solid fa-check me-2" aria-hidden="true"></i>Save</button>
        </div>
    @else
        <p class="small text-body-secondary mb-0 mt-3">You have read-only access to these settings.</p>
    @endif
</form>
