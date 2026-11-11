<div class="app-page-heading d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h1 class="h4 fw-semibold mb-1">{{ $title }}</h1>
        @if (($description ?? '') !== '')
            <p class="small text-body-secondary mb-0">{{ $description }}</p>
        @endif
    </div>
    @if (! empty($create))
        @can('create', $create['model'])
            <a class="btn btn-primary btn-sm" href="{{ $create['url'] }}"><i class="fa-solid fa-plus me-2" aria-hidden="true"></i>{{ $create['label'] }}</a>
        @endcan
    @endif
</div>
