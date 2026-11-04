@php
    $detailsByName = array_column($noteDetails, null, 'name');
@endphp

@if ($detailsByName === [])
    <p class="app-prescription-text">{{ $noteText }}</p>
@else
    @foreach (preg_split('/\r?\n/', $noteText) as $note)
        @php
            $noteName = trim($note);
            $noteDetail = $detailsByName[$noteName] ?? [];
            $durationUnit = $noteDetail['duration_unit'] ?? '';
        @endphp
        @if (filled($noteName))
            <p>
                <strong>{{ $noteName }}</strong>
                @if ($durationUnit === 'long_ago')
                    <span>Long time ago</span>
                @elseif (filled($noteDetail['duration'] ?? null))
                    <span>for {{ $noteDetail['duration'] }} {{ $durationUnit }}</span>
                @endif
            </p>
            @if (filled($noteDetail['notes'] ?? null))
                <p class="app-prescription-text">{{ $noteDetail['notes'] }}</p>
            @endif
        @endif
    @endforeach
@endif
