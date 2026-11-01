@foreach (['status' => 'success', 'success' => 'success', 'error' => 'error', 'warning' => 'warning', 'info' => 'info'] as $key => $type)
    @if (session()->has($key))
        <span hidden data-toast-message="{{ session($key) }}" data-toast-type="{{ $type }}"></span>
    @endif
@endforeach
@php
    $validationErrors = isset($errors) ? clone $errors->getBag('default') : null;
    $recordErrors = $validationErrors?->get('record') ?? [];
    $validationErrors?->forget('record');
@endphp
@foreach ($recordErrors as $error)
    <span hidden data-toast-message="{{ $error }}" data-toast-type="error"></span>
@endforeach
@if ($validationErrors?->any())
    <div class="alert alert-danger" id="validation-errors" role="alert">
        <p class="fw-semibold mb-2"><i class="fa-solid fa-circle-exclamation me-2" aria-hidden="true"></i>Please check the following:</p>
        <ul class="mb-0 ps-3">
            @foreach ($validationErrors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
