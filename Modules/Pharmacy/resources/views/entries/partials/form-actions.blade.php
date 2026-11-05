<div class="app-form-actions d-flex flex-wrap justify-content-between align-items-center gap-3 border-top pt-3 mt-4">
    <a class="btn btn-warning" href="{{ route($routePrefix.'.index', $routeParameters) }}">Cancel</a>
    <button class="btn btn-primary" type="submit" aria-keyshortcuts="Control+Enter"><i class="fa-solid fa-check me-2" aria-hidden="true"></i>{{ $submitLabel }}</button>
</div>
