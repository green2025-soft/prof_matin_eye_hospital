@can('create', \Modules\Hospital\Models\Patient::class)
    <div class="modal fade" id="patient-create-modal" tabindex="-1" aria-labelledby="patient-create-modal-heading" aria-hidden="true" data-fields-url="{{ route('hospital.patients.modal') }}">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <form class="modal-content" action="{{ route('hospital.patients.store') }}" method="POST" data-patient-create-form>
                @csrf
                <div class="modal-header">
                    <h2 class="modal-title h5" id="patient-create-modal-heading">New patient</h2>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" data-patient-modal-error role="alert" hidden></div>
                    <div data-patient-modal-content></div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit" data-patient-modal-save disabled><i class="fa-solid fa-check me-2" aria-hidden="true"></i>Save</button>
                </div>
            </form>
        </div>
    </div>
@endcan
