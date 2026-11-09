@extends('prescription::prints.layout')

@section('prescription-actions')
    @if ($canCreate)
        <a class="btn btn-info text-danger btn-sm" href="{{ $copyUrl }}"><i class="fa-solid fa-copy me-2"></i>Use as new</a>
    @endif
@endsection

@section('prescription-content')
    <div class="app-prescription-body">
        <aside class="app-prescription-clinical">
            @if ($chiefComplaints !== null)
                @include('prescription::prints.selected-notes', [
                    'heading' => 'C/C :',
                    'className' => 'min-height100',
                    'text' => $prescription->chief_complaints,
                    'details' => $chiefComplaints,
                ])
            @endif
            @if ($historyNotes !== null)
                @include('prescription::prints.history')
            @endif
            @if ($examinationNotes !== null)
                @include('prescription::prints.examination')
            @endif
            @if ($investigationNotes->isNotEmpty())
                <section class="min-height100">
                    <h3>Investigation :</h3>
                    <ul>
                        @foreach ($investigationNotes as $investigation)
                            <li>{{ $investigation->name }}</li>
                        @endforeach
                    </ul>
                </section>
            @endif
            @if ($diagnosisNotes !== null)
                @include('prescription::prints.selected-notes', [
                    'heading' => 'Diagnosis :',
                    'className' => 'min-height100',
                    'text' => $prescription->diagnosis,
                    'details' => $diagnosisNotes,
                ])
            @endif
        </aside>

        <div class="app-prescription-treatment">
            <h5><u>Treatment Plan</u></h5>
            {{-- <h3 class="app-prescription-rx">℞</h3> --}}
            <h5><i class="fa-solid fa-prescription" aria-hidden="true"></i></h5>
            <ol class="app-prescription-medicine-list">
                @forelse ($medicines as $medicine)
                    <li>
                        <h3>
                            {{ $medicine->medicine_name }}
                            @if (filled($medicine->generic?->name))
                                <span class="fw-normal">({{ $medicine->generic->name }})</span>
                            @endif
                        </h3>
                        <p class="app-prescription-text">{{ $medicine->frequency }} {{ $medicine->dose }} {{ $medicine->instructions }} {{ $medicine->duration }} </p>
                    </li>
                @empty
                    <li class="list-unstyled text-body-secondary">No medicines prescribed.</li>
                @endforelse
            </ol>

            @if ($adviceNotes !== null)
                @include('prescription::prints.selected-notes', [
                    'heading' => 'Advice',
                    'text' => $prescription->advice,
                    'details' => $adviceNotes,
                ])
            @endif
            @if ($showFollowUp)
                @include('prescription::prints.follow-up')
            @endif
        </div>
    </div>
@endsection
