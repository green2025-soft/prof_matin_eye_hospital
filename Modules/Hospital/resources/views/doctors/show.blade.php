@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle, 'description' => 'Profile, professional details, and consultation fees.'])

    <div class="d-flex flex-wrap gap-2 mb-4">
        <a class="btn btn-outline-secondary btn-sm" href="{{ route($routePrefix.'.index') }}"><i class="fa-solid fa-arrow-left me-2" aria-hidden="true"></i>Doctors</a>
        @can('update', $doctor)
            <a class="btn btn-primary btn-sm" href="{{ route($routePrefix.'.edit', $doctor) }}"><i class="fa-solid fa-pen-to-square me-2" aria-hidden="true"></i>Edit</a>
        @endcan
    </div>

    <div class="row g-3 align-items-start">
        <div class="col-lg-4">
            <section class="app-card p-3 p-md-4" aria-labelledby="doctor-profile-heading">
                @if ($doctor->picture_url)
                    <img class="rounded object-fit-cover mb-3" src="{{ $doctor->picture_url }}" alt="Picture of {{ $doctor->name }}" width="120" height="120">
                @else
                    <span class="app-mark app-mark-large mb-3" aria-hidden="true"><i class="fa-solid fa-user-doctor"></i></span>
                @endif
                <h2 class="h5 text-break mb-1" id="doctor-profile-heading">{{ $doctor->name }}</h2>
                <p class="small text-break mb-3" lang="bn"><span class="text-body-secondary">ডাক্তার নাম:</span> {{ $doctor->bn_name ?? '-' }}</p>

                <dl class="row gy-2 small mb-0">
                    <dt class="col-sm-4">Doctor ID</dt>
                    <dd class="col-sm-8 text-break mb-0">{{ $doctor->doctor_id ?? '-' }}</dd>
                    <dt class="col-sm-4">Designation</dt>
                    <dd class="col-sm-8 text-break mb-0">{{ $doctor->designation ?? '-' }}</dd>
                    <dt class="col-sm-4">Mobile no.</dt>
                    <dd class="col-sm-8 text-break mb-0">{{ $doctor->mobile_no }}</dd>
                    <dt class="col-sm-4">Email</dt>
                    <dd class="col-sm-8 text-break mb-0">{{ $doctor->email }}</dd>
                    <dt class="col-sm-4">Gender</dt>
                    <dd class="col-sm-8 mb-0">{{ $genderLabel }}</dd>
                    <dt class="col-sm-4">Blood group</dt>
                    <dd class="col-sm-8 mb-0">{{ $bloodGroupLabel }}</dd>
                </dl>
                <div class="border-top mt-3 pt-3">
                    <h3 class="h6">Address</h3>
                    <p class="small text-break mb-0">{{ $doctor->address ?? '-' }}</p>
                </div>
            </section>
        </div>

        <div class="col-lg-8">
            <section class="app-card p-3 p-md-4 mb-3" aria-labelledby="doctor-professional-heading">
                <h2 class="h6 mb-3" id="doctor-professional-heading">Professional details</h2>
                <dl class="row gy-2 small mb-0">
                    <dt class="col-sm-4">Department</dt>
                    <dd class="col-sm-8 text-break mb-0">{{ $doctor->department?->name ?? '-' }}</dd>
                    <dt class="col-sm-4">Specialist</dt>
                    <dd class="col-sm-8 text-break mb-0">{{ $doctor->specialist ?? '-' }}</dd>
                    <dt class="col-sm-4" lang="bn">স্পেশালিস্ট</dt>
                    <dd class="col-sm-8 text-break mb-0" lang="bn">{{ $doctor->bn_specialist ?? '-' }}</dd>
                    <dt class="col-sm-4">BMDC registration</dt>
                    <dd class="col-sm-8 text-break mb-0">{{ $doctor->bmdc_reg ?? '-' }}</dd>
                    <dt class="col-sm-4" lang="bn">বিএমডিসি রেজি</dt>
                    <dd class="col-sm-8 text-break mb-0" lang="bn">{{ $doctor->bn_bmdc_reg ?? '-' }}</dd>
                </dl>

                <div class="row g-3 border-top mt-3 pt-3">
                    <div class="col-md-6">
                        <h3 class="small fw-semibold text-body-secondary">Education / degree</h3>
                        <p class="small text-break mb-0">{{ $doctor->education_degree ?? '-' }}</p>
                    </div>
                    <div class="col-md-6" lang="bn">
                        <h3 class="small fw-semibold text-body-secondary">শিক্ষা/ডিগ্রী</h3>
                        <p class="small text-break mb-0">{{ $doctor->bn_education_degree ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h3 class="small fw-semibold text-body-secondary">Current employment</h3>
                        <p class="small text-break mb-0">{{ $doctor->current_employment ?? '-' }}</p>
                    </div>
                    <div class="col-md-6" lang="bn">
                        <h3 class="small fw-semibold text-body-secondary">বর্তমান চাকরি</h3>
                        <p class="small text-break mb-0">{{ $doctor->bn_current_employment ?? '-' }}</p>
                    </div>
                </div>
            </section>

            <section class="app-card p-3 p-md-4" aria-labelledby="doctor-fees-heading">
                <h2 class="h6 mb-3" id="doctor-fees-heading">Fees</h2>
                <dl class="row g-3 mb-0">
                    <div class="col-sm-6">
                        <dt class="small text-body-secondary mb-1">Consultation fee</dt>
                        <dd class="fw-semibold mb-0">{{ $doctor->consultation_fee }}</dd>
                    </div>
                    <div class="col-sm-6">
                        <dt class="small text-body-secondary mb-1">Follow-up fee</dt>
                        <dd class="fw-semibold mb-0">{{ $doctor->follow_up_fee }}</dd>
                    </div>
                </dl>
                <div class="border-top mt-3 pt-3">
                    <h3 class="h6">Pad footer text</h3>
                    <p class="small text-break mb-0">{{ $doctor->pad_footer_text ?? '-' }}</p>
                </div>
            </section>
        </div>
    </div>
@endsection
