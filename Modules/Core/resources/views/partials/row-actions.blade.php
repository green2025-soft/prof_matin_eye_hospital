<div class="d-flex justify-content-end gap-2 text-nowrap">
    @can('update', $model)
        <a class="btn btn-outline-secondary btn-sm app-action-button" href="{{ route($routePrefix.'.edit', $model) }}" aria-label="Edit {{ $model->name }}" title="Edit {{ $model->name }}"><i class="fa-solid fa-pen-to-square" aria-hidden="true"></i></a>
    @endcan
    @if (\Illuminate\Support\Facades\Route::has($routePrefix.'.destroy'))
        @can('delete', $model)
            <form class="d-inline-flex" action="{{ route($routePrefix.'.destroy', $model) }}" method="POST" data-confirm="{{ $deleteConfirmation ?? 'Delete this record? This cannot be undone.' }}">
                @csrf
                @method('DELETE')
                <button class="btn btn-outline-danger btn-sm app-action-button" type="submit" aria-label="Delete {{ $model->name }}" title="Delete {{ $model->name }}"><i class="fa-solid fa-trash-can" aria-hidden="true"></i></button>
            </form>
        @endcan
    @endif
</div>
