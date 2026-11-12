@php($recordLabel = $actionLabel ?? $model->name ?? 'record')
<div class="d-flex justify-content-end gap-2 text-nowrap">
    @if (($rowPermissions['update'] ?? true) && auth()->user()->can('update', $model))
        <a class="btn btn-outline-secondary btn-sm app-action-button" href="{{ route($routePrefix.'.edit', [$model] + ($routeParameters ?? [])) }}"
            aria-label="Edit {{ $recordLabel }}" title="Edit {{ $recordLabel }}"><i class="fa-solid fa-pen-to-square" aria-hidden="true"></i></a>
    @endif
    @if (\Illuminate\Support\Facades\Route::has($routePrefix.'.destroy')
        && ($rowPermissions['delete'] ?? true) && auth()->user()->can('delete', $model))
        <form class="d-inline-flex" action="{{ route($routePrefix.'.destroy', [$model] + ($routeParameters ?? [])) }}" method="POST" data-confirm="{{ $deleteConfirmation ?? 'Delete this record? This cannot be undone.' }}">
            @csrf
            @method('DELETE')
            <button class="btn btn-outline-danger btn-sm app-action-button" type="submit"
                aria-label="Delete {{ $recordLabel }}" title="Delete {{ $recordLabel }}"><i class="fa-solid fa-trash-can" aria-hidden="true"></i></button>
        </form>
    @endif
</div>
