<div class="app-tags">
    @forelse ($labels as $label)
        <span class="app-tag">{{ $label }}</span>
    @empty
        <span class="small text-body-secondary">{{ ($empty ?? '') !== '' ? $empty : '—' }}</span>
    @endforelse
</div>
