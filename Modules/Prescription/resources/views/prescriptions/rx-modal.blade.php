<div class="modal fade app-prescription-section-modal" id="prescription-rx-modal"
    tabindex="-1" aria-labelledby="prescription-rx-modal-heading" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title h5" id="prescription-rx-modal-heading"><i class="fa-solid fa-prescription" aria-hidden="true"></i></h2>
                <div class="app-prescription-rx-search">
                    <label class="visually-hidden" for="prescription-medicine-search">Search / Add medicine</label>
                    <select id="prescription-medicine-search" multiple
                        data-url="{{ route('inventory.products.options', ['type' => 'medicine']) }}"></select>
                </div>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @error('medicines')
                    <div class="invalid-feedback d-block mb-3">{{ $message }}</div>
                @enderror
                <section id="prescription-rx-selection" aria-label="Choose medicines">
                    <div class="app-prescription-rx-options" id="prescription-rx-options"
                        aria-label="Medicine suggestions">
                        <p class="small text-body-secondary mb-0">Search and select all medicines above.</p>
                    </div>
                    <div class="d-flex align-items-center gap-2 mt-3 mb-2">
                        <h3 class="h6 mb-0">Selected medicines</h3>
                        <span class="badge text-bg-light" id="prescription-rx-selected-count" aria-live="polite">0</span>
                    </div>
                    <p class="small text-body-secondary mb-0" id="prescription-rx-selected-empty">No medicines selected.</p>
                    <ul class="app-prescription-selected-medicines" id="prescription-rx-selected"></ul>
                </section>
                <section class="d-none" id="prescription-rx-editor" aria-label="Medicine dose and duration">
                    <div class="app-prescription-medicine-navigation">
                        <label class="visually-hidden" for="prescription-rx-medicine-jump">Medicine to edit</label>
                        <select class="form-select form-select-sm" id="prescription-rx-medicine-jump"></select>
                        <span class="small text-body-secondary text-nowrap" id="prescription-rx-position"
                            aria-live="polite"></span>
                    </div>
                    <ol class="app-prescription-editor-medicines" id="prescription-medicines">
                        @foreach ($medicineRows as $medicineIndex => $medicine)
                            @include('prescription::prescriptions.medicine-row', [
                                'medicineIndex' => $medicineIndex,
                                'medicine' => $medicine,
                            ])
                        @endforeach
                    </ol>
                </section>
            </div>
            <div class="modal-footer gap-2 py-2">
                <div class="d-flex align-items-center gap-2 flex-grow-1" id="prescription-rx-selection-actions">
                    <small class="text-body-secondary">Select medicines · F6 Set doses</small>
                    <button class="btn btn-primary btn-sm ms-auto" id="prescription-rx-set-doses" type="button"
                        aria-keyshortcuts="F6" disabled>
                        Set doses
                    </button>
                </div>
                <div class="d-none d-flex align-items-center gap-2 flex-grow-1" id="prescription-rx-editor-actions">
                    <button class="btn btn-outline-secondary btn-sm" id="prescription-rx-choose-medicines" type="button">
                        Medicines
                    </button>
                    <small class="text-body-secondary d-none d-sm-block">0–6 Dose · Enter Next</small>
                    <button class="btn btn-outline-primary btn-sm ms-auto" id="prescription-rx-previous" type="button">
                        Previous
                    </button>
                    <button class="btn btn-primary btn-sm" id="prescription-rx-next" type="button">Next medicine</button>
                </div>
                <button class="btn btn-primary btn-sm" type="button" data-bs-dismiss="modal"
                    aria-keyshortcuts="F8" title="Close medicines (F8)">Done</button>
            </div>
        </div>
    </div>
    <div class="app-prescription-dose-palette bg-white border rounded shadow-sm" id="prescription-dose-palette"
        role="menu" aria-label="Choose a dose" hidden>
        @foreach (['0', '¼', '⅓', '½', '¾', '1', '1¼', '1½', '1¾', '2', '2½', '3', '3½', '4', '5', '6'] as $doseChoice)
            <button class="dropdown-item prescription-dose-choice" type="button" role="menuitem"
                value="{{ $doseChoice }}">{{ $doseChoice }}</button>
        @endforeach
    </div>
    <div class="app-prescription-unit-palette bg-white border rounded shadow-sm" id="prescription-unit-palette"
        role="menu" aria-label="Choose a unit" hidden
        data-url="{{ route('prescription.options.selector', ['section' => 'medicine_unit']) }}">
        <button class="dropdown-item prescription-unit-choice" type="button" role="menuitem" value="">—</button>
        @foreach (['টা', 'চামচ', 'চা চামচ', 'বার', 'টি', 'স্প্রে', 'লাগাবেন', 'ফোটা', 'স্টিক', '±২'] as $unitChoice)
            <button class="dropdown-item prescription-unit-choice" type="button" role="menuitem"
                value="{{ $unitChoice }}">{{ $unitChoice }}</button>
        @endforeach
    </div>
</div>
