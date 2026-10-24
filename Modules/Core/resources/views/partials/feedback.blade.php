@if (session('status'))
    <div class="alert alert-success d-flex align-items-start gap-2" role="status"><i class="fa-solid fa-circle-check mt-1" aria-hidden="true"></i><span>{{ session('status') }}</span></div>
@endif
@if (session('error'))
    <div class="alert alert-danger d-flex align-items-start gap-2" role="alert"><i class="fa-solid fa-circle-exclamation mt-1" aria-hidden="true"></i><span>{{ session('error') }}</span></div>
@endif
@if (isset($errors) && $errors->any())
    <div class="alert alert-danger" id="validation-errors" role="alert">
        <p class="fw-semibold mb-2"><i class="fa-solid fa-circle-exclamation me-2" aria-hidden="true"></i>Please check the following:</p>
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
