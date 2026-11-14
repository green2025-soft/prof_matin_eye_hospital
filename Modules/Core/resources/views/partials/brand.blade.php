@if ($layout['logoUrl'])
    <img class="app-brand-logo" src="{{ $layout['logoUrl'] }}" alt="" width="36" height="36">
@else
    <span class="app-brand-icon" aria-hidden="true">{{ $layout['appInitial'] }}</span>
@endif
<span class="app-brand-copy">
    <strong>{{ $layout['appName'] }}</strong>
    {{-- <small>{{ $layout['tagline'] ?: 'Admin panel' }}</small> --}}
</span>
