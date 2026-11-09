@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @php
        $hasClinicalFields = $settings['general_chief_complaints_enabled']
            || $settings['general_history_enabled']
            || $settings['general_examination_enabled']
            || $settings['general_diagnosis_enabled']
            || $settings['general_investigations_enabled'];
        $medicineRows = $hasOldMedicines ? $medicineInputs : $record->medicines;
    @endphp

    <div class="app-prescription-workspace d-flex flex-column">
        <div class="app-page-heading flex-shrink-0 mb-0">
            <h1 class="h4 mb-0">{{ $pageTitle }}</h1>
            <div class="d-flex flex-wrap gap-2">

                <a class="btn btn-outline-success " href="{{ route($routePrefix.'.index') }}">
                    <i class="fa-solid fa-clock-rotate-left me-2" aria-hidden="true"></i>Prescription List
                </a>
            </div>
        </div>

        <form class="d-flex flex-column" id="prescription-form" action="{{ $formAction }}" method="POST">
            @csrf
            @if ($formMethod !== 'POST')
                @method($formMethod)
            @endif

            <div class="app-card app-prescription-editor d-flex flex-column overflow-hidden">
                <header class="app-prescription-editor-header d-flex flex-shrink-0 align-items-center
                    justify-content-between gap-3">
                    <div>
                        <h2>{{ request()->attributes->get('application_name') }}</h2>
                        <p>{{ request()->attributes->get('branch_name') }}</p>
                    </div>
                    <div class="">
                        @if ($canCreatePatient)
                        <button class="btn btn-outline-primary " type="button"
                            data-bs-toggle="modal" data-bs-target="#patient-create-modal"
                            data-patient-target="prescription_patient_id">
                            <i class="fa-solid fa-user-plus me-2" aria-hidden="true"></i>New patient
                        </button>
                    @endif
                    </div>
                    <div class="app-prescription-editor-title d-flex flex-shrink-0 align-items-center">
                        <i class="fa-solid fa-prescription d-none d-sm-inline" aria-hidden="true"></i>
                    </div>
                </header>

                <div class="app-prescription-editor-identity flex-shrink-0">
                    @include('prescription::partials.identity')
                </div>

                <div class="nav nav-tabs flex-shrink-0 px-3 d-lg-none" role="tablist"
                    aria-label="Prescription sections">
                    <button class="nav-link active" id="prescription-treatment-tab" type="button" role="tab"
                        data-bs-toggle="tab" data-bs-target="#prescription-treatment-pane"
                        aria-controls="prescription-treatment-pane" aria-selected="true">Rx &amp; advice</button>
                    @if ($hasClinicalFields)
                        <button class="nav-link" id="prescription-notes-tab" type="button" role="tab"
                            data-bs-toggle="tab" data-bs-target="#prescription-notes-pane"
                            aria-controls="prescription-notes-pane" aria-selected="false">Clinical notes</button>
                    @endif
                </div>

                <div class="app-prescription-editor-body tab-content row g-0 flex-nowrap flex-grow-1">
                    @if ($hasClinicalFields)
                        <aside class="app-prescription-editor-notes tab-pane col-12 col-lg d-lg-block overflow-auto"
                            id="prescription-notes-pane"
                            role="tabpanel" aria-labelledby="prescription-notes-tab" tabindex="0">
                            @if ($settings['general_chief_complaints_enabled'])
                                @include('prescription::prescriptions.note-field', [
                                    'noteField' => 'chief_complaints',
                                    'noteLabel' => 'C/C',
                                ])
                            @endif
                            @if ($settings['general_history_enabled'])
                                @include('prescription::prescriptions.history-field')
                            @endif
                            @if ($settings['general_examination_enabled'])
                                @include('prescription::prescriptions.examination-field')
                            @endif
                             @if ($settings['general_investigations_enabled'])
                                @include('prescription::prescriptions.investigations-field')
                            @endif
                            @if ($settings['general_diagnosis_enabled'])
                                @include('prescription::prescriptions.note-field', [
                                    'noteField' => 'diagnosis',
                                    'noteLabel' => 'Diagnosis',
                                ])
                            @endif

                        </aside>
                    @endif

                    <section class="app-prescription-editor-treatment tab-pane show active col-12 col-lg d-lg-flex
                        flex-column position-relative overflow-auto"
                        id="prescription-treatment-pane" role="tabpanel"
                        aria-labelledby="prescription-treatment-tab" tabindex="0">
                        <img class="app-prescription-watermark" src="{{ asset('prescription-bg.png') }}"
                            width="500" height="500" alt="">
                        <div class="app-prescription-section-heading d-flex align-items-center gap-2">
                            <h2 class="app-prescription-rx mb-0"><i class="fa-solid fa-prescription" aria-hidden="true"></i></h2>
                            <button class="btn btn-outline-primary btn-sm" type="button"
                                data-bs-toggle="modal" data-bs-target="#prescription-rx-modal"
                                aria-keyshortcuts="F2" title="Medicines (F2)"
                                aria-label="Add or edit medicines">
                                <i class="fa-solid fa-plus" aria-hidden="true"></i>
                            </button>
                            <span class="small text-body-secondary ms-auto" id="prescription-medicine-count"
                                aria-live="polite" aria-atomic="true"></span>
                        </div>

                        <ol class="app-prescription-rx-preview list-unstyled mb-0" id="prescription-medicine-preview"></ol>
                        <p class="small text-body-secondary" id="prescription-medicine-empty">No medicines added.</p>

                        <div class="app-prescription-editor-advice">
                            @if ($settings['general_advice_enabled'])
                                @include('prescription::prescriptions.note-field', [
                                    'noteField' => 'advice',
                                    'noteLabel' => 'Advice',
                                ])
                            @endif
                            @if ($settings['general_follow_up_enabled'])
                                @include('prescription::prescriptions.follow-up-field')
                            @endif
                        </div>
                        <span class="app-prescription-pad-signature align-self-end pt-3 mt-auto">Signature</span>
                    </section>
                </div>
            </div>

            <div class="app-prescription-editor-actions d-flex flex-shrink-0 flex-wrap justify-content-end gap-2">
                <a class="btn btn-warning" href="{{ route($routePrefix.'.index') }}">Cancel</a>
                <button class="btn btn-primary px-4" type="submit">
                    <i class="fa-solid fa-floppy-disk me-2" aria-hidden="true"></i>{{ $submitLabel }} prescription
                </button>
            </div>
        </form>
    </div>

    @stack('prescription-modals')
    @include('prescription::prescriptions.rx-modal')

    @if ($canCreatePatient)
        @include('hospital::patients.modal')
    @endif

    <template id="prescription-medicine-template">
        @include('prescription::prescriptions.medicine-row', ['medicineIndex' => '__INDEX__', 'medicine' => []])
    </template>
@endsection
