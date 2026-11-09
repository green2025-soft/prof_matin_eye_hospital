<div class="modal fade app-prescription-section-modal" id="prescription-rx-modal"
    tabindex="-1" aria-labelledby="prescription-rx-modal-heading" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title h5" id="prescription-rx-modal-heading"><i class="fa-solid fa-prescription" aria-hidden="true"></i></h2>
                <div class="app-prescription-rx-search position-relative mx-auto">
                    <label class="visually-hidden" for="prescription-medicine-search">Search / Add medicine</label>
                    <select id="prescription-medicine-search" multiple
                        data-url="{{ route('pharmacy.products.options', ['type' => 'medicine']) }}"></select>
                </div>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="app-prescription-rx-options d-flex flex-wrap align-content-start gap-2 overflow-auto" id="prescription-rx-options"
                    aria-label="Medicine suggestions">
                    <p class="small text-body-secondary mb-0">Search or select a medicine above.</p>
                </div>
                @error('medicines')
                    <div class="invalid-feedback d-block mb-3">{{ $message }}</div>
                @enderror
                <ol class="app-prescription-editor-medicines list-unstyled mb-0" id="prescription-medicines">
                    @foreach ($medicineRows as $medicineIndex => $medicine)
                        @include('prescription::prescriptions.medicine-row', [
                            'medicineIndex' => $medicineIndex,
                            'medicine' => $medicine,
                        ])
                    @endforeach
                </ol>
            </div>
            <div class="modal-footer justify-content-between py-2">
                <small class="text-body-secondary">F2 Search · F4 Dose/Unit · F8 Done</small>
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
