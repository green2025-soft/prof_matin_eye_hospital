@extends('prescription::prints.layout')

@section('prescription-content')
    <section class="app-glass-print-body" aria-labelledby="glass-print-heading">
        {{-- <h2 class="h6 mb-3" id="glass-print-heading">Eye prescription</h2> --}}
        <div class="col-10 mx-auto mb-3">
            <img class="img-fluid w-100" src="{{ asset('glass-prescription-axis.svg') }}" width="720" height="210"
                alt="Right eye (R.E.) and left eye (L.E.) axis diagrams">
        </div>
        <div class="row g-2 flex-nowrap">
            <div class="col-6">
                <div class="table-responsive">
                    <table class="table table-bordered app-glass-print-table align-middle">
                        <caption class="caption-top text-center fw-semibold text-body py-1">Right (R.E.)</caption>
                        <thead>
                            <tr>
                                <th scope="col">Vision</th>
                                <th scope="col">SPH</th>
                                <th scope="col">CYL</th>
                                <th scope="col">Axis</th>
                                <th scope="col">V/A</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">DV</th>
                                <td>{{ $record->r_sphere_dv ?? '--' }}</td>
                                <td>{{ $record->r_cylinder_dv ?? '--' }}</td>
                                <td>{{ $record->r_axis_dv ?? '--' }}</td>
                                <td>{{ $record->r_acuity_dv ?? '--' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">NV</th>
                                <td>{{ $record->r_sphere_nv ?? '--' }}</td>
                                <td>{{ $record->r_cylinder_nv ?? '--' }}</td>
                                <td>{{ $record->r_axis_nv ?? '--' }}</td>
                                <td>{{ $record->r_acuity_nv ?? '--' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-6">
                <div class="table-responsive">
                    <table class="table table-bordered app-glass-print-table align-middle">
                        <caption class="caption-top text-center fw-semibold text-body py-1">Left (L.E.)</caption>
                        <thead>
                            <tr>
                                <th scope="col">Vision</th>
                                <th scope="col">SPH</th>
                                <th scope="col">CYL</th>
                                <th scope="col">Axis</th>
                                <th scope="col">V/A</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">DV</th>
                                <td>{{ $record->l_sphere_dv ?? '--' }}</td>
                                <td>{{ $record->l_cylinder_dv ?? '--' }}</td>
                                <td>{{ $record->l_axis_dv ?? '--' }}</td>
                                <td>{{ $record->l_acuity_dv ?? '--' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">NV</th>
                                <td>{{ $record->l_sphere_nv ?? '--' }}</td>
                                <td>{{ $record->l_cylinder_nv ?? '--' }}</td>
                                <td>{{ $record->l_axis_nv ?? '--' }}</td>
                                <td>{{ $record->l_acuity_nv ?? '--' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @if ($settings['glass_pd_enabled'] && filled($record->pd))
            <p class="app-glass-print-pd"><strong>I.P.D:</strong> {{ $record->pd }} mm</p>
        @endif
        @if ($settings['glass_lens_options_enabled'] && ! empty($record->lens_options))
            <section aria-labelledby="glass-print-lens-heading">
                <h3 class="h6" id="glass-print-lens-heading">Glasses and use</h3>
                <ul class="app-prescription-lens-list list-unstyled d-flex flex-wrap column-gap-3 row-gap-1">
                    @foreach ($record->lens_options as $option)
                        <li><i class="fa-regular fa-square-check"></i> {{ $lensOptions[$option] ?? $option }}</li>
                    @endforeach
                </ul>
            </section>
        @endif
        @if ($settings['glass_disclaimer_enabled'] && filled($record->disclaimer))
            <section class="app-prescription-advice" aria-labelledby="glass-print-disclaimer-heading">
                <h3 class="h6" id="glass-print-disclaimer-heading">Remarks</h3>
                <ul class="mb-0 ps-3">
                    @foreach (preg_split('/\R/', $record->disclaimer) as $remark)
                        @continue(blank($remark))
                        <li>{{ trim($remark) }}</li>
                    @endforeach
                </ul>
            </section>
        @endif
    </section>
@endsection
