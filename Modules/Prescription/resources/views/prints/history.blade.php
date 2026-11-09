@php
    $history = $prescription->history_details;
    $personal = $history?->personal ?? null;
    $labelledHistory = collect([
        'Drug' => $history?->drug ?? null,
        'Investigation' => $history?->investigation ?? null,
        'Personal' => $history?->personal_notes ?? null,
        'Family' => $history?->family ?? null,
        'Surgical' => $history?->surgical ?? null,
        'Allergic' => $history?->allergic ?? null,
        'Other' => $history?->other ?? null,
    ])->filter('filled');
    $habits = collect([
        'Smoker' => $personal?->smoker ?? false,
        'Ex-smoker' => $personal?->ex_smoker ?? false,
        'Alcoholic' => $personal?->alcoholic ?? false,
        'Tobacco' => $personal?->tobacco ?? false,
    ])->filter()->keys()->join(', ');
@endphp

@if (filled($prescription->history) || $labelledHistory->isNotEmpty() || filled($habits))
    <section class="min-height100">
        <h3>History :</h3>
        <div class="mb-1">
            @if (filled($prescription->history))
                @include('prescription::prints.clinical-lines', [
                    'text' => $prescription->history,
                    'details' => $historyNotes,
                ])
            @endif
            @foreach ($labelledHistory as $label => $value)
                <p class="app-prescription-text mb-0">{{ $label }}: {{ $value }}</p>
            @endforeach
            @if (filled($habits))
                <p class="app-prescription-text mb-0">{{ $habits }}</p>
            @endif
        </div>
    </section>
@endif
