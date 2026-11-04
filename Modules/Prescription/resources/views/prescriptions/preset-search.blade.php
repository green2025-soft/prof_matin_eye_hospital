<div class="app-prescription-preset-search">
    @include('prescription::prescriptions.picker-search')
    @if ($canCreateOptions)
        <button class="btn btn-outline-primary btn-sm prescription-preset-save" type="button" hidden
            data-picker="#{{ $pickerField }}-picker"
            data-url="{{ route('prescription.options.store') }}" data-section="{{ $pickerField }}">
            <i class="fa-solid fa-circle-plus" aria-hidden="true"></i> Create
        </button>
    @endif
</div>
