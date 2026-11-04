@extends('prescription::prints.layout')

@section('prescription-actions')
    @can('create', \Modules\Prescription\Models\Prescription::class)
        <a class="btn btn-outline-primary btn-sm" href="{{ route('prescription.prescriptions.create', ['copy' => $record->id]) }}"><i class="fa-solid fa-copy me-2" aria-hidden="true"></i>Use as new</a>
    @endcan
@endsection

@section('prescription-content')
    @php
        $sectionDetails = $record->section_details ?? [];
        $historyDetails = $record->history_details ?? [];
        $personalHistory = array_filter($historyDetails['personal'] ?? []);
        $historyNotes = $historyDetails;
        unset($historyNotes['personal']);
        $examinationDetails = $record->examination_details ?? [];
        if ($examinationDetails['temperature_normal'] ?? false) {
            $examinationDetails['temperature'] = 'Normal';
        } elseif (filled($examinationDetails['temperature'] ?? null)) {
            $examinationDetails['temperature'] .= ' °'.strtoupper($examinationDetails['temperature_unit'] ?? 'c');
        }
        if (! ($examinationDetails['weight_visible'] ?? true)) {
            unset($examinationDetails['weight']);
        }
        if (! ($examinationDetails['height_visible'] ?? true)) {
            unset($examinationDetails['height_feet'], $examinationDetails['height_inches']);
        }
        $examinationLabels = [
            'bp_systolic' => 'BP systolic',
            'bp_diastolic' => 'BP diastolic',
            'pulse' => 'Pulse',
            'temperature' => 'Temperature',
            'weight' => 'Weight',
            'height_feet' => 'Height (feet)',
            'height_inches' => 'Height (inches)',
            'spo2' => 'SpO2',
            'bmi' => 'BMI',
            'bsa' => 'BSA',
            'bmr' => 'BMR',
            'tdee' => 'TDEE',
            'activity' => 'Activity',
            'ideal_weight' => 'Ideal weight (kg)',
            'ideal_bmi' => 'Ideal BMI',
            'extra_calories' => 'Extra calories',
            'target_calories' => 'Target daily calories',
        ];
        $hasHistory = filled($record->history)
            || filled(array_filter($historyNotes, 'filled'))
            || $personalHistory !== [];
        $examinationValues = array_intersect_key($examinationDetails, $examinationLabels);
        $hasExamination = filled($record->examination) || filled(array_filter($examinationValues, 'filled'));
        $hasClinicalNotes = ($settings['general_chief_complaints_enabled'] && filled($record->chief_complaints))
            || ($settings['general_history_enabled'] && $hasHistory)
            || ($settings['general_examination_enabled'] && $hasExamination)
            || ($settings['general_diagnosis_enabled'] && filled($record->diagnosis))
            || ($settings['general_investigations_enabled'] && filled($record->investigations));
    @endphp

    <div class="app-prescription-body">
        @if ($hasClinicalNotes)
            <aside class="app-prescription-clinical" aria-label="Clinical notes">
                @if ($settings['general_chief_complaints_enabled'] && filled($record->chief_complaints))
                    <section>
                        <h3>Chief complaints</h3>
                        @include('prescription::prints.selected-notes', [
                            'noteText' => $record->chief_complaints,
                            'noteDetails' => $sectionDetails['chief_complaints'] ?? [],
                        ])
                    </section>
                @endif
                @if ($settings['general_history_enabled'] && $hasHistory)
                    <section>
                        <h3>History</h3>
                        @if (filled($record->history))
                            @include('prescription::prints.selected-notes', [
                                'noteText' => $record->history,
                                'noteDetails' => $sectionDetails['history'] ?? [],
                            ])
                        @endif
                        @foreach ([
                            'drug' => 'Drug',
                            'investigation' => 'Investigation',
                            'personal_notes' => 'Personal',
                            'family' => 'Family',
                            'surgical' => 'Surgical',
                            'allergic' => 'Allergic',
                            'other' => 'Other',
                        ] as $historyKey => $historyLabel)
                            @if (filled($historyDetails[$historyKey] ?? null))
                                <p>
                                    <strong>{{ $historyLabel }}:</strong>
                                    <span class="app-prescription-text">{{ $historyDetails[$historyKey] }}</span>
                                </p>
                            @endif
                        @endforeach
                        @foreach ([
                            'smoker' => 'Smoker',
                            'ex_smoker' => 'Ex-smoker',
                            'alcoholic' => 'Alcoholic',
                            'tobacco' => 'Tobacco',
                        ] as $personalKey => $personalLabel)
                            @if ($personalHistory[$personalKey] ?? false)
                                <p>{{ $personalLabel }}</p>
                            @endif
                        @endforeach
                    </section>
                @endif
                @if ($settings['general_examination_enabled'] && $hasExamination)
                    <section>
                        <h3>Examination</h3>
                        @if (filled($record->examination))
                            @include('prescription::prints.selected-notes', [
                                'noteText' => $record->examination,
                                'noteDetails' => $sectionDetails['examination'] ?? [],
                            ])
                        @endif
                        @foreach ($examinationLabels as $examinationKey => $examinationLabel)
                            @if (filled($examinationDetails[$examinationKey] ?? null))
                                <p>
                                    <strong>{{ $examinationLabel }}:</strong>
                                    {{ $examinationDetails[$examinationKey] }}
                                </p>
                            @endif
                        @endforeach
                    </section>
                @endif
                @if ($settings['general_diagnosis_enabled'] && filled($record->diagnosis))
                    <section>
                        <h3>Diagnosis</h3>
                        @include('prescription::prints.selected-notes', [
                            'noteText' => $record->diagnosis,
                            'noteDetails' => $sectionDetails['diagnosis'] ?? [],
                        ])
                    </section>
                @endif
                @if ($settings['general_investigations_enabled'] && filled($record->investigations))
                    <section>
                        <h3>Investigations</h3>
                        @include('prescription::prints.selected-notes', [
                            'noteText' => $record->investigations,
                            'noteDetails' => $sectionDetails['investigations'] ?? [],
                        ])
                    </section>
                @endif
            </aside>
        @endif

        <div class="app-prescription-treatment">
            <h2 class="app-prescription-rx" aria-label="Prescription">Rx</h2>
            <ol class="app-prescription-medicine-list">
                @forelse ($medicines as $medicine)
                    <li>
                        <h3>{{ $medicine->medicine_name }}</h3>
                        <div class="app-prescription-dose">
                            @if (filled($medicine->dose))<span>{{ $medicine->dose }}</span>@endif
                            @if (filled($medicine->frequency))<span>{{ $medicine->frequency }}</span>@endif
                            @if (filled($medicine->duration))<span>{{ $medicine->duration }}</span>@endif
                        </div>
                        @if (filled($medicine->instructions))<p class="app-prescription-text">{{ $medicine->instructions }}</p>@endif
                    </li>
                @empty
                    <li class="list-unstyled text-body-secondary">No medicines prescribed.</li>
                @endforelse
            </ol>
            @if ($settings['general_advice_enabled'] && filled($record->advice))
                <section class="app-prescription-advice">
                    <h3>Advice</h3>
                    @include('prescription::prints.selected-notes', [
                        'noteText' => $record->advice,
                        'noteDetails' => $sectionDetails['advice'] ?? [],
                    ])
                </section>
            @endif
            @if ($settings['general_follow_up_enabled']
                && ($record->follow_up_date !== null
                    || $record->follow_up_interval !== null
                    || filled($record->follow_up_notes)))
                @php
                    $followUpPrefix = '';
                    if ($record->follow_up_interval !== null) {
                        $followUpUnit = $record->follow_up_interval_unit === 'months' ? 'মাস' : 'দিন';
                        $followUpPrefix = $record->follow_up_interval.' '.$followUpUnit.' পর';
                    } elseif ($record->follow_up_date !== null) {
                        $followUpPrefix = $record->follow_up_date->format('d-m-Y').' তারিখে';
                    }
                    $followUpNotes = array_filter(
                        preg_split('/\r?\n/', (string) $record->follow_up_notes),
                        'filled',
                    );
                @endphp
                <section class="app-prescription-follow-up">
                    <h3>Follow-up</h3>
                    @forelse ($followUpNotes as $followUpNote)
                        <p class="app-prescription-text">{{ trim($followUpPrefix.' '.$followUpNote) }}</p>
                    @empty
                        <p>{{ $followUpPrefix }}</p>
                    @endforelse
                </section>
            @endif
        </div>
    </div>
@endsection
