@php
    $examination = $prescription->examination_details;
    $pulse = $examination?->pulse ?? null;
    $pulseRhythm = collect([
        'regular' => 'Regular',
        'irregular' => 'Irregular',
        'drop_beat' => 'Drop-beat',
    ])->get($examination?->pulse_rhythm ?? '', '');
    if (filled($pulse) && filled($pulseRhythm)) {
        $pulse .= ' ('.$pulseRhythm.')';
    }

    $temperature = $examination?->temperature ?? null;
    if ($examination?->temperature_normal ?? false) {
        $temperature = 'Normal';
    } elseif (filled($temperature)) {
        $temperature .= ' °'.strtoupper($examination?->temperature_unit ?? 'c');
    }

    $heightVisible = $examination?->height_visible ?? true;
    $odWithoutGlasses = data_get($examination, 'visual_acuity.od_without_glasses');
    $odWithGlasses = data_get($examination, 'visual_acuity.od_with_glasses');
    $osWithoutGlasses = data_get($examination, 'visual_acuity.os_without_glasses');
    $osWithGlasses = data_get($examination, 'visual_acuity.os_with_glasses');
    $measurements = collect([
        'V/A (Without / With Glasses)' => collect([
            'OD' => filled($odWithoutGlasses) || filled($odWithGlasses)
                ? (filled($odWithoutGlasses) ? $odWithoutGlasses : '—').' / '.(filled($odWithGlasses) ? $odWithGlasses : '—')
                : null,
            'OS' => filled($osWithoutGlasses) || filled($osWithGlasses)
                ? (filled($osWithoutGlasses) ? $osWithoutGlasses : '—').' / '.(filled($osWithGlasses) ? $osWithGlasses : '—')
                : null,
        ])->filter('filled'),
        'A/S' => collect([
            'OD (Right Eye)' => data_get($examination, 'anterior_segment.od'),
            'OS (Left Eye)' => data_get($examination, 'anterior_segment.os'),
        ])->filter('filled'),
        'IOP (mmHg)' => collect([
            'OD' => data_get($examination, 'iop.od'),
            'OS' => data_get($examination, 'iop.os'),
        ])->filter('filled'),
        'FL Stain' => collect([
            'OD' => data_get($examination, 'fluorescein_stain.od'),
            'OS' => data_get($examination, 'fluorescein_stain.os'),
        ])->filter('filled'),
        "Schirmer's Test (mm)" => collect([
            'OD' => data_get($examination, 'schirmer_test.od'),
            'OS' => data_get($examination, 'schirmer_test.os'),
        ])->filter('filled'),
        'Fundus Oculi' => collect([
            'OD' => data_get($examination, 'fundus_oculi.od'),
            'OS' => data_get($examination, 'fundus_oculi.os'),
        ])->filter('filled'),
        'General' => collect([
            'BP systolic' => $examination?->bp_systolic ?? null,
            'BP diastolic' => $examination?->bp_diastolic ?? null,
            'Pulse' => $pulse,
            'Temperature' => $temperature,
            'Weight' => ($examination?->weight_visible ?? true) ? ($examination?->weight ?? null) : null,
            'Height (feet)' => $heightVisible ? ($examination?->height_feet ?? null) : null,
            'Height (inches)' => $heightVisible ? ($examination?->height_inches ?? null) : null,
            'SpO2' => $examination?->spo2 ?? null,
            'BMI' => $examination?->bmi ?? null,
            'BSA' => $examination?->bsa ?? null,
            'BMR' => $examination?->bmr ?? null,
            'TDEE' => $examination?->tdee ?? null,
            'Activity' => $examination?->activity ?? null,
            'Ideal weight (kg)' => $examination?->ideal_weight ?? null,
            'Ideal BMI' => $examination?->ideal_bmi ?? null,
            'Extra calories' => $examination?->extra_calories ?? null,
            'Target daily calories' => $examination?->target_calories ?? null,
        ])->filter('filled'),
    ]);
    $hasMeasurements = $measurements->contains(fn ($group) => $group->isNotEmpty());
@endphp

@if (filled($prescription->examination) || $hasMeasurements)
    <section class="min-height100">
        <h3>O/E :</h3>
        <div class="mb-1">
            @if (filled($prescription->examination))
                @include('prescription::prints.clinical-lines', [
                    'text' => $prescription->examination,
                    'details' => $examinationNotes,
                ])
            @endif
            @foreach ($measurements as $heading => $group)
                @if ($group->isNotEmpty())
                    <div class="app-prescription-preview-group mt-2">
                        <h4>{{ $heading }}</h4>
                        @foreach ($group as $label => $value)
                            <p class="app-prescription-text mb-0">{{ $label }}: {{ $value }}</p>
                        @endforeach
                    </div>
                @endif
            @endforeach
        </div>
    </section>
@endif
