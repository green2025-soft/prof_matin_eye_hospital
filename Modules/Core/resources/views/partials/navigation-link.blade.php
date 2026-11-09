<a @class(['app-nav-link', 'active' => $active]) href="{{ $url }}" title="{{ $label }}"
    @if ($active) aria-current="page" @endif>
    <span class="app-nav-icon"><i class="fa-solid fa-{{ $icon }}" aria-hidden="true"></i></span>
    <span class="app-nav-copy">{{ $label }}</span>
</a>
