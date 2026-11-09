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
    $measurements = collect([
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
    ])->filter('filled');
@endphp

@if (filled($prescription->examination) || $measurements->isNotEmpty())
    <section class="min-height100">
        <h3>O/E :</h3>
        <div class="mb-1">
            @if (filled($prescription->examination))
                @include('prescription::prints.clinical-lines', [
                    'text' => $prescription->examination,
                    'details' => $examinationNotes,
                ])
            @endif
            @foreach ($measurements as $label => $value)
                <p class="app-prescription-text mb-0">{{ $label }}: {{ $value }}</p>
            @endforeach
        </div>
    </section>
@endif
