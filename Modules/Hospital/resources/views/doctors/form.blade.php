@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle, 'description' => 'Set the doctor profile, professional details, fees, and sign-in access.'])

    <form class="app-card p-3 p-md-4" action="{{ $formAction }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if ($formMethod !== 'POST')
            @method($formMethod)
        @endif

        @if ($doctor->exists && $doctor->doctor_id !== null)
            <p class="small text-body-secondary mb-3">Doctor ID: <strong class="text-body">{{ $doctor->doctor_id }}</strong></p>
        @endif

        <section aria-labelledby="doctor-details-heading">
            <h2 class="visually-hidden" id="doctor-details-heading">Doctor details</h2>
            <div class="row g-3">
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="name">Doctor name <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="{{ inputClass('name') }}" id="name" name="name" type="text" value="{{ getValue('name', $doctor) }}" maxlength="255" autocomplete="name" required autofocus {{ inputAttrs('name') }}>
                    @error('name')<div class="invalid-feedback d-block" id="name-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="bn_name" lang="bn">ডাক্তার নাম</label>
                    <input class="{{ inputClass('bn_name') }}" id="bn_name" name="bn_name" type="text" value="{{ getValue('bn_name', $doctor) }}" maxlength="255" lang="bn" {{ inputAttrs('bn_name') }}>
                    @error('bn_name')<div class="invalid-feedback d-block" id="bn_name-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="mobile_no">Mobile no. <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="{{ inputClass('mobile_no') }}" id="mobile_no" name="mobile_no" type="tel" value="{{ getValue('mobile_no', $doctor) }}" maxlength="30" autocomplete="tel" required {{ inputAttrs('mobile_no') }}>
                    @error('mobile_no')<div class="invalid-feedback d-block" id="mobile_no-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="department_id">Department <span class="text-danger" aria-hidden="true">*</span></label>
                    <select class="{{ inputClass('department_id', 'form-select') }}" id="department_id" name="department_id" data-ajax-select data-url="{{ route('hospital.departments.options') }}" data-value-field="id" data-label-field="name" data-placeholder="Select a department" required {{ inputAttrs('department_id') }}>
                        <option value=""></option>
                        @if ($selectedDepartment !== null)
                            <option value="{{ $selectedDepartment->id }}" selected>{{ $selectedDepartment->name }}</option>
                        @endif
                    </select>
                    @error('department_id')<div class="invalid-feedback d-block" id="department_id-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="designation">Designation</label>
                    <input class="{{ inputClass('designation') }}" id="designation" name="designation" type="text" value="{{ getValue('designation', $doctor) }}" maxlength="255" {{ inputAttrs('designation') }}>
                    @error('designation')<div class="invalid-feedback d-block" id="designation-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="specialist">Specialist</label>
                    <input class="{{ inputClass('specialist') }}" id="specialist" name="specialist" type="text" value="{{ getValue('specialist', $doctor) }}" maxlength="255" {{ inputAttrs('specialist') }}>
                    @error('specialist')<div class="invalid-feedback d-block" id="specialist-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="bn_specialist" lang="bn">স্পেশালিস্ট</label>
                    <input class="{{ inputClass('bn_specialist') }}" id="bn_specialist" name="bn_specialist" type="text" value="{{ getValue('bn_specialist', $doctor) }}" maxlength="255" lang="bn" {{ inputAttrs('bn_specialist') }}>
                    @error('bn_specialist')<div class="invalid-feedback d-block" id="bn_specialist-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <label class="form-label mb-0" for="picture">Picture</label>
                        @if ($doctor->picture_url)
                            <img class="app-table-avatar object-fit-cover" src="{{ $doctor->picture_url }}" alt="Current doctor picture" width="34" height="34">
                        @endif
                    </div>
                    <input class="{{ inputClass('picture') }}" id="picture" name="picture" type="file" accept=".png,.jpg,.jpeg,.webp,.gif,image/png,image/jpeg,image/webp,image/gif" {{ inputAttrs('picture', null, 'picture-help') }}>
                    <div class="form-text" id="picture-help">PNG, JPG, WebP, or GIF up to 2 MB.</div>
                    @error('picture')<div class="invalid-feedback d-block" id="picture-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="bmdc_reg">BMDC registration</label>
                    <input class="{{ inputClass('bmdc_reg') }}" id="bmdc_reg" name="bmdc_reg" type="text" value="{{ getValue('bmdc_reg', $doctor) }}" maxlength="100" {{ inputAttrs('bmdc_reg') }}>
                    @error('bmdc_reg')<div class="invalid-feedback d-block" id="bmdc_reg-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="bn_bmdc_reg" lang="bn">বিএমডিসি রেজি</label>
                    <input class="{{ inputClass('bn_bmdc_reg') }}" id="bn_bmdc_reg" name="bn_bmdc_reg" type="text" value="{{ getValue('bn_bmdc_reg', $doctor) }}" maxlength="100" lang="bn" {{ inputAttrs('bn_bmdc_reg') }}>
                    @error('bn_bmdc_reg')<div class="invalid-feedback d-block" id="bn_bmdc_reg-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="gender">Gender <span class="text-danger" aria-hidden="true">*</span></label>
                    <select class="{{ inputClass('gender', 'form-select') }}" id="gender" name="gender" required {{ inputAttrs('gender') }}>
                        <option value="">Select</option>
                        @foreach ($genders as $value => $label)
                            <option value="{{ $value }}" {{ getSelected($value, 'gender', $doctor) }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('gender')<div class="invalid-feedback d-block" id="gender-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="blood_group">Blood group</label>
                    <select class="{{ inputClass('blood_group', 'form-select') }}" id="blood_group" name="blood_group" {{ inputAttrs('blood_group') }}>
                        <option value="">Select</option>
                        @foreach ($bloodGroups as $value => $label)
                            <option value="{{ $value }}" {{ getSelected($value, 'blood_group', $doctor) }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('blood_group')<div class="invalid-feedback d-block" id="blood_group-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="education_degree">Education / degree</label>
                    <textarea class="{{ inputClass('education_degree') }}" id="education_degree" name="education_degree" rows="2" maxlength="2000" {{ inputAttrs('education_degree') }}>{{ getValue('education_degree', $doctor) }}</textarea>
                    @error('education_degree')<div class="invalid-feedback d-block" id="education_degree-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="bn_education_degree" lang="bn">শিক্ষা/ডিগ্রী</label>
                    <textarea class="{{ inputClass('bn_education_degree') }}" id="bn_education_degree" name="bn_education_degree" rows="2" maxlength="2000" lang="bn" {{ inputAttrs('bn_education_degree') }}>{{ getValue('bn_education_degree', $doctor) }}</textarea>
                    @error('bn_education_degree')<div class="invalid-feedback d-block" id="bn_education_degree-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="current_employment">Current employment</label>
                    <textarea class="{{ inputClass('current_employment') }}" id="current_employment" name="current_employment" rows="2" maxlength="2000" {{ inputAttrs('current_employment') }}>{{ getValue('current_employment', $doctor) }}</textarea>
                    @error('current_employment')<div class="invalid-feedback d-block" id="current_employment-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="bn_current_employment" lang="bn">বর্তমান চাকরি</label>
                    <textarea class="{{ inputClass('bn_current_employment') }}" id="bn_current_employment" name="bn_current_employment" rows="2" maxlength="2000" lang="bn" {{ inputAttrs('bn_current_employment') }}>{{ getValue('bn_current_employment', $doctor) }}</textarea>
                    @error('bn_current_employment')<div class="invalid-feedback d-block" id="bn_current_employment-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6">
                    <label class="form-label" for="address">Address</label>
                    <textarea class="{{ inputClass('address') }}" id="address" name="address" rows="2" maxlength="2000" autocomplete="street-address" {{ inputAttrs('address') }}>{{ getValue('address', $doctor) }}</textarea>
                    @error('address')<div class="invalid-feedback d-block" id="address-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6">
                    <label class="form-label" for="pad_footer_text">Pad footer text</label>
                    <textarea class="{{ inputClass('pad_footer_text') }}" id="pad_footer_text" name="pad_footer_text" rows="2" maxlength="4000" {{ inputAttrs('pad_footer_text') }}>{{ getValue('pad_footer_text', $doctor) }}</textarea>
                    @error('pad_footer_text')<div class="invalid-feedback d-block" id="pad_footer_text-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="consultation_fee">Consultation fee <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="{{ inputClass('consultation_fee') }}" id="consultation_fee" name="consultation_fee" type="text" inputmode="decimal" value="{{ getValue('consultation_fee', $doctor, '0.00') }}" maxlength="13" required {{ inputAttrs('consultation_fee') }}>
                    @error('consultation_fee')<div class="invalid-feedback d-block" id="consultation_fee-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="follow_up_fee">Follow-up fee <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="{{ inputClass('follow_up_fee') }}" id="follow_up_fee" name="follow_up_fee" type="text" inputmode="decimal" value="{{ getValue('follow_up_fee', $doctor, '0.00') }}" maxlength="13" required {{ inputAttrs('follow_up_fee') }}>
                    @error('follow_up_fee')<div class="invalid-feedback d-block" id="follow_up_fee-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="email">Email address <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="{{ inputClass('email') }}" id="email" name="email" type="email" value="{{ getValue('email', $doctor) }}" maxlength="255" autocomplete="email" required {{ inputAttrs('email') }}>
                    @error('email')<div class="invalid-feedback d-block" id="email-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-xl-3">
                    <label class="form-label" for="password">{{ $doctor->exists ? 'New password' : 'Password' }} @if (! $doctor->exists)<span class="text-danger" aria-hidden="true">*</span>@endif</label>
                    <div class="app-input-wrap">
                        <input class="{{ inputClass('password') }} app-password-input" id="password" name="password" type="password" minlength="{{ $passwordMinLength }}" maxlength="4096" autocomplete="new-password" @required(! $doctor->exists) {{ inputAttrs('password') }}>
                        <button class="app-password-toggle" type="button" data-password-toggle aria-controls="password" aria-label="Show password" aria-pressed="false" title="Show password"><i class="fa-solid fa-eye" aria-hidden="true"></i></button>
                    </div>
                    @if ($doctor->exists)
                        <div class="form-text">Leave blank to keep the current password.</div>
                    @endif
                    @error('password')<div class="invalid-feedback d-block" id="password-error">{{ $message }}</div>@enderror
                </div>
            </div>
        </section>

        @include('core::partials.form-actions', ['model' => $doctor, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel])
    </form>
@endsection
