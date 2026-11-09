@if (filled($text))
    <section class="{{ $className ?? '' }}">
        <h3>{{ $heading }}</h3>
        <div class="mb-1">
            @include('prescription::prints.clinical-lines', ['text' => $text, 'details' => $details])
        </div>
    </section>
@endif
