<ul class="list-group list-group-flush mb-0" aria-label="{{ $caption }}">
    @forelse ($records as $record)
        <li class="list-group-item bg-transparent p-0">
            <a class="app-quick-link align-items-start" href="{{ route($showRoute, $record->id) }}">
                <span class="d-flex flex-column flex-grow-1 overflow-hidden">
                    <span class="fw-semibold text-break">{{ $record->patient?->name ?? 'Patient unavailable' }}</span>
                    <span class="small text-body-secondary text-break">{{ $record->rx_number ?? '#'.$record->id }} · {{ $record->patient?->patient_id }}</span>
                    <span class="small text-body-secondary text-break">{{ $record->doctor?->name ?? 'Doctor unavailable' }}</span>
                </span>
                <span class="d-flex flex-column align-items-end gap-1 flex-shrink-0">
                    <time class="small text-body-secondary" datetime="{{ $record->prescription_date->toDateString() }}">{{ $record->prescription_date->format('d M Y') }}</time>
                    <span class="small fw-semibold">View <i class="fa-solid fa-arrow-right ms-1" aria-hidden="true"></i></span>
                </span>
            </a>
        </li>
    @empty
        <li class="app-dashboard-empty">
            <i class="fa-solid fa-file-prescription" aria-hidden="true"></i>
            <h3>No prescriptions yet</h3>
            <p>Saved prescriptions will appear here.</p>
        </li>
    @endforelse
</ul>
