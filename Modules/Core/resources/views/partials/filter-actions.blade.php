<div class="d-flex gap-2">
    <input name="per_page" type="hidden" value="{{ $filters['per_page'] ?? config('pagination.default') }}">
    <button class="btn btn-primary" type="submit"><i class="fa-solid fa-filter me-2" aria-hidden="true"></i>Apply</button>
    <a class="btn btn-outline-secondary" href="{{ route($routePrefix.'.index', ['per_page' => $filters['per_page'] ?? config('pagination.default')]) }}" title="Clear filters"><i class="fa-solid fa-rotate-left me-2" aria-hidden="true"></i>Reset</a>
</div>
