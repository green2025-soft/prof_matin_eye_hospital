<section class="app-prescription-note mb-3">
    <div class="app-prescription-section-heading d-flex align-items-center gap-2">
        <h3 class="mb-0">Investigations</h3>
        <button class="btn btn-outline-primary btn-sm" type="button"
            data-bs-toggle="modal" data-bs-target="#prescription-investigations-modal"
            aria-label="Add or edit investigations">
            <i class="fa-solid fa-plus" aria-hidden="true"></i>
        </button>
    </div>
    <ul class="app-prescription-section-preview" id="investigations-preview"></ul>
</section>

@push('prescription-modals')
    <div class="modal fade app-prescription-section-modal prescription-note-modal"
        id="prescription-investigations-modal" tabindex="-1"
        aria-labelledby="investigations-modal-heading" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title h5" id="investigations-modal-heading">Investigations</h2>
                    @include('prescription::prescriptions.picker-search', ['pickerField' => 'investigations'])
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @include('prescription::prescriptions.picker', [
                        'pickerField' => 'investigations',
                        'pickerValue' => getValue('investigations', $record),
                        'pickerUrl' => route('hospital.investigations.options'),
                    ])
                    @include('prescription::prescriptions.clinical-items', ['pickerField' => 'investigations'])
                </div>
            </div>
        </div>
    </div>
@endpush
