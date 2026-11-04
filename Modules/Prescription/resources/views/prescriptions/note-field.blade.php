@php
    $noteValue = getValue($noteField, $record);
@endphp

<section class="app-prescription-note">
    <div class="app-prescription-section-heading">
        <h3>{{ $noteLabel }}</h3>
        <button class="btn btn-outline-primary btn-sm" type="button"
            data-bs-toggle="modal" data-bs-target="#prescription-{{ $noteField }}-modal"
            aria-label="Add or edit {{ strtolower($noteLabel) }}">
            <i class="fa-solid fa-plus" aria-hidden="true"></i>
        </button>
    </div>
    <ul class="app-prescription-section-preview" id="{{ $noteField }}-preview"></ul>
</section>

@push('prescription-modals')
    <div class="modal fade app-prescription-section-modal prescription-note-modal"
        id="prescription-{{ $noteField }}-modal" tabindex="-1"
        aria-labelledby="{{ $noteField }}-modal-heading" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title h5" id="{{ $noteField }}-modal-heading">{{ $noteLabel }}</h2>
                    @include('prescription::prescriptions.preset-search', ['pickerField' => $noteField])
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @include('prescription::prescriptions.preset-picker', [
                        'pickerField' => $noteField,
                        'pickerValue' => $noteValue,
                    ])
                    @include('prescription::prescriptions.clinical-items', ['pickerField' => $noteField])
                </div>
            </div>
        </div>
    </div>
@endpush
