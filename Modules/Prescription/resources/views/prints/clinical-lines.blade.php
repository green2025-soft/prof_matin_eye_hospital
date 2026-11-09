@if ($details->isEmpty())
    <p class="app-prescription-text mb-0">{{ trim((string) $text) }}</p>
@else
    @php
        $detailsByName = $details->keyBy('name');
    @endphp
    @foreach (str($text)->explode("\n") as $line)
        @php
            $name = trim($line);
            $detail = $detailsByName->get($name);
            $duration = '';
            if (($detail?->duration_unit ?? null) === 'long_ago') {
                $duration = 'Long time ago';
            } elseif (filled($detail?->duration ?? null)) {
                $duration = trim('for '.$detail->duration.' '.($detail->duration_unit ?? ''));
            }
        @endphp
        @continue($name === '')
        <p class="app-prescription-text mb-0">{{ trim($name.' '.$duration) }}</p>
        @if (filled($detail?->notes ?? null))
            <p class="app-prescription-text mb-0">{{ trim($detail->notes) }}</p>
        @endif
    @endforeach
@endif
