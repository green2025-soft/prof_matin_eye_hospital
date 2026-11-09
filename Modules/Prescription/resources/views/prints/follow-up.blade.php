@php
    $prefix = '';
    if ($prescription->follow_up_interval !== null) {
        $unit = $prescription->follow_up_interval_unit === 'months' ? 'মাস' : 'দিন';
        $prefix = $prescription->follow_up_interval.' '.$unit.' পর';
    } elseif ($prescription->follow_up_date !== null) {
        $prefix = $prescription->follow_up_date->format('d-m-Y').' তারিখে';
    }
    $followUpLines = collect(preg_split('/\r?\n/', (string) $prescription->follow_up_notes))
        ->filter('filled');
@endphp

@if (filled($prefix) || $followUpLines->isNotEmpty())
    <section>
        <h3>Follow-up</h3>
        <div class="mb-1">
            @forelse ($followUpLines as $line)
                <p class="app-prescription-text mb-0">{{ trim($prefix.' '.trim($line)) }}</p>
            @empty
                <p class="app-prescription-text mb-0">{{ $prefix }}</p>
            @endforelse
        </div>
    </section>
@endif
