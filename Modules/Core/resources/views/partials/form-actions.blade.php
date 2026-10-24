<div class="app-form-actions d-flex flex-wrap justify-content-end gap-2 border-top pt-3 mt-4">
    @can('viewAny', is_string($model) ? $model : get_class($model))
        <a class="btn btn-outline-secondary" href="{{ route($routePrefix.'.index') }}">Cancel</a>
    @else
        <a class="btn btn-outline-secondary" href="{{ route('home') }}">Dashboard</a>
    @endcan
    @if ($canSubmit ?? true)
        <button class="btn btn-primary" type="submit"><i class="fa-solid fa-check me-2" aria-hidden="true"></i>{{ $submitLabel }}</button>
    @endif
</div>
