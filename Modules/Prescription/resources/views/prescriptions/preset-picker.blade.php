@include('prescription::prescriptions.picker', [
    'pickerUrl' => route('prescription.options.selector', ['section' => $pickerField]),
])

