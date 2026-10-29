<section class="app-form-section" aria-labelledby="{{ $fieldPrefix.'details_heading' }}">
    <h2 class="app-form-section-title" id="{{ $fieldPrefix.'details_heading' }}">
        <span class="app-form-section-icon"><i class="fa-solid fa-hospital-user" aria-hidden="true"></i></span>Patient details
    </h2>

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label" for="{{ $fieldPrefix.'name' }}">Patient name <span class="text-danger" aria-hidden="true">*</span></label>
            <input class="{{ inputClass('name') }}" id="{{ $fieldPrefix.'name' }}" name="name" type="text" value="{{ getValue('name', $patient) }}" maxlength="255" autocomplete="name" required autofocus {{ inputAttrs('name', $fieldPrefix.'name') }}>
            @error('name')<div class="invalid-feedback d-block" id="{{ $fieldPrefix.'name-error' }}">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label" for="{{ $fieldPrefix.'father_husband_name' }}">Father/Husband name</label>
            <input class="{{ inputClass('father_husband_name') }}" id="{{ $fieldPrefix.'father_husband_name' }}" name="father_husband_name" type="text" value="{{ getValue('father_husband_name', $patient) }}" maxlength="255" {{ inputAttrs('father_husband_name', $fieldPrefix.'father_husband_name') }}>
            @error('father_husband_name')<div class="invalid-feedback d-block" id="{{ $fieldPrefix.'father_husband_name-error' }}">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label" for="{{ $fieldPrefix.'patient_id' }}">Patient ID</label>
            <input class="form-control" id="{{ $fieldPrefix.'patient_id' }}" type="text" value="{{ $patient->patient_id }}" placeholder="Auto generated" readonly>
        </div>
        <div class="col-md-6">
            <label class="form-label" for="{{ $fieldPrefix.'age' }}">Age (years) <span class="text-danger" aria-hidden="true">*</span></label>
            <input class="{{ inputClass('age') }}" id="{{ $fieldPrefix.'age' }}" name="age" type="number" inputmode="numeric" value="{{ getValue('age', $patient) }}" min="0" max="150" step="1" required {{ inputAttrs('age', $fieldPrefix.'age') }}>
            @error('age')<div class="invalid-feedback d-block" id="{{ $fieldPrefix.'age-error' }}">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label" for="{{ $fieldPrefix.'gender' }}">Gender <span class="text-danger" aria-hidden="true">*</span></label>
            <select class="{{ inputClass('gender', 'form-select') }}" id="{{ $fieldPrefix.'gender' }}" name="gender" required {{ inputAttrs('gender', $fieldPrefix.'gender') }}>
                <option value="">Select</option>
                @foreach ($genders as $value => $label)
                    <option value="{{ $value }}" {{ getSelected($value, 'gender', $patient) }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('gender')<div class="invalid-feedback d-block" id="{{ $fieldPrefix.'gender-error' }}">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label" for="{{ $fieldPrefix.'blood_group' }}">Blood group</label>
            <select class="{{ inputClass('blood_group', 'form-select') }}" id="{{ $fieldPrefix.'blood_group' }}" name="blood_group" {{ inputAttrs('blood_group', $fieldPrefix.'blood_group') }}>
                <option value="">Select</option>
                @foreach ($bloodGroups as $value => $label)
                    <option value="{{ $value }}" {{ getSelected($value, 'blood_group', $patient) }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('blood_group')<div class="invalid-feedback d-block" id="{{ $fieldPrefix.'blood_group-error' }}">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label" for="{{ $fieldPrefix.'mobile_no' }}">Mobile no. <span class="text-danger" aria-hidden="true">*</span></label>
            <input class="{{ inputClass('mobile_no') }}" id="{{ $fieldPrefix.'mobile_no' }}" name="mobile_no" type="tel" value="{{ getValue('mobile_no', $patient) }}" maxlength="30" autocomplete="tel" required {{ inputAttrs('mobile_no', $fieldPrefix.'mobile_no') }}>
            @error('mobile_no')<div class="invalid-feedback d-block" id="{{ $fieldPrefix.'mobile_no-error' }}">{{ $message }}</div>@enderror
        </div>
    </div>
</section>

<section class="app-form-section" aria-labelledby="{{ $fieldPrefix.'address_heading' }}">
    <h2 class="app-form-section-title" id="{{ $fieldPrefix.'address_heading' }}">Address</h2>
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label" for="{{ $fieldPrefix.'division_id' }}">Division</label>
            <select class="{{ inputClass('division_id', 'form-select') }}" id="{{ $fieldPrefix.'division_id' }}" name="division_id" data-ajax-select data-url="{{ route('tenantcore.divisions.options') }}" data-value-field="id" data-label-field="name" data-placeholder="Select a division" {{ inputAttrs('division_id', $fieldPrefix.'division_id') }}>
                <option value=""></option>
                @if ($selectedDivision !== null)
                    <option value="{{ $selectedDivision->id }}" selected>{{ $selectedDivision->name }}</option>
                @endif
            </select>
            @error('division_id')<div class="invalid-feedback d-block" id="{{ $fieldPrefix.'division_id-error' }}">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label" for="{{ $fieldPrefix.'district_id' }}">District</label>
            <input type="hidden" name="district_id" value="">
            <select class="{{ inputClass('district_id', 'form-select') }}" id="{{ $fieldPrefix.'district_id' }}" name="district_id" data-ajax-select data-url="{{ route('tenantcore.districts.options') }}" data-value-field="id" data-label-field="name" data-placeholder="Select a district" data-parent-select="{{ $fieldPrefix.'division_id' }}" data-parent-field="division_id" @disabled($selectedDivision === null) {{ inputAttrs('district_id', $fieldPrefix.'district_id') }}>
                <option value=""></option>
                @if ($selectedDistrict !== null)
                    <option value="{{ $selectedDistrict->id }}" selected>{{ $selectedDistrict->name }}</option>
                @endif
            </select>
            @error('district_id')<div class="invalid-feedback d-block" id="{{ $fieldPrefix.'district_id-error' }}">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label" for="{{ $fieldPrefix.'thana_id' }}">Thana</label>
            <input type="hidden" name="thana_id" value="">
            <select class="{{ inputClass('thana_id', 'form-select') }}" id="{{ $fieldPrefix.'thana_id' }}" name="thana_id" data-ajax-select data-url="{{ route('tenantcore.thanas.options') }}" data-value-field="id" data-label-field="name" data-placeholder="Select a thana" data-parent-select="{{ $fieldPrefix.'district_id' }}" data-parent-field="district_id" @disabled($selectedDistrict === null) {{ inputAttrs('thana_id', $fieldPrefix.'thana_id') }}>
                <option value=""></option>
                @if ($selectedThana !== null)
                    <option value="{{ $selectedThana->id }}" selected>{{ $selectedThana->name }}</option>
                @endif
            </select>
            @error('thana_id')<div class="invalid-feedback d-block" id="{{ $fieldPrefix.'thana_id-error' }}">{{ $message }}</div>@enderror
        </div>
        <div class="col-12">
            <label class="form-label" for="{{ $fieldPrefix.'address' }}">Address</label>
            <textarea class="{{ inputClass('address') }}" id="{{ $fieldPrefix.'address' }}" name="address" rows="3" maxlength="2000" autocomplete="street-address" {{ inputAttrs('address', $fieldPrefix.'address') }}>{{ getValue('address', $patient) }}</textarea>
            @error('address')<div class="invalid-feedback d-block" id="{{ $fieldPrefix.'address-error' }}">{{ $message }}</div>@enderror
        </div>
    </div>
</section>
