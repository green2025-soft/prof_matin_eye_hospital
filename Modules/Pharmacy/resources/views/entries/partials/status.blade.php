<span class="badge {{ $record->voided_at ? 'text-bg-secondary' : 'text-bg-success' }}">
    {{ $record->voided_at ? 'Voided' : 'Posted' }}
</span>
