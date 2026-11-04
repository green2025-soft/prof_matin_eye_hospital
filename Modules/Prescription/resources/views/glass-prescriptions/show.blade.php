@extends('prescription::prints.layout')

@section('prescription-content')
    <section class="app-glass-print-body" aria-labelledby="glass-print-heading">
        <h2 class="h6 mb-3" id="glass-print-heading">Eye prescription</h2>
        <div class="table-responsive">
            <table class="table table-bordered app-glass-print-table align-middle">
                <caption class="visually-hidden">Right and left eye distance and near vision prescription</caption>
                <thead><tr><th scope="col">Eye</th><th scope="col">Vision</th><th scope="col">SPH</th><th scope="col">CYL</th><th scope="col">Axis</th><th scope="col">V/A</th></tr></thead>
                <tbody>
                    <tr>
                        <th scope="row">Right (OD)</th>
                        <td>DV</td>
                        <td>{{ $record->r_sphere_dv ?? '--' }}</td>
                        <td>{{ $record->r_cylinder_dv ?? '--' }}</td>
                        <td>{{ $record->r_axis_dv ?? '--' }}</td>
                        <td>{{ $record->r_acuity_dv ?? '--' }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Right (OD)</th>
                        <td>NV</td>
                        <td>{{ $record->r_sphere_nv ?? '--' }}</td>
                        <td>{{ $record->r_cylinder_nv ?? '--' }}</td>
                        <td>{{ $record->r_axis_nv ?? '--' }}</td>
                        <td>{{ $record->r_acuity_nv ?? '--' }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Left (OS)</th>
                        <td>DV</td>
                        <td>{{ $record->l_sphere_dv ?? '--' }}</td>
                        <td>{{ $record->l_cylinder_dv ?? '--' }}</td>
                        <td>{{ $record->l_axis_dv ?? '--' }}</td>
                        <td>{{ $record->l_acuity_dv ?? '--' }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Left (OS)</th>
                        <td>NV</td>
                        <td>{{ $record->l_sphere_nv ?? '--' }}</td>
                        <td>{{ $record->l_cylinder_nv ?? '--' }}</td>
                        <td>{{ $record->l_axis_nv ?? '--' }}</td>
                        <td>{{ $record->l_acuity_nv ?? '--' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        @if ($settings['glass_pd_enabled'] && filled($record->pd))
            <p class="app-glass-print-pd"><strong>PD:</strong> {{ $record->pd }} mm</p>
        @endif
        @if ($settings['glass_lens_options_enabled'] && ! empty($record->lens_options))
            <section aria-labelledby="glass-print-lens-heading">
                <h3 class="h6" id="glass-print-lens-heading">Glasses and use</h3>
                <ul class="app-prescription-lens-list">
                    @foreach ($record->lens_options as $option)
                        <li>{{ $lensOptions[$option] ?? $option }}</li>
                    @endforeach
                </ul>
            </section>
        @endif
        @if ($settings['glass_disclaimer_enabled'] && filled($record->disclaimer))
            <section class="app-prescription-advice" aria-labelledby="glass-print-disclaimer-heading">
                <h3 class="h6" id="glass-print-disclaimer-heading">Disclaimer / advice</h3>
                <p class="app-prescription-text">{{ $record->disclaimer }}</p>
            </section>
        @endif
    </section>
@endsection
