@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle])

    <form class="app-card p-3 p-md-4" id="glass-prescription-form" action="{{ $formAction }}" method="POST">
        @csrf
        @if ($formMethod !== 'POST')
            @method($formMethod)
        @endif

        @include('prescription::partials.identity')

        <section class="app-form-section mb-3" aria-labelledby="glass-powers-heading">
            <h2 class="h6 mb-3" id="glass-powers-heading">Eye prescription</h2>
            <div class="row g-3">
                <div class="col-lg-6">
                    <div class="app-glass-eye-card">
                        <h3 class="h6 app-glass-eye-heading">Right eye (OD)</h3>
                        <div class="table-responsive">
                            <table class="table table-sm app-glass-eye-table align-middle mb-0">
                                <caption class="visually-hidden">Right eye (OD) distance and near vision powers</caption>
                                <thead><tr><th scope="col">Vision</th><th scope="col">SPH</th><th scope="col">CYL</th><th scope="col">Axis</th><th scope="col">V/A</th></tr></thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">DV</th>
                                        <td>
                                            <label class="visually-hidden" for="glass_r_sphere_dv">Right eye (OD) DV SPH</label>
                                            <input class="{{ inputClass('r_sphere_dv', 'form-control form-control-sm text-center') }}" id="glass_r_sphere_dv" name="r_sphere_dv" type="text" maxlength="30" value="{{ getValue('r_sphere_dv', $record) }}" {{ inputAttrs('r_sphere_dv', 'glass_r_sphere_dv') }}>
                                            @error('r_sphere_dv')<div class="invalid-feedback d-block" id="glass_r_sphere_dv-error">{{ $message }}</div>@enderror
                                        </td>
                                        <td>
                                            <label class="visually-hidden" for="glass_r_cylinder_dv">Right eye (OD) DV CYL</label>
                                            <input class="{{ inputClass('r_cylinder_dv', 'form-control form-control-sm text-center') }}" id="glass_r_cylinder_dv" name="r_cylinder_dv" type="text" maxlength="30" value="{{ getValue('r_cylinder_dv', $record) }}" {{ inputAttrs('r_cylinder_dv', 'glass_r_cylinder_dv') }}>
                                            @error('r_cylinder_dv')<div class="invalid-feedback d-block" id="glass_r_cylinder_dv-error">{{ $message }}</div>@enderror
                                        </td>
                                        <td>
                                            <label class="visually-hidden" for="glass_r_axis_dv">Right eye (OD) DV Axis</label>
                                            <input class="{{ inputClass('r_axis_dv', 'form-control form-control-sm text-center') }}" id="glass_r_axis_dv" name="r_axis_dv" type="text" maxlength="30" value="{{ getValue('r_axis_dv', $record) }}" {{ inputAttrs('r_axis_dv', 'glass_r_axis_dv') }}>
                                            @error('r_axis_dv')<div class="invalid-feedback d-block" id="glass_r_axis_dv-error">{{ $message }}</div>@enderror
                                        </td>
                                        <td>
                                            <label class="visually-hidden" for="glass_r_acuity_dv">Right eye (OD) DV V/A</label>
                                            <input class="{{ inputClass('r_acuity_dv', 'form-control form-control-sm text-center') }}" id="glass_r_acuity_dv" name="r_acuity_dv" type="text" maxlength="30" value="{{ getValue('r_acuity_dv', $record) }}" {{ inputAttrs('r_acuity_dv', 'glass_r_acuity_dv') }}>
                                            @error('r_acuity_dv')<div class="invalid-feedback d-block" id="glass_r_acuity_dv-error">{{ $message }}</div>@enderror
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">NV</th>
                                        <td>
                                            <label class="visually-hidden" for="glass_r_sphere_nv">Right eye (OD) NV SPH</label>
                                            <input class="{{ inputClass('r_sphere_nv', 'form-control form-control-sm text-center') }}" id="glass_r_sphere_nv" name="r_sphere_nv" type="text" maxlength="30" value="{{ getValue('r_sphere_nv', $record) }}" {{ inputAttrs('r_sphere_nv', 'glass_r_sphere_nv') }}>
                                            @error('r_sphere_nv')<div class="invalid-feedback d-block" id="glass_r_sphere_nv-error">{{ $message }}</div>@enderror
                                        </td>
                                        <td>
                                            <label class="visually-hidden" for="glass_r_cylinder_nv">Right eye (OD) NV CYL</label>
                                            <input class="{{ inputClass('r_cylinder_nv', 'form-control form-control-sm text-center') }}" id="glass_r_cylinder_nv" name="r_cylinder_nv" type="text" maxlength="30" value="{{ getValue('r_cylinder_nv', $record) }}" {{ inputAttrs('r_cylinder_nv', 'glass_r_cylinder_nv') }}>
                                            @error('r_cylinder_nv')<div class="invalid-feedback d-block" id="glass_r_cylinder_nv-error">{{ $message }}</div>@enderror
                                        </td>
                                        <td>
                                            <label class="visually-hidden" for="glass_r_axis_nv">Right eye (OD) NV Axis</label>
                                            <input class="{{ inputClass('r_axis_nv', 'form-control form-control-sm text-center') }}" id="glass_r_axis_nv" name="r_axis_nv" type="text" maxlength="30" value="{{ getValue('r_axis_nv', $record) }}" {{ inputAttrs('r_axis_nv', 'glass_r_axis_nv') }}>
                                            @error('r_axis_nv')<div class="invalid-feedback d-block" id="glass_r_axis_nv-error">{{ $message }}</div>@enderror
                                        </td>
                                        <td>
                                            <label class="visually-hidden" for="glass_r_acuity_nv">Right eye (OD) NV V/A</label>
                                            <input class="{{ inputClass('r_acuity_nv', 'form-control form-control-sm text-center') }}" id="glass_r_acuity_nv" name="r_acuity_nv" type="text" maxlength="30" value="{{ getValue('r_acuity_nv', $record) }}" {{ inputAttrs('r_acuity_nv', 'glass_r_acuity_nv') }}>
                                            @error('r_acuity_nv')<div class="invalid-feedback d-block" id="glass_r_acuity_nv-error">{{ $message }}</div>@enderror
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="app-glass-eye-card">
                        <h3 class="h6 app-glass-eye-heading">Left eye (OS)</h3>
                        <div class="table-responsive">
                            <table class="table table-sm app-glass-eye-table align-middle mb-0">
                                <caption class="visually-hidden">Left eye (OS) distance and near vision powers</caption>
                                <thead><tr><th scope="col">Vision</th><th scope="col">SPH</th><th scope="col">CYL</th><th scope="col">Axis</th><th scope="col">V/A</th></tr></thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">DV</th>
                                        <td>
                                            <label class="visually-hidden" for="glass_l_sphere_dv">Left eye (OS) DV SPH</label>
                                            <input class="{{ inputClass('l_sphere_dv', 'form-control form-control-sm text-center') }}" id="glass_l_sphere_dv" name="l_sphere_dv" type="text" maxlength="30" value="{{ getValue('l_sphere_dv', $record) }}" {{ inputAttrs('l_sphere_dv', 'glass_l_sphere_dv') }}>
                                            @error('l_sphere_dv')<div class="invalid-feedback d-block" id="glass_l_sphere_dv-error">{{ $message }}</div>@enderror
                                        </td>
                                        <td>
                                            <label class="visually-hidden" for="glass_l_cylinder_dv">Left eye (OS) DV CYL</label>
                                            <input class="{{ inputClass('l_cylinder_dv', 'form-control form-control-sm text-center') }}" id="glass_l_cylinder_dv" name="l_cylinder_dv" type="text" maxlength="30" value="{{ getValue('l_cylinder_dv', $record) }}" {{ inputAttrs('l_cylinder_dv', 'glass_l_cylinder_dv') }}>
                                            @error('l_cylinder_dv')<div class="invalid-feedback d-block" id="glass_l_cylinder_dv-error">{{ $message }}</div>@enderror
                                        </td>
                                        <td>
                                            <label class="visually-hidden" for="glass_l_axis_dv">Left eye (OS) DV Axis</label>
                                            <input class="{{ inputClass('l_axis_dv', 'form-control form-control-sm text-center') }}" id="glass_l_axis_dv" name="l_axis_dv" type="text" maxlength="30" value="{{ getValue('l_axis_dv', $record) }}" {{ inputAttrs('l_axis_dv', 'glass_l_axis_dv') }}>
                                            @error('l_axis_dv')<div class="invalid-feedback d-block" id="glass_l_axis_dv-error">{{ $message }}</div>@enderror
                                        </td>
                                        <td>
                                            <label class="visually-hidden" for="glass_l_acuity_dv">Left eye (OS) DV V/A</label>
                                            <input class="{{ inputClass('l_acuity_dv', 'form-control form-control-sm text-center') }}" id="glass_l_acuity_dv" name="l_acuity_dv" type="text" maxlength="30" value="{{ getValue('l_acuity_dv', $record) }}" {{ inputAttrs('l_acuity_dv', 'glass_l_acuity_dv') }}>
                                            @error('l_acuity_dv')<div class="invalid-feedback d-block" id="glass_l_acuity_dv-error">{{ $message }}</div>@enderror
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">NV</th>
                                        <td>
                                            <label class="visually-hidden" for="glass_l_sphere_nv">Left eye (OS) NV SPH</label>
                                            <input class="{{ inputClass('l_sphere_nv', 'form-control form-control-sm text-center') }}" id="glass_l_sphere_nv" name="l_sphere_nv" type="text" maxlength="30" value="{{ getValue('l_sphere_nv', $record) }}" {{ inputAttrs('l_sphere_nv', 'glass_l_sphere_nv') }}>
                                            @error('l_sphere_nv')<div class="invalid-feedback d-block" id="glass_l_sphere_nv-error">{{ $message }}</div>@enderror
                                        </td>
                                        <td>
                                            <label class="visually-hidden" for="glass_l_cylinder_nv">Left eye (OS) NV CYL</label>
                                            <input class="{{ inputClass('l_cylinder_nv', 'form-control form-control-sm text-center') }}" id="glass_l_cylinder_nv" name="l_cylinder_nv" type="text" maxlength="30" value="{{ getValue('l_cylinder_nv', $record) }}" {{ inputAttrs('l_cylinder_nv', 'glass_l_cylinder_nv') }}>
                                            @error('l_cylinder_nv')<div class="invalid-feedback d-block" id="glass_l_cylinder_nv-error">{{ $message }}</div>@enderror
                                        </td>
                                        <td>
                                            <label class="visually-hidden" for="glass_l_axis_nv">Left eye (OS) NV Axis</label>
                                            <input class="{{ inputClass('l_axis_nv', 'form-control form-control-sm text-center') }}" id="glass_l_axis_nv" name="l_axis_nv" type="text" maxlength="30" value="{{ getValue('l_axis_nv', $record) }}" {{ inputAttrs('l_axis_nv', 'glass_l_axis_nv') }}>
                                            @error('l_axis_nv')<div class="invalid-feedback d-block" id="glass_l_axis_nv-error">{{ $message }}</div>@enderror
                                        </td>
                                        <td>
                                            <label class="visually-hidden" for="glass_l_acuity_nv">Left eye (OS) NV V/A</label>
                                            <input class="{{ inputClass('l_acuity_nv', 'form-control form-control-sm text-center') }}" id="glass_l_acuity_nv" name="l_acuity_nv" type="text" maxlength="30" value="{{ getValue('l_acuity_nv', $record) }}" {{ inputAttrs('l_acuity_nv', 'glass_l_acuity_nv') }}>
                                            @error('l_acuity_nv')<div class="invalid-feedback d-block" id="glass_l_acuity_nv-error">{{ $message }}</div>@enderror
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="row g-3">
            @if ($settings['glass_pd_enabled'])
                <div class="col-sm-4 col-lg-3">
                    <label class="form-label" for="glass_pd">I.P.D (mm)</label>
                    <input class="{{ inputClass('pd') }}" id="glass_pd" name="pd" type="text" maxlength="30" value="{{ getValue('pd', $record) }}" {{ inputAttrs('pd', 'glass_pd') }}>
                    @error('pd')<div class="invalid-feedback d-block" id="glass_pd-error">{{ $message }}</div>@enderror
                </div>
            @endif
            @if ($settings['glass_lens_options_enabled'])
                <fieldset class="col-sm-8 col-lg-9">
                    <legend class="form-label">Glasses and use</legend>
                    <div class="d-flex flex-wrap gap-3 pt-1">
                        @foreach ($lensOptions as $value => $label)
                            <div class="form-check">
                                <input class="{{ inputClass('lens_options', 'form-check-input') }}" id="glass_lens_{{ $value }}" name="lens_options[]" type="checkbox" value="{{ $value }}" {{ getChecked($value, 'lens_options', $record, []) }} {{ inputAttrs('lens_options', 'glass_lens_'.$value) }}>
                                <label class="form-check-label" for="glass_lens_{{ $value }}">{{ $label }}</label>
                            </div>
                        @endforeach
                    </div>
                    @error('lens_options')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    @error('lens_options.*')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </fieldset>
            @endif
            @if ($settings['glass_disclaimer_enabled'])
                <div class="col-12">
                    <label class="form-label" for="glass_disclaimer">Remarks</label>
                    <textarea class="{{ inputClass('disclaimer') }}" id="glass_disclaimer" name="disclaimer" rows="2" maxlength="2000" {{ inputAttrs('disclaimer', 'glass_disclaimer') }}>{{ getValue('disclaimer', $record) }}</textarea>
                    @error('disclaimer')<div class="invalid-feedback d-block" id="glass_disclaimer-error">{{ $message }}</div>@enderror
                </div>
            @endif
        </div>

        <div class="d-flex flex-wrap gap-2 mt-3">
            <button class="btn btn-primary" type="submit"><i class="fa-solid fa-floppy-disk me-2" aria-hidden="true"></i>{{ $submitLabel }} glass prescription</button>
            <a class="btn btn-warning" href="{{ route($routePrefix.'.index') }}">Cancel</a>
        </div>
    </form>
@endsection
